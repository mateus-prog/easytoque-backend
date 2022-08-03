<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

class Request extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'requests';
	public $fillable = [
        'id', 'value', 'user_id', 'status_request_id', 'url_invoice'
    ];
	public $searchable = [
        'id', 'value', 'user_id', 'status_request_id', 'url_invoice'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "value" => 'R$ '.Format::valueBR($this->value),
            "user_id" => $this->user_id,
            "status_request_id" => $this->status_request_id,
            "url_invoice" => $this->url_invoice,
            "created_at" => Format::formatDate($this->created_at),
            "updated_at" => Format::formatDate($this->updated_at)
        ];
    }

}