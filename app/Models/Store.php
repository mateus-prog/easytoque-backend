<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class Store extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'stores';
	public $fillable = [
        'id', 'client_id'
    ];
	public $searchable = [
        'id', 'client_id'
    ];

    public $timestamps = false;

    public function format()
    {
        return (object) [
            "client_id" => $this->client_id,
        ];
    }
}