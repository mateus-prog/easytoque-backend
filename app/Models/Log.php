<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

class Log extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'logs';
	public $fillable = [
        'message', 'status', 'action_id', 'user_changed_id', 'user_modified_id', 'updated_at'
    ];
	public $searchable = [
        'message', 'status', 'action_id', 'user_changed_id', 'user_modified_id', 'updated_at'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "user_changed_id" => $this->user_changed_id,
            "user_modified_id" => $this->user_modified_id,
            "action_id" => $this->action_id,
            "status" => $this->status,
            "message" => $this->message,
            "created_at" => Format::formatDateTime($this->created_at),
            "updated_at" => Format::formatDateTime($this->updated_at),
        ];
    }
}