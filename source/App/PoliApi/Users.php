<?php

namespace Source\App\PoliApi;

use Source\Models\Scheduling;
use Source\Models\Branch;

class Users extends PoliApi
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        $user = $this->user->data();
        $response["user"] = $user;
        unset($user->password); //Removendo a senha do retorno
        
        $this->back($response);
        return;
    }

    public function update(array $data): void
    {
        $response["data"] = $data;
        $this->back($response);
        return;
    }
    
    /** Lista de Eventos **/
    public function events($data): void 
    {
        $user = $this->user->data();

        if(empty($data["status"])){
            $this->call(
                400,
                "status_empty",
                "O Parametro STATUS não foi encontrado na requisição, verifique!"
            )->back();
            return;
        }

        $lista = (new Scheduling)->find("id_collaborators = :id AND status = :s", "id={$user->id}&s={$data["status"]}")->fetch(true);

        if($lista) {
            foreach($lista as $item){  
                $branch = (new Branch())->find("id = :id", "id={$item->id_branchs}")->fetch();
                $branch = $branch->data();

                if($item->status == "pending"){
                    $color = "#f5d142";
                }

                if($item->status == "success"){
                    $color = "#00FF00";
                }

                if($item->status == "occurrence"){
                    $color = "#FF0000";
                }

                $events[] = [
                    "id" => $item->id,
                    "branchs" => $branch,
                    "title" => $item->collaborator()->name,
                    "client" => $item->client()->company,
                    "collaborator" => $item->collaborator()->name,
                    "doctor" => $item->doctor()->name,
                    "exam" => $item->exam()->description,
                    "start" => $item->start,
                    "end" => $item->end,
                    "status" => $item->status,
                    "observation" => $item->observation,
                    "color" => $color,
                ];
            }
        }

        $events["scheduling"] = $events;
        $this->back(($events["scheduling"] ?? null));
        return;
    }

    /** Lista de Eventos **/
    public function history(): void 
    {
        $user = $this->user->data();
        $lista = (new Scheduling)->find("id_collaborators = :id", "id={$user->id}")->fetch(true);

        if($lista) {
            foreach($lista as $item){  
                $branch = (new Branch())->find("id = :id", "id={$item->id_branchs}")->fetch();
                $branch = $branch->data();

                if($item->status == "pending"){
                    $color = "#f5d142";
                }

                if($item->status == "success"){
                    $color = "#00FF00";
                }

                if($item->status == "occurrence"){
                    $color = "#FF0000";
                }

                $events[] = [
                    "id" => $item->id,
                    "branchs" => $branch,
                    "title" => $item->collaborator()->name,
                    "client" => $item->client()->company,
                    "collaborator" => $item->collaborator()->name,
                    "doctor" => $item->doctor()->name,
                    "exam" => $item->exam()->description,
                    "start" => $item->start,
                    "end" => $item->end,
                    "status" => $item->status,
                    "observation" => $item->observation,
                    "color" => $color,
                ];
            }
        }

        $events["scheduling"] = $events;
        $this->back(($events["scheduling"] ?? null));
        return;
    }

}