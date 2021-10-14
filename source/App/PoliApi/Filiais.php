<?php

namespace Source\App\PoliApi;

use Source\Models\Branch;

class Filiais extends PoliApi
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        $filial = (new Branch())->find()->fetch(true);

        if($filial){
            foreach($filial as $item){
                $branchs[] = [
                    "company" => $item->company,
                    "fantasy" => $item->fantasy,
                    "mail" => $item->mail,
                    "phone" => $item->phone,
                    "celphone" => $item->celphone,
                    "street" => $item->street,
                    "number" => $item->number,
                    "neight" => $item->neight,
                ];
            }
        }

        $response["branchs"] = ($branchs ?? null);
        $this->back($response);
        return;
    }
}