<?php

namespace Source\App;

use DateTime;
use DateTimeZone;
use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\User;
use Source\Support\Message;
use Source\Models\Collaborator;
use Source\Support\Email;
use Source\Models\Scheduling;
use Source\Models\Exam;
use Source\Models\Branch;
use Source\Models\Complementary;

/**
 * Class Web
 * @package Source\App
 */
class Web extends Controller
{
    /** @var User */
    private $user;

    /**
     * Web constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
        $this->user = Auth::user();
    }

    public function root(): void
    {
        if (!Auth::user()) {
            redirect("/entrar");
        } else {
            redirect("/painel");
        }
    }

    /**
     * Page Inicio
     */
    public function home(): void
    {
        if (!Auth::user()) {
            redirect("/");
        }

        $branch = (new Branch())->find()->fetch(true);
        $collaborator = (new Collaborator())->find("clients_id = :id", "id={$this->user->id}")->fetch(true);
        $exam = (new Exam())->find()->fetch(true);
        $complementary = (new Complementary())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("home", [
            "head" => $head,
            "menu" => "home",
            "branch" => $branch,
            "colaborator" => $collaborator,
            "complementary" => $complementary,
            "exams" => $exam
        ]);
    }

    /** Lista de Eventos **/
    public function events(): void
    {
        $lista = (new Scheduling)->find("id_clients in (9999,{$this->user->id})")->fetch(true);

        if ($lista) {
            foreach ($lista as $item) {
                $arrComp = explode(",", $item->complementary);
                if ($arrComp) {
                    $comp = [];
                    foreach ($arrComp as $value) {
                        $comp[] = (new Complementary())->find("id = :id", "id={$value}")->fetch();
                    }

                    $result = [];
                    foreach ($comp as $c) {
                        $result[] = ($c->description ?? null);
                    }

                    $complement = implode(",", $result);
                }

                if ($item->status == "block") {
                    $events[] = [
                        "id" => $item->id,
                        "status" => $item->status,
                        "title" => "Horário não disponível",
                        "start" => $item->start,
                        "end" => $item->end,
                        "observation" => $item->observation,
                        "color" => "#FF0000"
                    ];
                } else {
                    $events[] = [
                        "id" => $item->id,
                        "branch" => $item->branch()->company,
                        "title" => $item->collaborator()->name,
                        "client" => $item->client()->company,
                        "collaborator" => $item->collaborator()->name,
                        "office" => $item->office,
                        "function" => $item->function,
                        "doctor" => $item->doctor()->name,
                        "exam" => $item->exam()->description,
                        "complementary" => ($complement ?? null),
                        "start" => $item->start,
                        "end" => $item->end,
                        "status" => $item->status,
                        "observation" => $item->observation,
                        "color" => "#069"
                    ];
                }
            }
        }

        $events = ($events ?? null);
        echo json_encode($events);
        return;
    }

    /** Cadastro de Eventos **/
    public function scheduling(array $data): void
    {
        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("ERRO! Ocorreu um problema ao realizar o agendamento, informe o administrador!")->flash();
                echo json_encode($json);
                return;
            }

            //Criando o Objeto para incluir no banco
            $sched = new Scheduling;
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $timeTerminate = date('H:i:s', strtotime('+30 minute', strtotime($data["time_initial"])));

            //Pegando horario de trabalho
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $businessInitial = new DateTime("08:00", $timezone);
            $businessTerminal = new DateTime("18:00", $timezone);

            $iHours = new DateTime($data["time_initial"], $timezone);

            $start = "{$data["date_initial"]} {$data["time_initial"]}";
            $schedDate = new DateTime($start);

            //Verificando se esta dentro do horario de trabalho
            if ($iHours->format("H:i") < $businessInitial->format("H:i") || $iHours->format("H:i") > $businessTerminal->format("H:i")) {
                $json["message"] = $this->message->warning("Horário do agendamento esta fora do horario de espediente, verifique!")->render();
                echo json_encode($json);
                return;
            }

            //Verificando se não esta dentro do horario de bloqueio
            $result = (new Scheduling())->find("status = :s", "s=block")->fetch(true);
            if ($result) {
                foreach ($result as $r) {
                    $start = new DateTime($r->start);
                    $end = new DateTime($r->end);

                    if ($schedDate > $start && $schedDate < $end) {
                        $json["message"] = $this->message->warning("Agendamento inválido, horário que deseja agendar esta bloqueado")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }

            $sched->id_branchs = $data["branch"];
            $sched->id_clients = $this->user->id;
            $sched->id_collaborators = $data["collaborator"];
            $sched->office = $data["office"];
            $sched->function = $data["function"];
            $sched->id_exams = $data["exams"];
            $sched->complementary = implode(",", $data["complementary"]);
            $sched->start = "{$data["date_initial"]} {$data["time_initial"]}";
            $sched->end = "{$data["date_initial"]} {$timeTerminate}";
            $sched->status = "pending";
            $sched->observation = $data["observation"];

            if (!$sched->save()) {
                $json["message"] = $sched->message()->flash();
                header("Location: " . url());
            }

            $json["message"] = $this->message->success("SUCESSO! Seu agendamento foi concluído!")->flash();
            $json["redirect"] = url();
            echo json_encode($json);
            return;
        }
    }

    /** Remover Agendamento */
    public function removeScheduling($data): void
    {
        $sched = (new Scheduling())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($sched) {
            $sched->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        header("Location: " . url());
    }

    /**
     * Page Collaborator
     */
    public function collaborator(): void
    {
        if (!Auth::user()) {
            redirect("/");
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        $collaborator = (new Collaborator())->find("clients_id = :i", "i={$this->user->id}")->fetch(true);
        echo $this->view->render("collaborator", [
            "head" => $head,
            "collaborator" => $collaborator,
            "menu" => "func"
        ]);
    }

    public function newCollaborator(?array $data): void
    {
        if (!Auth::user()) {
            redirect("/");
        }

        if (!empty($data["csrf"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Desculpe, tivemos um problema ao cadastrar o funcionário, entre em contato com a Empresa Administradora")->render();
                echo json_encode($json);
                return;
            }

            // REMOVENDO A OBRIGATORIEDADE DAS INFORMAÇÔES - 24/08/2021
            // if (!is_email($data["mail"])) {
            //     $json["message"] = $this->message->warning("E-mail informado não é válido, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }

            // if (!is_cpf($data["document"])) {
            //     $json["message"] = $this->message->warning("CPF informado não é válido, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }

            // $cpf = str_replace([".", "-"], ["", ""], $data["document"]);
            // $document = (new Collaborator())->find("clients_id = :id AND document = :d", "id={$this->user->id}&d={$cpf}")->fetch();
            // if ($document) {
            //     $json["message"] = $this->message->warning("CPF informado já esta cadastrado, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }

            // $mail = (new Collaborator())->find("clients_id = :id AND mail = :m", "id={$this->user->id}&m={$data["mail"]}")->fetch();
            // if ($mail) {
            //     $json["message"] = $this->message->warning("CPF informado já esta cadastrado, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }

            $collaborator = new Collaborator();
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            list($d, $m, $y) = explode("/", $data["birth_date"]);

            $collaborator->clients_id = $this->user->id;
            $collaborator->name = $data["name"];
            $collaborator->document = str_replace([".", "-"], ["", ""], $data["document"]);
            $collaborator->birth_date = "{$y}-{$m}-{$d}";
            /** VERIFICAR COMO FAZER */
            $collaborator->office = $data["office"];
            $collaborator->function = $data["function"];
            $collaborator->mail = $data["mail"];
            $collaborator->phone = str_replace(["(", ")", "-"], ["", "", ""], $data["phone"]);
            $collaborator->celphone = str_replace(["(", ")", "-"], ["", "", ""], $data["celphone"]);
            $collaborator->address = $data["address"];
            $collaborator->complement = $data["complement"];
            $collaborator->neight = $data["neight"];
            $collaborator->city = $data["city"];
            $collaborator->uf = $data["uf"];
            $collaborator->uf_code = str_replace("-", "", $data["uf_code"]);

            // $password = substr(strtolower($data["name"]), 0, 5) . date("Y");
            // $collaborator->password = passwd($password);

            if (!$collaborator->save()) {
                $json["message"] = $collaborator->message()->render();
                echo json_encode($json);
                return;
            }

            // //Envio de Login da empresa por e-mail
            // (new Email())->bootstrap(
            //     "[CONFIRMAÇÃO] Cadastro realizado com sucesso!",
            //     "
            //     Prezado(a)s,<br>
            //     {$collaborator->name}, <br><br>
            //         Seu login de acesso ao nosso sistema é: <br>
            //         Login: <b>{$collaborator->document}</b><br>
            //         Senha: <b>{$password}</b>
            //         <br>
            //         <br>
            //         Caso não tenha nosso App baixado clique no link abaixo:
            //         <br>
            //         <a href='#'>Baixar Aplicativo da Polimed</a>
            //     ",
            //     $collaborator->mail,
            //     "{$collaborator->name}"
            // )->send();

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/funcionarios");
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("new-collaborator", [
            "head" => $head,
            "menu" => "func"
        ]);
    }

    public function editCollaborator(?array $data): void
    {
        if (!Auth::user()) {
            redirect("/");
        }

        $verify = (new Collaborator())->find("id = :id", "id={$data["code"]}")->fetch();
        if ($verify->clients_id != $this->user->id) {
            $json["message"] = $this->message->error("O Colaborador que tentou acessar não é vinculado à sua empresa!")->flash();
            header("Location: " . url("/funcionarios"));
            echo json_encode($json);
            return;
        }

        if (!empty($data["update"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Desculpe, tivemos um problema ao cadastrar o funcionário, entre em contato com a Empresa Administradora")->render();
                echo json_encode($json);
                return;
            }

            // if (!is_email($data["mail"])) {
            //     $json["message"] = $this->message->warning("E-mail informado não é válido, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }

            // if (!is_cpf($data["document"])) {
            //     $json["message"] = $this->message->warning("CPF informado não é válido, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }


            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $collaborator = (new Collaborator())->findById($data["code"]);
            list($d, $m, $y) = explode("/", $data["birth_date"]);
            $collaborator->clients_id = $this->user->id;
            $collaborator->name = $data["name"];
            $collaborator->document = str_replace([".", "-"], ["", ""], $data["document"]);
            $collaborator->birth_date = "{$y}-{$m}-{$d}";
            /** VERIFICAR COMO FAZER */
            $collaborator->office = $data["office"];
            $collaborator->function = $data["function"];
            $collaborator->mail = $data["mail"];
            $collaborator->phone = str_replace(["(", ")", "-"], ["", "", ""], $data["phone"]);
            $collaborator->celphone = str_replace(["(", ")", "-"], ["", "", ""], $data["celphone"]);
            $collaborator->address = $data["address"];
            $collaborator->complement = $data["complement"];
            $collaborator->neight = $data["neight"];
            $collaborator->city = $data["city"];
            $collaborator->uf = $data["uf"];
            $collaborator->uf_code = str_replace("-", "", $data["uf_code"]);

            if (!$collaborator->save()) {
                $json["message"] = $collaborator->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/funcionarios");
            echo json_encode($json);
            return;
        }

        $collaborator = (new Collaborator())->find("clients_id = :c and id = :i", "c={$this->user->id}&i={$data["code"]}")->fetch();
        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("edit-collaborator", [
            "head" => $head,
            "collaborator" => $collaborator,
            "menu" => "func"
        ]);
    }

    public function removeCollaborator($data): void
    {
        $collaborator = (new Collaborator())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($collaborator) {
            $collaborator->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        $json["redirect"] = url("/funcionarios");
        echo json_encode($json);
        return;
    }

    /**
     * Auth Login
     */
    public function login(?array $data): void
    {

        if (!empty($data['csrf'])) {

            //Verificando se o csrf é valido ou não
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor user o formulário")->render();
                echo json_encode($json);
                return;
            }

            //Verificando o limite
            if (request_limit("weblogin", 10, 60 * 5)) {
                $json['message'] = $this->message->error("Você já efetuou 10 tentativas, esse é o limite. Por favor aguarde por 5 minutos para tentar novamente!")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['cnpj']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu CNPJ ou senha para entrar")->render();
                echo json_encode($json);
                return;
            }

            $save = (!empty($data['save']) ? true : false);
            $auth = new Auth();
            $login = $auth->login($data['cnpj'], $data['password'], $save);

            if ($login) {
                $json['redirect'] = url('/');
            } else {
                $json['message'] = $auth->message()->render();
            }


            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Entrar - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/entrar"),
            theme("/assets/images/shared.png")
        );
        echo $this->view->render("auth-login", [
            "head" => $head,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail"),
        ]);
    }

    /**
     * Auth Register
     * @param null|array $data
     */
    public function register(?array $data): void
    {
        // Verificando se existe o campo CSRF;
        if (!empty($data['csrf'])) {

            //Verificando se o csrf é valido ou não
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor user o formulário")->render();
                echo json_encode($json);
                return;
            }

            //Verificando se não existe campos vazio.
            if (in_array("", $data)) {
                $json['message'] = $this->message->info("Informe seus dados para criar sua conta.")->render();
                echo json_encode($json);
                return;
            }

            //Verificando o campo Repetir senha
            if (empty($data["password_re"]) || $data["password"] != $data["password_re"]) {
                $json["message"] = $this->message->warning("Senhas digitadas inválidas, verifique!")->render();
                echo json_encode($json);
                return;
            }


            $auth = new Auth();
            $user = new User();

            //Informando dado a dado pois é muito valido informar todos os campos por segurança
            $user->bootstrap(
                $data["fullname"],
                $data["email"],
                $data["password"]
            );

            //Efetuando o registro
            if ($auth->register($user)) {
                $json['redirect'] = url("/");
            } else {
                $json['message'] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Cadastrar - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/registrar"),
            theme("/assets/images/shared.png")
        );
        echo $this->view->render("auth-register", [
            "head" => $head
        ]);
    }

    public function reset(array $data): void
    {
        if (Auth::user()) {
            redirect("/");
        }

        if (!empty($data['csrf'])) {
            //Verificando se o csrf é valido ou não
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor user o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["password"]) || empty($data["password_re"])) {
                $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
                echo json_encode($json);
                return;
            }

            list($email, $code) = explode("|", $data["code"]);
            $col = new Collaborator();

            if ($col->reset($email, $code, $data["password"], $data["password_re"])) {
                $json["message"] = $this->message->success("Senha auterada com sucesso.")->render();
            } else {
                $json["message"] = $col->message()->render();
            }

            echo json_encode($json);
            return;
        }


        $head = $this->seo->render(
            "Crie sua nova senha no " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/recuperar"),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("auth-reset", [
            "head" => $head,
            "code" => ($data["code"] ?? null)
        ]);
    }

    /**
     * APP LOGOUT
     */
    public function logout()
    {
        (new Message())->info("Você saiu com sucesso " . $this->user->fantasy  . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }

    /**
     * @param array $data
     */
    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problemas":
                $error->code = "OPS";
                $error->title = "Estamos enfrentando problemas!";
                $error->message = "Parece que nosso serviço não está disponível, Já estamos vendo isso mas caso precise, envie-nos um e-mail :)";
                $error->linkTitle = "Enviar E-mail!";
                $error->link = "mailto:" . CONF_MAIL_SUPPORT;
                break;

            case "manutencao":
                $error->code = "OPS";
                $error->title = "Desculpe. Estamos em manutenção";
                $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para você controlar melhor as suas contas :P";
                $error->linkTitle = null;
                $error->link = null;
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Ooops. Conteúdo indiponivel :/";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponivel no momento ou foi removido!";
                $error->linkTitle = "Continue navegando!";
                $error->link = url_back();
                break;
        }

        $head = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("/ops/{$error->code}"),
            theme("/assets/images/shared.png"),
            false
        );

        echo $this->view->render("error", [
            "head" => $head,
            "error" => $error
        ]);
    }
}
