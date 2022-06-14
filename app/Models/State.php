<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class State extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'states';
	public $fillable = [
        'name', 'initials'
    ];
	public $searchable = [
        'name', 'initials'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "name" => $this->name,
            "initials" => $this->initials,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }

}