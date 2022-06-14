<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

class StatusRequest extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'status_request';
	public $fillable = [
        'name', 'description', 'color'
    ];
	public $searchable = [
        'name', 'description', 'color'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "color" => $this->color,
            "created_at" => Format::formatDateTime($this->created_at),
            "updated_at" => Format::formatDateTime($this->updated_at)
        ];
    }

}