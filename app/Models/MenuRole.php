<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class MenuRole extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'menus_roles';
	public $fillable = [
        'role_id', 'menu_id'
    ];
	public $searchable = [
        'role_id', 'menu_id'
    ];

    public $timestamps = false;

}