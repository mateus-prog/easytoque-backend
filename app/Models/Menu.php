<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class Menu extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'menus';
	public $fillable = [
        'name', 'icon', 'url', 'type_menu_id'
    ];
	public $searchable = [
        'name', 'icon', 'url', 'type_menu_id'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "name" => $this->name,
            "icon" => $this->icon,
            "url" => $this->url,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }

}