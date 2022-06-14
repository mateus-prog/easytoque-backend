<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

class Log extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'log';
	public $fillable = [
        'message', 'user_id', 'updated_at'
    ];
	public $searchable = [
        'message', 'user_id', 'updated_at'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "message" => $this->message,
            "created_at" => Format::formatDateTime($this->created_at),
            "updated_at" => Format::formatDateTime($this->updated_at),
        ];
    }
}