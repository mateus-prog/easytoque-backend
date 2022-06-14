<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class Bank extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'banks';
	public $fillable = [
        'code', 'name'
    ];
	public $searchable = [
        'code', 'name'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "code" => $this->code,
            "name" => $this->name,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }

}