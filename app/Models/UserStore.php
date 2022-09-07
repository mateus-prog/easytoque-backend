<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class UserStore extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'user_store';
	public $fillable = [
        'commission', 'store_id', 'user_id', 'client_id'
    ];
	public $searchable = [
        'commission', 'store_id', 'user_id', 'client_id'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "commission" => $this->commission,
            "store_id" => $this->store_id,
            "client_id" => $this->client_id,
        ];
    }
}