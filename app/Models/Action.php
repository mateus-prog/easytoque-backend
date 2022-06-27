<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

class Action extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'actions';
	public $fillable = [
        'name', 'display_name', 'updated_at'
    ];
	public $searchable = [
        'name', 'display_name', 'updated_at'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "name" => $this->name,
            "display_name" => $this->display_name,
            "created_at" => Format::formatDateTime($this->created_at),
            "updated_at" => Format::formatDateTime($this->updated_at),
        ];
    }
}