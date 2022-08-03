<?php

namespace App\Services\Logo;

use Illuminate\Support\Facades\Http;
use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\UserCorporateRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class LogoService
{   
    protected $_inputUpload;
    protected $_pathUpload;
    protected $_logoDefault;

    public function __construct()
    {
        $this->_inputUpload = 'url_logo';
        $this->_pathUpload = 'logos'; 
        $this->_logoDefault = $this->_pathUpload.'/logo_default.png';

        $this->userRepository = new UserRepository();
        $this->userCorporateRepository = new UserCorporateRepository();
    }

    public function getUserCorporateId(){
        //pega o id do usuario logado
        $userId = Auth::user()->id;

        $userCorporate = $this->userCorporateRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'id');
        
        return (int) $userCorporate[0]['id'];
    }

    public function getPathLogo(){
        $id = $this->getUserCorporateId();
        $userCorporate = $this->userCorporateRepository->findById($id);

        return $userCorporate->url_logo == '' ? $this->_logoDefault : $userCorporate->url_logo;
    }
}