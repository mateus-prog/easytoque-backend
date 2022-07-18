<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class Reason extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'reasons';
	public $fillable = [
        'id', 'reason', 'request_id'
    ];
	public $searchable = [
        'id', 'reason', 'request_id'
    ];

    public $timestamps = false;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "reason" => $this->reason,
            "request_id" => $this->request_id
        ];
    }

}