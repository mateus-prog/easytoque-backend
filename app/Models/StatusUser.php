<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

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

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "name" => $this->name,
            "status" => $this->status,
            "created_at" => Format::formatDateTime($this->created_at),
            "updated_at" => Format::formatDateTime($this->updated_at),
        ];
    }

}