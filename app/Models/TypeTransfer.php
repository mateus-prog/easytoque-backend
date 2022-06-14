<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class TypeTransfer extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'type_transfers';
	public $fillable = [
        'name'
    ];
	public $searchable = [
        'name'
    ];

    public $timestamps = true;

}