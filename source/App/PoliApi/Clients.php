<?php

namespace Source\App\PoliApi;

use Source\Models\Collaborator;
use Source\Support\Email;

class Clients extends PoliApi
{
    public function __construct()
    {
        parent::__construct();
    }

    public function forget(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_EMAIL);
        $email = $data["email"];

        if(!is_email($email)){
            $response["message"] = "Informe um e-mail válido para continuar!";
            $this->back($response);
            return;
        }

        $mail = (new Collaborator())->findByEmail($email);
        if(!$mail){
            $response["message"] = "E-mail informado não esta cadastrado em nosso sistema, por favor, verifique!";
            $this->back($response);
            return;
        }

        $mail->forget = md5(uniqid(rand(), true));
        $mail->save();

        //Envio de Recuperação de E-mail
        (new Email())->bootstrap(
            "[RECUPERAÇÃO] Recupere sua senha!",
            "
            Prezado(a)s,<br>
            {$mail->name}, <br><br>
                Clique no botão abaixo para alterar sua senha. <br>
                <a class='btn btn-blue' href='".url("/resetar/{$mail->mail}|{$mail->forget}")."'>Alterar Senha</a>
            ",
            $mail->mail,
            "{$mail->name}"
        )->send();


        $response["message"] = "Enviamos um e-mail para sua recuperação de senha, verifique!";
        $this->back($response);
        return;
    }


}