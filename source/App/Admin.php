<?php

namespace Source\App;

use DateTime;
use DateTimeZone;
use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\User;
use Source\Support\Message;
use Source\Models\Client;
use Source\Support\Email;
use Source\Models\Branch;
use Source\Models\Doctor;
use Source\Models\Exam;
use Source\Models\Complementary;
use Source\Models\Scheduling;
use Source\Models\Collaborator;

/**
 * Class Web
 * @package Source\App
 */
class Admin extends Controller
{
    /** @var User */
    private $user;

    /**
     * Web constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_ADMIN . "/");
        $this->user = Auth::userAdm();
    }

    public function root(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin/login");
        } else {
            redirect("/admin/dashboard");
        }
    }

    /**
     * Page Inicio
     */
    public function home(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $branch = (new Branch())->find()->fetch(true);
        $client = (new Client())->find()->fetch(true);
        $exams = (new Exam())->find()->fetch(true);
        $complementary = (new Complementary())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );
        echo $this->view->render("home", [
            "head" => $head,
            "menu" => "dash",
            "user" => $this->user->fullname,
            "branchs" => $branch,
            "clients" => $client,
            "exams" => $exams,
            "complementary" => $complementary
        ]);
    }

    public function scheduling(array $data): void
    {
        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("PROBLEMA! Ocorreu um erro ao efetuar o agendamento, por favor solicite o suporte!")->render();
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
            if($iHours->format("H:i") < $businessInitial->format("H:i") || $iHours->format("H:i") > $businessTerminal->format("H:i")){
                $json["message"] = $this->message->warning("Horário do agendamento esta fora do horario de espediente, verifique!")->render();
                echo json_encode($json);
                return;
            }

            //Verificando se não esta dentro do horario de bloqueio
            $result = (new Scheduling())->find("status = :s", "s=block")->fetch(true);
            if($result){
                foreach($result as $r){
                    $start = new DateTime($r->start);
                    $end = new DateTime($r->end);
    
                    if($schedDate > $start && $schedDate < $end ){
                        $json["message"] = $this->message->warning("Agendamento inválido, horário que deseja agendar esta bloqueado")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }
        
            $sched->id_branchs = $data["branchs"];
            $sched->id_clients = $data["clients"];
            $sched->id_collaborators = $data["colaboration"];
            $sched->office = $data["office"];
            $sched->function = $data["function"];
            $sched->id_exams = $data["exams"];
            $sched->complementary = implode(",", $data["complementary"]);
            $sched->start = "{$data["date_initial"]} {$data["time_initial"]}";
            $sched->end = "{$data["date_initial"]} {$timeTerminate}";
            $sched->status = "pending";
            $sched->observation = $data["observation"];

            if (!$sched->save()) {
                $json["message"] = $sched->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("SUCESSO! Seu Agendamento foi concluído!")->flash();
            $json["redirect"] = url("/admin/dashboard");
            echo json_encode($json);
            return;
        }
    }

    /** Lista de Eventos **/
    public function events(): void
    {
        $lista = (new Scheduling)->find()->fetch(true);

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

                if ($item->status == "pending") {
                    $color = "#f5d142";
                }

                if ($item->status == "success") {
                    $color = "#00FF00";
                }

                if ($item->status == "occurrence") {
                    $color = "#FF0000";
                }

                if ($item->status == "block") {
                    $events[] = [
                        "id" => $item->id,
                        "status" => $item->status,
                        "title" => "Horário não disponível - ".$item->observation,
                        "start" => $item->start,
                        "end" => $item->end,
                        "observation" => $item->observation,
                        "color" => "#FF0000"
                    ];
                } else {
                    $events[] = [
                        "id" => $item->id,
                        "branch" => $item->branch()->company,
                        "title" => ($item->collaborator()->name ?? null),
                        "client" => $item->client()->fantasy,
                        "collaborator" => ($item->collaborator()->name ?? null),
                        "office" => ($item->office ?? null),
                        "function" => ($item->function ?? null),
                        "exam" => $item->exam()->description,
                        "complementary" => $complement,
                        "doctor" => $item->doctor()->name,
                        "start" => $item->start,
                        "end" => $item->end,
                        "status" => $item->status,
                        "observation" => $item->observation,
                        "color" => $color
                    ];
                }
            }
        }

        echo json_encode($events);
        return;
    }

    /** Alterar status Agendamento */
    public function alterStatus($data): void
    {
        $sched = (new Scheduling())->findById($data["code"]);
        $sched->status = $data["status"];

        if (!$sched->save()) {
            $json["message"] = "Ocorreu um erro ao alterar o status, solicite o suporte!";
            echo json_encode($json);
            return;
        }

        $json["redirect"] = url("/admin/dashboard");
        echo json_encode($json);
        return;
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
        header("Location: " . url("/admin/dashboard"));
    }


    public function listCollaborator(array $data): void
    {
        $collaborator = (new Collaborator())->find("clients_id = :i", "i={$data["code"]}")->fetch(true);

        if ($collaborator) {
            foreach ($collaborator as $item) {
                $json[] = [
                    "id" => $item->id,
                    "name" => $item->name
                ];
            }
        }
        echo json_encode($json);
        return;
    }

    public function infosCollaborator(array $data): void
    {
        $collaborator = (new Collaborator())->find("id = :i", "i={$data["code"]}")->fetch(true);

        if ($collaborator) {
            foreach ($collaborator as $item) {
                $json["collaborator"] = [
                    "office" => $item->office,
                    "function" => $item->function
                ];
            }
        }
        echo json_encode($json);
        return;
    }

    /**
     * Page Médicos
     */
    public function doctors(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $doctors = (new Doctor())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("doctors", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "medicos",
            "doctors" => $doctors
        ]);
    }

    public function newDoctor(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["csrf"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("ERRO! tivemos um problema ao cadastrar seu registro, por favor, chame o suporte!")->render();
                echo json_encode($json);
                return;
            }

            $doctor = new Doctor();
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $doctor->name = $data["name"];
            $doctor->crm = $data["crm"];
            $doctor->street = $data["street"];
            $doctor->number = $data["number"];
            $doctor->neight = $data["neight"];
            $doctor->city = $data["city"];
            $doctor->uf = $data["uf"];
            $doctor->uf_code = $data["uf_code"];

            if (!$doctor->save()) {
                $json["message"] = $doctor->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/medicos");
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("new-doctors", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "medicos"
        ]);
    }

    public function editDoctor(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["update"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("ERRO! tivemos um problema ao cadastrar seu registro, por favor, chame o suporte!")->render();
                echo json_encode($json);
                return;
            }

            $doctor = (new Doctor())->findById($data["code"]);

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $doctor->name = $data["name"];
            $doctor->crm = $data["crm"];
            $doctor->street = $data["street"];
            $doctor->number = $data["number"];
            $doctor->neight = $data["neight"];
            $doctor->city = $data["city"];
            $doctor->uf = $data["uf"];
            $doctor->uf_code = $data["uf_code"];

            if (!$doctor->save()) {
                $json["message"] = $doctor->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/medicos");
            echo json_encode($json);
            return;
        }

        $doctor = (new Doctor())->find("id = :id", "id={$data["code"]}")->fetch();

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("edit-doctors", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "medicos",
            "doctor" => $doctor
        ]);
    }

    public function removeDoctor($data): void
    {
        $doctor = (new Doctor())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($doctor) {
            $doctor->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        $json["redirect"] = url("/admin/medicos");
        echo json_encode($json);
        return;
    }

    /**
     * Page Bloqueio de Horarios
     */
    public function blocks(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $blocks = (new Scheduling())->find("status = :s", "s=block")->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("blocks", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "block",
            "blocks" => $blocks
        ]);
    }

    public function newBlocks(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["csrf"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Tivemos um problema ao cadastrar seu registro, por favor acione o suporte!")->render();
                echo json_encode($json);
                return;
            }

            $timezone = new DateTimeZone('America/Sao_Paulo');
            $businessInitial = new DateTime("08:00", $timezone);
            $businessTerminal = new DateTime("18:00", $timezone);

            $iHours = new DateTime($data["timer_initial"], $timezone);
            $fHours = new DateTime($data["timer_final"], $timezone);


            if ($iHours->format("H:i") < $businessInitial->format("H:i") || $iHours->format("H:i") > $businessTerminal->format("H:i")) {
                $json["message"] = $this->message->warning("Horário inicial fora do horario de espediente, verifique!")->render();
                echo json_encode($json);
                return;
            }

            if ($fHours->format("H:i") > $businessTerminal->format("H:i") || $fHours->format("H:i") < $businessInitial->format("H:i")) {
                $json["message"] = $this->message->warning("Horário final fora do horario de espediente, verifique!")->render();
                echo json_encode($json);
                return;
            }

            if ($fHours->format("H:i") < $iHours->format("H:i")) {
                $json["message"] = $this->message->warning("Horário final é menor que Horário inicial, verifique!")->render();
                echo json_encode($json);
                return;
            }

            list($d, $m, $y) = explode("/", $data["date"]);

            $iniHours = "{$y}-{$m}-{$d} {$data["timer_initial"]}";
            $finHours = "{$y}-{$m}-{$d} {$data["timer_final"]}";

            $block = new Scheduling();
            $block->id_clients = 9999;
            $block->start = $iniHours;
            $block->end = $finHours;
            $block->status = "block";
            $block->observation = $data["observation"];

            if (!$block->save()) {
                $json["message"] = $block->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Registro incluido com sucesso")->flash();
            $json["redirect"] = url("/admin/horarios-bloqueados");

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("new-blocks", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "block"
        ]);
    }

    public function editBlocks(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["update"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Tivemos um problema ao cadastrar seu registro, por favor acione o suporte!")->render();
                echo json_encode($json);
                return;
            }

            $timezone = new DateTimeZone('America/Sao_Paulo');
            $businessInitial = new DateTime("08:00", $timezone);
            $businessTerminal = new DateTime("18:00", $timezone);

            $iHours = new DateTime($data["timer_initial"], $timezone);
            $fHours = new DateTime($data["timer_final"], $timezone);


            if ($iHours->format("H:i") < $businessInitial->format("H:i") || $iHours->format("H:i") > $businessTerminal->format("H:i")) {
                $json["message"] = $this->message->warning("Horário inicial fora do horario de espediente, verifique!")->render();
                echo json_encode($json);
                return;
            }

            if ($fHours->format("H:i") > $businessTerminal->format("H:i") || $fHours->format("H:i") < $businessInitial->format("H:i")) {
                $json["message"] = $this->message->warning("Horário final fora do horario de espediente, verifique!")->render();
                echo json_encode($json);
                return;
            }

            if ($fHours->format("H:i") < $iHours->format("H:i")) {
                $json["message"] = $this->message->warning("Horário final é menor que Horário inicial, verifique!")->render();
                echo json_encode($json);
                return;
            }

            list($d, $m, $y) = explode("/", $data["date"]);

            $iniHours = "{$y}-{$m}-{$d} {$data["timer_initial"]}";
            $finHours = "{$y}-{$m}-{$d} {$data["timer_final"]}";

            $block = (new Scheduling())->findById($data["code"]);
            $block->id_clients = 9999;
            $block->start = $iniHours;
            $block->end = $finHours;
            $block->status = "block";
            $block->observation = $data["observation"];

            if (!$block->save()) {
                $json["message"] = $block->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Registro alterado com sucesso")->flash();
            $json["redirect"] = url("/admin/horarios-bloqueados");

            echo json_encode($json);
            return;
        }

        $block = (new Scheduling())->find("id = :id", "id={$data["code"]}")->fetch();


        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("edit-blocks", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "block",
            "block" => $block
        ]);
    }

    public function removeBlocks($data): void
    {
        $block = (new Scheduling())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($block) {
            $block->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        $json["redirect"] = url("/admin/horarios-bloqueados");
        echo json_encode($json);
        return;
    }



    /**
     * Page Clientes
     */
    public function clients(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $clients = (new Client())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("clients", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "clients",
            "clients" => $clients
        ]);
    }

    public function newClients(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data['csrf'])) {

            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("PROBLEMA! Ocorreu um erro ao verificar dados para cadastro, solicite o suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (!is_cnpj($data["document"])) {
                $json["message"] = $this->message->warning("ATENÇÃO! CNPJ informado não é válido, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $client = new Client();
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $cnpj =  preg_replace('/[^0-9]/', '', (string) $data["document"]);
            $document = (new Client())->find("document = :d", "d={$cnpj}")->fetch();
            if ($document) {
                $json["message"] = $this->message->warning("CNPJ já cadastrado, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $client->company = $data["company"];
            $client->document = $cnpj;
            $client->fantasy = $data["fantasy"];
            $client->ie = $data["ie"];
            $client->mail = $data["mail"];
            $client->phone = str_replace(["(", ")", "-"], ["", "", ""], $data["phone"]);
            $client->celphone = str_replace(["(", ")", "-"], ["", "", ""], $data["celphone"]);
            $client->street = $data["street"];
            $client->complement = $data["complement"];
            $client->number = $data["number"];
            $client->neight = $data["neight"];
            $client->city = $data["city"];
            $client->uf = $data["uf"];
            $client->uf_code = str_replace([".", "-"], ["", ""], $data["uf_code"]);

            $password = substr(strtolower($data["company"]), 0, 4) . date("Y");
            $client->password = passwd($password);

            if (!$client->save()) {
                $json["message"] = $client->message()->render();
                echo json_encode($json);
                return;
            }

            //Envio de Login da empresa por e-mail
            (new Email())->bootstrap(
                "[CONFIRMAÇÃO] Cadastro realizado com sucesso!",
                "
                Prezado(a)s,<br>
                {$client->company}, <br><br>
                    Seu login de acesso ao nosso sistema é: <br>
                    Login: <>{$client->document}</><br>
                    Senha: <b>{$password}</b>
                    <br>
                    <br>
                    <a href='" . url() . "'>Acessar o Sistema de Agendamento</a>
                ",
                $client->mail,
                "{$client->fantasy}"
            )->queue();

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/clientes");
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );
        echo $this->view->render("new-clients", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "clients"
        ]);
    }

    public function editClients(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data['update'])) {

            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("PROBLEMA! Ocorreu um erro ao verificar dados para cadastro, solicite o suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (!is_cnpj($data["document"])) {
                $json["message"] = $this->message->warning("ATENÇÃO! CNPJ informado não é válido, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $cnpj =  preg_replace('/[^0-9]/', '', (string) $data["document"]);
            $document = (new Client())->find("document = :d and id <> :id", "d={$cnpj}&id={$data["code"]}")->fetch();
            if ($document) {
                $json["message"] = $this->message->warning("CNPJ já cadastrado, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $client = (new Client())->findById($data["code"]);

            $client->company = $data["company"];
            $client->document = $cnpj;
            $client->fantasy = $data["fantasy"];
            $client->ie = $data["ie"];
            $client->mail = $data["mail"];
            $client->phone = str_replace(["(", ")", "-"], ["", "", ""], $data["phone"]);
            $client->celphone = str_replace(["(", ")", "-"], ["", "", ""], $data["celphone"]);
            $client->street = $data["street"];
            $client->complement = $data["complement"];
            $client->number = $data["number"];
            $client->neight = $data["neight"];
            $client->city = $data["city"];
            $client->uf = $data["uf"];
            $client->uf_code = str_replace([".", "-"], ["", ""], $data["uf_code"]);

            if (!$client->save()) {
                $json["message"] = $client->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/clientes");
            echo json_encode($json);
            return;
        }

        $client = (new Client())->find("id = :id", "id={$data["code"]}")->fetch();
        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );
        echo $this->view->render("edit-clients", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "clients",
            "clients" => $client
        ]);
    }


    public function removeClients($data): void
    {
        $clients = (new Client())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($clients) {
            $clients->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        $json["redirect"] = url("/admin/clientes");
        echo json_encode($json);
        return;
    }

    /**
     * Page Colaboradores
     */
    public function collaborators(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $collaborator = (new Collaborator())->find("clients_id != 1")->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("collaborators", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "collaborator",
            "collaborators" => $collaborator
        ]);
    }

    public function newCollaborator(?array $data): void
    {
        if (!Auth::userAdm()) {
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
            // $document = (new Collaborator())->find("document = :d", "d={$cpf}")->fetch();
            // if ($document) {
            //     $json["message"] = $this->message->warning("CPF informado já esta cadastrado, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }

            // $mail = (new Collaborator())->find("mail = :m", "m={$data["mail"]}")->fetch();
            // if ($mail) {
            //     $json["message"] = $this->message->warning("E-mail informado já esta cadastrado, por favor verifique!")->render();
            //     echo json_encode($json);
            //     return;
            // }

            $collaborator = new Collaborator();
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            list($d, $m, $y) = explode("/", $data["birth_date"]);

            $collaborator->clients_id = $data["clients"];
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
            $json["redirect"] = url("/admin/colaboradores");
            echo json_encode($json);
            return;
        }

        $clients = (new Client())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("new-collaborator", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "collaborator",
            "clients" => $clients
        ]);
    }

    public function editCollaborator(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/");
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
            $collaborator->clients_id = $data["clients"];
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
            $json["redirect"] = url("/admin/colaboradores");
            echo json_encode($json);
            return;
        }

        $clients = (new Client())->find()->fetch(true);
        $collaborator = (new Collaborator())->find("id = :id", "id={$data["code"]}")->fetch();
        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("edit-collaborator", [
            "head" => $head,
            "user" => $this->user->fullname,
            "collaborator" => $collaborator,
            "menu" => "collaborator",
            "clients" => $clients
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
        $json["redirect"] = url("/admin/colaboradores");
        echo json_encode($json);
        return;
    }

    /**
     * Page Filiais
     */
    public function branchs(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $branch = (new Branch())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("branchs", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "branch",
            "branchs" => $branch
        ]);
    }

    public function newBranchs(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["csrf"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("PROBLEMA! Ocorreu um erro ao inserir seu registro, por favor, solicite o suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (!is_cnpj($data["document"])) {
                $json["message"] = $this->message->warning("CNPJ digitado inválido, por favor verifique!");
                echo json_encode($json);
                return;
            }

            if (!is_email($data["mail"])) {
                $json["message"] = $this->message->warning("E-mail digitado inválido, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $cnpj =  preg_replace('/[^0-9]/', '', (string) $data["document"]);
            $document = (new Branch())->find("document = :d", "d={$cnpj}")->fetch();
            if ($document) {
                $json["message"] = $this->message->warning("CNPJ já cadastrado, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $branch = new Branch();
            $branch->company = $data["company"];
            $branch->fantasy = $data["fantasy"];
            $branch->document = str_replace(["/", ".", "-"], ["", "", ""], $data["document"]);
            $branch->ie = str_replace(".", "", $data["ie"]);
            $branch->street = $data["street"];
            $branch->number = $data["number"];
            $branch->complement = $data["complement"];
            $branch->neight = $data["neight"];
            $branch->city = $data["city"];
            $branch->uf = $data["uf"];
            $branch->uf_code = str_replace("-", "", $data["uf_code"]);
            $branch->phone = str_replace(["-", "(", ")"], ["", "", ""], $data["phone"]);
            $branch->celphone = str_replace(["-", "(", ")"], ["", "", ""], $data["celphone"]);
            $branch->mail = $data["mail"];

            if (!$branch->save()) {
                $json["message"] = $branch->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/filiais");
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("new-branchs", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "branch"
        ]);
    }

    public function editBranchs(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["update"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("PROBLEMA! Ocorreu um erro ao inserir seu registro, por favor, solicite o suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (!is_cnpj($data["document"])) {
                $json["message"] = $this->message->warning("CNPJ digitado inválido, por favor verifique!");
                echo json_encode($json);
                return;
            }

            if (!is_email($data["mail"])) {
                $json["message"] = $this->message->warning("E-mail digitado inválido, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $branch = (new Branch())->findById($data["code"]);
            $branch->company = $data["company"];
            $branch->fantasy = $data["fantasy"];
            $branch->document = str_replace(["/", ".", "-"], ["", "", ""], $data["document"]);
            $branch->ie = str_replace(".", "", $data["ie"]);
            $branch->street = $data["street"];
            $branch->number = $data["number"];
            $branch->complement = $data["complement"];
            $branch->neight = $data["neight"];
            $branch->city = $data["city"];
            $branch->uf = $data["uf"];
            $branch->uf_code = str_replace("-", "", $data["uf_code"]);
            $branch->phone = str_replace(["-", "(", ")"], ["", "", ""], $data["phone"]);
            $branch->celphone = str_replace(["-", "(", ")"], ["", "", ""], $data["celphone"]);
            $branch->mail = $data["mail"];

            if (!$branch->save()) {
                $json["message"] = $branch->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("SUCESSO! Seu registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/filiais");
            echo json_encode($json);
            return;
        }

        $branch = (new Branch())->find("id = :id", "id={$data["code"]}")->fetch();

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("edit-branchs", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "branch",
            "branch" => $branch
        ]);
    }

    public function removeBranchs($data): void
    {
        $branch = (new Branch())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($branch) {
            $branch->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        $json["redirect"] = url("/admin/filiais");
        echo json_encode($json);
        return;
    }

    /**
     * Exames
     */
    public function exams(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $exams = (new Exam())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("exams", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "exams",
            "exams" => $exams
        ]);
    }

    public function newExams(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["csrf"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Não foi possivel efetuar seu cadastro, por favor, chame no suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json["message"] = $this->message->warning("Atenção! Nenhum campo pode estar em branco ou sem informação, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $exam = new Exam();
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $exam->description = $data["description"];
            $exam->id_doctors = $data["doctor"];
            $exam->id_branchs = $data["branch"];

            if (!$exam->save()) {
                $json["message"] = $exam->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Sucesso! Registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/exames");
            echo json_encode($json);
            return;
        }

        $branchs = (new Branch())->find()->fetch(true);
        $doctors = (new Doctor())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("new-exams", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "exams",
            "doctors" => $doctors,
            "branchs" => $branchs,
        ]);
    }

    public function editExams(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["update"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Não foi possivel efetuar seu cadastro, por favor, chame no suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json["message"] = $this->message->warning("Atenção! Nenhum campo pode estar em branco ou sem informação, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $exam = (new Exam())->findById($data["code"]);
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $exam->description = $data["description"];
            $exam->id_doctors = $data["doctor"];
            $exam->id_branchs = $data["branch"];

            if (!$exam->save()) {
                $json["message"] = $exam->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Sucesso! Registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/exames");
            echo json_encode($json);
            return;
        }

        $branchs = (new Branch())->find()->fetch(true);
        $doctors = (new Doctor())->find()->fetch(true);
        $exam = (new Exam())->find("id = :id", "id={$data["code"]}")->fetch();

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("edit-exams", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "exams",
            "exam" => $exam,
            "doctors" => $doctors,
            "branchs" => $branchs,
        ]);
    }

    public function removeExams($data): void
    {
        $exam = (new Exam())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($exam) {
            $exam->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        $json["redirect"] = url("/admin/exames");
        echo json_encode($json);
        return;
    }

    /**
     * Complementary
     */
    public function complementary(): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        $com = (new Complementary())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("complementary", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "comp",
            "complementary" => $com
        ]);
    }

    public function newComplementary(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["csrf"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Não foi possivel efetuar seu cadastro, por favor, chame no suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json["message"] = $this->message->warning("Atenção! Nenhum campo pode estar em branco ou sem informação, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $com = new Complementary();
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $com->description = $data["description"];

            if (!$com->save()) {
                $json["message"] = $com->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Sucesso! Registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/complementares");
            echo json_encode($json);
            return;
        }

        $exams = (new Exam())->find()->fetch(true);

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("new-complementary", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "comp",
            "exams" => $exams
        ]);
    }

    public function editComplementary(?array $data): void
    {
        if (!Auth::userAdm()) {
            redirect("/admin");
        }

        if (!empty($data["update"])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Não foi possivel efetuar seu cadastro, por favor, chame no suporte!")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json["message"] = $this->message->warning("Atenção! Nenhum campo pode estar em branco ou sem informação, por favor verifique!")->render();
                echo json_encode($json);
                return;
            }

            $com = (new Complementary())->findById($data["code"]);
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $com->description = $data["description"];

            if (!$com->save()) {
                $json["message"] = $com->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Sucesso! Registro foi concluído!")->flash();
            $json["redirect"] = url("/admin/complementares");
            echo json_encode($json);
            return;
        }

        $exams = (new Exam())->find()->fetch(true);
        $comp = (new Complementary())->find("id = :id", "id={$data["code"]}")->fetch();

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/shared.png")
        );

        echo $this->view->render("edit-complementary", [
            "head" => $head,
            "user" => $this->user->fullname,
            "menu" => "comp",
            "exams" => $exams,
            "comp" => $comp,
        ]);
    }

    public function removeComplementary($data): void
    {
        $comp = (new Complementary())->find(
            "id = :id",
            "id={$data["code"]}"
        )->fetch();

        if ($comp) {
            $comp->destroy();
        }

        $json["message"] = $this->message->success('Registro excluido com sucesso')->flash();
        $json["redirect"] = url("/admin/complementares");
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
            if (request_limit("adminlogin", 5, 60 * 5)) {
                $json['message'] = $this->message->error("Você já efetuou 5 tentativas, esse é o limite. Por favor aguarde por 5 minutos para tentar novamente!")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu e-mail ou senha para entrar")->render();
                echo json_encode($json);
                return;
            }

            $save = (!empty($data['save']) ? true : false);
            $auth = new Auth();
            $login = $auth->loginAdm($data['email'], $data['password'], $save);

            if ($login) {
                $json['redirect'] = url('/admin/dashboard');
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

    /**
     * APP LOGOUT
     */
    public function logout()
    {
        (new Message())->info("Você saiu com sucesso " . Auth::userAdm()->fullname . ". Volte logo :)")->flash();

        Auth::logoutAdm();
        redirect("/admin/login");
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
