<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use App\Services\ClickSign\ClickSignService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

class UserClickSignController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $clickSignService;

    protected $environment;
    protected $token;
    protected $keyTemplateDocument;

    public function __construct(
        ClickSignService $clickSignService
    )
    {
        $this->clickSignService = new $clickSignService();
    }

    public function createSigner($userId)
    {
        $resultClickSign = $this->clickSignService->createSigner($userId);
        if(!empty($resultClickSign['errors'])){
            return $this->error($resultClickSign['errors'], HttpStatus::SERVER_ERROR);    
        }
    }

    public function createDocumentTemplate($userId)
    {
        $resultClickSign = $this->clickSignService->createDocumentTemplate($userId);
        if(!empty($resultClickSign['errors'])){
            return $this->error($resultClickSign['errors'], HttpStatus::SERVER_ERROR);    
        }
    }

    public function createSignerDocument($userId)
    {
        $resultClickSign = $this->clickSignService->createSignerDocument($userId);

        if(!empty($resultClickSign['errors'])){
            return $this->error($resultClickSign['errors'], HttpStatus::SERVER_ERROR);    
        }
    }

    public function notificationMail($userId)
    {
        $userClickSign = $this->clickSignService->findByUserClickSign($userId);
        $request_signature_key = $userClickSign[0]['request_signature_key'];
        if($request_signature_key != ''){
            
            $message = 'Por favor, assine o documento digitalmente para concluir seu cadastro como parceiro Easytoque';

            $resultClickSign = $this->clickSignService->notificationMail($request_signature_key, $message);

            if(!empty($resultClickSign['errors'])){
                return $this->error($resultClickSign['errors'], HttpStatus::SERVER_ERROR);    
            }else{
                return $this->success('O E-mail foi enviado com sucesso.', HttpStatus::SUCCESS);
            }
        }else{
            return $this->error('A Mensagem não pode ser enviada.', HttpStatus::SERVER_ERROR);
        }
    }

    public function viewDocument($userId)
    {
        $resultClickSign = $this->clickSignService->viewDocument($userId);

        if(!empty($resultClickSign['errors'])){
            return $this->error($resultClickSign['errors'], HttpStatus::SERVER_ERROR);    
        }
    }
}
