<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Core\Session;
use Source\Models\Client;

/**
 * Class Auth
 * @package Source\Models
 */
class Auth extends Model
{
    /**
     * Auth constructor.
     */
    public function __construct()
    {
        parent::__construct("user", ["id"], ["email", "password"]);
    }

    /**
     * @return Client|null
     */
    public static function user(): ?Client
    {
        $session = new Session();
        if (!$session->has("authUser")) {
            return null;
        }

        return (new Client())->find("id = :id", "id={$session->authUser}")->fetch();
    }

     /**
     * @return User|null
     */
    public static function userAdm(): ?User
    {
        $session = new Session();
        if (!$session->has("authAdm")) {
            return null;
        }

        return (new User())->findById($session->authAdm);
    }

    /**
     * Logout
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->unset("authUser");
    }

    /**
     * Logout ADM
     */
    public static function logoutAdm(): void
    {
        $session = new Session();
        $session->unset("authAdm");
    }

    /**
     * @param User $user
     * @return bool
     */
    public function register(User $user): bool
    {
        if (!$user->save()) {
            $this->message = $user->message;
            return false;
        }
        return true;
    }


    public function loginAdm(string $email, string $password, bool $save = false): bool
    {
        //Verificando o e-mail do usuário
        if (!is_email($email)) {
            $this->message->warning("O e-mail informado não é válido");
            return false;
        }

        //Verificando o lembrar dados e criando o cookie do usuário
        if ($save) {
            setcookie("authEmail", $email, time() + 604800, "/");
        } else {
            setcookie("authEmail", null, time() - 3600, "/");
        }

        //Verificando a Senha do usuário
        if (!is_passwd($password)) {
            $this->message->warning("A senha informada não é válida");
            return false;
        }

        //Verificando o E-mail do Usuário
        $user = (new User)->findByEmail($email);
        if (!$user) {
            $this->message->error("O e-mail informado não esta cadastrado");
            return false;
        }

        //Senha informada não confere com a do banco de dados
        if (!passwd_verify($password, $user->password)) {
            $this->message->error("A senha informada não confere");
            return false;
        }

        //Verifica se precisa atualizar a hash da senha;
        if (passwd_rehash($user->password)) {
            $user->password = $password;
            $user->save();
        }

        //Login
        (new Session())->set("authAdm", $user->id);
        return true;
    }


    // Login Cliente
    public function login(string $cnpj, string $password, bool $save = false): bool
    {
        //Verificando o e-mail do usuário
        if (!is_cnpj($cnpj)) {
            $this->message->warning("O CNPJ informado não é válido");
            return false;
        }

        //Verificando o lembrar dados e criando o cookie do usuário
        if ($save) {
            setcookie("authCnpj", $cnpj, time() + 604800, "/");
        } else {
            setcookie("authCnpj", null, time() - 3600, "/");
        }

        //Verificando a Senha do usuário
        if (!is_passwd($password)) {
            $this->message->warning("A senha informada não é válida");
            return false;
        }

        //Verificando o CNPJ do Usuário
        $cnpj = str_replace([".", "/", "-"], ["", "", ""], $cnpj);
        $client = (new Client)->find("document = :c", "c={$cnpj}")->fetch();
        if (!$client) {
            $this->message->error("O CNPJ informado não esta cadastrado");
            return false;
        }

        //Senha informada não confere com a do banco de dados
        if (!passwd_verify($password, $client->password)) {
            $this->message->error("A senha informada não confere");
            return false;
        }

        //Verifica se precisa atualizar a hash da senha;
        if (passwd_rehash($client->password)) {
            $client->password = $password;
            $client->save();
        }

        //Login
        (new Session())->set("authUser", $client->id);
        return true;
    }

    // Login Colaboradores
    public function loginCollaborators(string $document, string $password)
    {
        //Verificando o CPF do usuário
        if (!is_cpf($document)) {
            $this->message->warning("O CPF informado não é válido");
            return false;
        }

        //Verificando a Senha do usuário
        if (!is_passwd($password)) {
            $this->message->warning("A senha informada não é válida");
            return false;
        }

        //Verificando se CPF do Usuário existe no banco
        $document = str_replace([".", "-"], ["", ""], $document);
        $collaborator = (new Collaborator())->find("document = :c", "c={$document}")->fetch();
        if (!$collaborator) {
            $this->message->error("O CPF informado não esta cadastrado em nossa base de dados");
            return false;
        }

        //Senha informada não confere com a do banco de dados
        if (!passwd_verify($password, $collaborator->password)) {
            $this->message->error("A senha informada não confere");
            return false;
        }

        //Verifica se precisa atualizar a hash da senha;
        if (passwd_rehash($collaborator->password)) {
            $collaborator->password = $password;
            $collaborator->save();
        }

        return $collaborator;
    }
}