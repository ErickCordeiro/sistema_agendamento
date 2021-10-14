<?php


namespace Source\Models;

use Source\Core\Model;

class Branch extends Model
{
    public function __construct()
    {
        parent::__construct("branchs", ["id"], ["company", "fantasy", "document", "ie", "street", "number", "city", "neight", "uf", "uf_code", "phone", "mail"]);
    }

}