<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

class UserCorporate extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'user_corporate';
	public $fillable = [
        'corporate_name', 'cnpj', 'address', 'number', 'complement', 'district', 'city', 'cep', 'state_id', 'user_id'
    ];
	public $searchable = [
        'corporate_name', 'cnpj', 'address', 'number', 'complement', 'district', 'city', 'cep', 'state_id', 'user_id'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "corporate_name" => $this->corporate_name,
            "cnpj" => Format::cnpj($this->cnpj),
            "address" => $this->address,
            "number" => $this->number,
            "complement" => $this->complement,
            "district" => $this->district,
            "city" => $this->city,
            "cep" => Format::cep($this->cep),
            "state_id" => $this->state_id,
        ];
    }
}