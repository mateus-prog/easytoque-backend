<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;
use App\Helpers\Format;

class UserClickSign extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'user_clicksign';
	public $fillable = [
        'signatario_key', 'document_key', 'request_signature_key', 'user_id', 'created_at', 'updated_at'
    ];
	public $searchable = [
        'signatario_key', 'document_key', 'request_signature_key', 'user_id', 'created_at', 'updated_at'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "signatario_key" => $this->signatario_key,
            "document_key" => $this->document_key,
            "request_signature_key" => $this->request_signature_key,
            "user_id" => $this->user_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}