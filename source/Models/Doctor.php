<?php


namespace Source\Models;

use Source\Core\Model;

class Doctor extends Model
{
    public function __construct()
    {
        parent::__construct("doctors", ["id"], ["name", "crm"]);
    }
}