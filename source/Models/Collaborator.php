<?php

namespace Source\Models;

use Source\Core\Model;

class Collaborator extends Model
{
    public function __construct()
    {
        parent::__construct("collaborators", ["id"], ["clients_id", "name", "office", "document", "mail", "celphone", "birth_date", "password"]);
    }

    public function clients(): ?Client
    {
        if($this->clients_id){
            return (new Client())->findById($this->clients_id);
        }

        return null;
    }

    public function findByEmail(string $email, string $columns = "*"): ?Collaborator
    {
        $find = $this->find("mail = :mail", "mail={$email}", $columns);
        return $find->fetch();
    }

    public function reset(string $email, string $code, string $password, string $passwordRe): bool
    {
        $collaborator = $this->findByEmail($email);

        if(!$collaborator){
            $this->message->warning("A conta para recuperação não foi encontrada");
            return false;
        }

        if($collaborator->forget != $code){
            $this->message->warning("Desculpe, mas o código de verificação não é válido");
            return false;
        }

        if(!is_passwd($password)){
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;
            $this->message->info("Sua senha deve ter entre {$min} e {$max} caracteres");
            return false;
        }

        if($password != $passwordRe){
            $this->message->warning("Você informou duas senhas diferentes");
            return false;
        }

        $collaborator->password = passwd($password);
        $collaborator->forget = null;
        $collaborator->save();

        return true;
    }

   
}