<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Traits\TraitBuilder;
use App\Traits\TraitCollection;

class UserBank extends BaseModel
{
    use TraitCollection, TraitBuilder;

    public $table = 'user_bank_data';
	public $fillable = [
        'bank_id', 'agency', 'agency_digit', 'checking_account', 'checking_account_digit', 'pix', 'user_id'
    ];
	public $searchable = [
        'bank_id', 'agency', 'agency_digit', 'checking_account', 'checking_account_digit', 'pix', 'user_id'
    ];

    public $timestamps = true;

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "bank_id" => $this->bank_id,
            "agency" => $this->agency_digit != null ? $this->agency . '-' . $this->agency_digit : $this->agency,
            "checking_account" => $this->checking_account_digit != null ? $this->checking_account . '-' . $this->checking_account_digit : $this->checking_account,
            "pix" => $this->pix != null ? $this->pix : 'Não informado',
            "type_transfers_id" => $this->type_transfers_id,
        ];
    }
}