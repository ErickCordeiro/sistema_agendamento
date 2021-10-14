<?php


namespace Source\Models;

use Source\Core\Model;

class Client extends Model
{
    public function __construct()
    {
        parent::__construct("clients", ["id"], ["company", "fantasy", "document", "ie", "street", "number", "city", "neight", "uf", "uf_code", "phone", "mail", "password"]);
    }
}