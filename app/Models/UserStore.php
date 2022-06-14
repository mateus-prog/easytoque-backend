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
        'image_url', 'commission', 'store_id', 'user_id'
    ];
	public $searchable = [
        'image_url', 'commission', 'store_id', 'user_id'
    ];

    public $timestamps = true;
}