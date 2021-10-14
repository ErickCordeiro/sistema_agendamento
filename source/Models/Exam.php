<?php


namespace Source\Models;

use Source\Core\Model;
use Source\Models\Doctor;
use Source\Models\Branch;

class Exam extends Model
{
    public function __construct()
    {
        parent::__construct("exams", ["id"], ["id_doctors", "id_branchs", "description"]);
    }

        /**
     * @return Doctor|null
     */
    public function doctor(): ?Doctor
    {
        if($this->id_doctors){
            return (new Doctor())->findById($this->id_doctors);
        }

        return null;
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


}