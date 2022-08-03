<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class UserRoles extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'role_user';
	public $fillable = [
       'id', 'role_id', 'user_id'
    ];
	public $searchable = [
        'id', 'role_id', 'user_id'
    ];

    public $timestamps = false;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "role_id" => $this->role_id,
            "user_id" => $this->user_id,
        ];
    }
}
