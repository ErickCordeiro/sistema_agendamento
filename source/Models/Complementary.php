<?php


namespace Source\Models;

use Source\Core\Model;
use Source\Models\Exam;
use Source\Models\Branch;

class Complementary extends Model
{
    public function __construct()
    {
        parent::__construct("complementary", ["id"], ["id_exams", "description"]);
    }
}