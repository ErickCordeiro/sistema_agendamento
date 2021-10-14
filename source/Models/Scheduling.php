<?php


namespace Source\Models;

use Source\Core\Model;
use Source\Models\Branch;
use Source\Models\Collaborator;
use Source\Models\Client;
use Source\Models\Exam;
use Source\Models\Doctor;

class Scheduling extends Model
{
    public function __construct()
    {
        parent::__construct("schedulings", ["id"], ["start", "end", "status"]);
    }

    /**
     * @return Branch|null
     */
    public function branch(): ?Branch
    {
        if($this->id_branchs){
            return (new Branch())->findById($this->id_branchs);
        }

        return null;
    }

    /**
     * @return Collaborator|null
     */
    public function collaborator(): ?Collaborator
    {
        if($this->id_collaborators){
            return (new Collaborator())->findById($this->id_collaborators);
        }

        return null;
    }

    /**
     * @return Client|null
     */
    public function client(): ?Client
    {
        if($this->id_clients){
            return (new Client())->findById($this->id_clients);
        }

        return null;
    }

        /**
     * @return Exam|null
     */
    public function exam(): ?Exam
    {
        if($this->id_exams){
            return (new Exam())->findById($this->id_exams);
        }

        return null;
    }

    /**
     * @return Doctor|null
     */
    public function doctor(): ?Doctor
    {
        $exam = (new Exam)->find("id = :id", "id={$this->id_exams}", "id_doctors")->fetch();

        if($exam->id_doctors){
            return (new Doctor)->findById($exam->id_doctors);
        }
        return null;
    }
}