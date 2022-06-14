<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class StatusUser extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'status_user';
	public $fillable = [
        'name', 'status'
    ];
	public $searchable = [
        'name', 'status'
    ];

    public $timestamps = true;

}