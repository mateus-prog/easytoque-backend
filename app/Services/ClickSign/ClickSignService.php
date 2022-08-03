<?php

namespace App\Services\ClickSign;

use Illuminate\Support\Facades\Http;
use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\UserCorporateRepository;
use App\Repositories\Elouquent\UserStoreRepository;
use App\Repositories\Elouquent\UserClickSignRepository;
use App\Repositories\Elouquent\StateRepository;
use App\Repositories\Elouquent\RolesRepository;
use App\Repositories\Elouquent\LogRepository;
use App\Helpers\Format;
use App\Helpers\Log;
use Exception;

class ClickSignService
{
    protected $_token;
    protected $_environment; 
    protected $_keyTemplateDocument;

    public function __construct()
    {
        //$this->_token = 'ba466851-5de6-4fa0-9d5a-6871f57167d9';
        //$this->_environment = 'https://app.clicksign.com'; 
        //$this->_keyTemplateDocument = '7d037718-f24c-4202-bfa0-e5adc58ae553';
        $this->_token = 'b8519293-a6df-4c92-b68b-711f836e1a49';
        $this->_environment = 'https://sandbox.clicksign.com';
        $this->_keyTemplateDocument = 'ebdfb8ca-16d4-4076-8dbc-557ae3de1cce';

        $this->userRepository = new UserRepository();
        $this->userCorporateRepository = new UserCorporateRepository();
        $this->userStoreRepository = new UserStoreRepository();
        $this->userClickSignRepository = new UserClickSignRepository();
        $this->stateRepository = new StateRepository();
        $this->logRepository = new LogRepository();
        $this->rolesRepository = new RolesRepository();
    }

    /**
     * Display the specified resource.
     *
     * @param number $user_id
     * @return \Illuminate\Http\Response
     */
    public function findByUserClickSign($user_id)
    {
        return $this->userClickSignRepository->findByFieldWhereReturnArray('user_id', '=', $user_id, 'id, signatario_key, document_key, request_signature_key');
    }

    public function store(array $request)
    {
        try { 
            return $this->userClickSignRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update($id, array $request)
    {
        try { 
            return $this->userClickSignRepository->update($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    //criar signatario
	public function createSigner($userId) 
    {
        $user = $this->userRepository->findById($userId);
        $userClickSign = $this->findByUserClickSign($userId);

        $idUserLog = $userId;

        $role = $this->rolesRepository->findById($user->role_id);

        if(empty($userClickSign))
        {

            $name = trim($user->first_name).' '.trim($user->last_name);
            $email = trim($user->email);

            //integração com a clicksign
            $host = $this->_environment.'/api/v1/signers?access_token='.$this->_token;
        
            $postFields = [
                'signer' => [
                    'email' => $email,
                    'auths' => ['email'],
                    'name' => $name,
                    'has_documentation' => false,
                    'delivery' => 'email',
                    'handwritten_enabled' => true
                ]
            ];

            //resposta da clicksign
            $resultClickSign = Http::post($host, $postFields)->json();
            
            if(!empty($resultClickSign['errors']))
            {
                $messageLog = 'Signatário na ClickSign';
                $actionId = 1;
                $idUserLog = $user->id;

                $log = Log::createLog($idUserLog, $messageLog, $actionId, false);
                $this->logRepository->store($log);

                return $resultClickSign['errors'];    
            }
            else if(!empty($resultClickSign['signer']['key']))
            {
                $signatario_key = $resultClickSign['signer']['key'];   

                //insert no banco de dados
                $this->store(['user_id' => $userId, 'signatario_key' => $signatario_key]);
                
                $this->createDocumentTemplate($userId);
            }
        }
        else
        {
            $this->createDocumentTemplate($userId);
        }

        $messageLog = 'Signatário na ClickSign';
        $actionId = 1;
        $idUserLog = $user->id;

        $log = Log::createLog($idUserLog, $messageLog, $actionId, true);
        $this->logRepository->store($log);
	}

    //visualizar signatario
	public function viewSigner($signatario_key) 
    {
        $host = $this->_environment.'/api/v1/signers/'.$signatario_key.'?access_token='.$this->_token;

        return Http::get($host)->json();
	}

    //adicionar signatario em documento
    public function createSignerDocument($userId) 
    {
        $userClickSign = $this->findByUserClickSign($userId);
        $userClickSign = $userClickSign[0];
        $request_signature_key = $userClickSign['request_signature_key'];
        
        $idUserLog = $userId;

        $user = $this->userRepository->findById($userId);
        $role = $this->rolesRepository->findById($user->role_id);

        $message = 'Por favor, assine o documento digitalmente para concluir seu cadastro como parceiro Easytoque';

        if($request_signature_key == '')
        {
            $signatario_key = $userClickSign['signatario_key'];
            $document_key = $userClickSign['document_key'];
            
            $host = $this->_environment.'/api/v1/lists?access_token='.$this->_token;
        
            $postFields = [
                'list' => [
                    'document_key' => $document_key,
                    'signer_key' => $signatario_key,
                    'sign_as' => 'sign'
                ]
            ];

            //resposta da clicksign
            $resultClickSign = Http::post($host, $postFields)->json();
            
            if(!empty($resultClickSign['errors']))
            {
                $messageLog = 'Vinculo entre Signatário e o Contrato na ClickSign';
                $actionId = 1;
                $idUserLog = $user->id;
        
                $log = Log::createLog($idUserLog, $messageLog, $actionId, false);
                $this->logRepository->store($log);

                return $resultClickSign['errors'];    
            }
            else if(!empty($resultClickSign['list']['request_signature_key']))
            { 
                $request_signature_key = $resultClickSign['list']['request_signature_key'];   
                $id = $userClickSign['id'];

                //update
                $this->update($id, ['request_signature_key' => $request_signature_key]);
                
                //enviar whatsapp
                $this->notificationMail($request_signature_key, $message);
            }
        }
        else
        {
            //enviar whatsapp
            $this->notificationMail($request_signature_key, $message);
        }

        $messageLog = 'Vinculo entre Signatário e o Contrato na ClickSign';

        $actionId = 1;
        $idUserLog = $user->id;

        $log = Log::createLog($idUserLog, $messageLog, $actionId, true);
        $this->logRepository->store($log);
	}

    //remover signatario em documento
    public function deleteSignerDocument($list_key) 
    {
        $host = $this->_environment.'/api/v1/lists/'.$list_key.'?access_token='.$this->_token;

        return Http::delete($host)->json();
	}

    //deletar signatario
	public function deleteSigner($signer_key) 
    {
        $host = $this->_environment.'/api/v2/signers/'.$signer_key.'?access_token='.$this->_token;

        return Http::delete($host)->json();
	}

    //criar documento via modelos
	public function createDocumentTemplate($userId) 
    {
        $user = $this->userRepository->findById($userId);
        $userClickSign = $this->findByUserClickSign($userId);
        $document_key = $userClickSign[0]['document_key'];
        
        $idUserLog = $userId;

        $user = $this->userRepository->findById($userId);
        $role = $this->rolesRepository->findById($user->role_id);

        if($document_key == '')
        {
        
            $name = $user->first_name . ' ' . $user->last_name;
            $cpf = Format::cpf($user->cpf);

            $userCorporate = $this->userCorporateRepository->findByFieldWhereReturnObject('user_id', '=', $userId);
            $userCorporate = $userCorporate[0];

            $state = $this->stateRepository->findById($userCorporate->state_id);
            $userCorporate->state_id = $state->name;
            
            $store = $this->userStoreRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'commission');
            $commission = Format::valueBR($store[0]['commission']);

            $corporate_name = $userCorporate->corporate_name;
            $cnpj = $userCorporate->cnpj;
            $address = $userCorporate->address;
            $number = $userCorporate->number;
            $complement = $userCorporate->complement;
            $district = $userCorporate->district;
            $city = $userCorporate->city;
            $cep = $userCorporate->cep;
            $state = $userCorporate->state_id;

            $path = '/parceiros/parceiro-'.Format::slugify($corporate_name).'-cnpj-'.Format::slugify($cnpj).'.docx';

            $address .= ', ' . $number;

            $address .= $complement != '' ? ' Compl. ' . $complement : '';

            $address .= ' - ' . $district . ' - ' . $city . ' - CEP ' . $cep . ' - ' . $state;

            $host = $this->_environment.'/api/v1/templates/'.$this->_keyTemplateDocument.'/documents?access_token='.$this->_token;

            $postFields = [
                'document' => [
                    'path' => $path,
                    'template' => [
                        'data'=>[
                            'razao_social' => $corporate_name,
                            'cnpj' => $cnpj,
                            'endereco' => $address, 
                            'representante' => $name,
                            'cpf' => $cpf,
                            'comissao' => $commission.'%'
                        ]
                    ]
                ]
            ];

            //resposta da clicksign
            $resultClickSign = Http::post($host, $postFields)->json();
            //update no banco de dados
            if(!empty($resultClickSign['errors'])){

                $messageLog = 'Contrato na ClickSign';

                $actionId = 1;
                $idUserLog = $user->id;

                $log = Log::createLog($idUserLog, $messageLog, $actionId, false);
                $this->logRepository->store($log);

                return $resultClickSign['errors'];    
            }else if(!empty($resultClickSign['document']['key']))
            { 
                $document_key = $resultClickSign['document']['key'];   
                $id = $userClickSign[0]['id'];

                $this->update($id, ['document_key' => $document_key]);

                $this->createSignerDocument($userId);
            }
        }
        else
        {
            $this->createSignerDocument($userId);
        }
        
        $messageLog = 'Contrato na ClickSign';
        $actionId = 1;
        $idUserLog = $user->id;

        $log = Log::createLog($idUserLog, $messageLog, $actionId, true);
        $this->logRepository->store($log);
	}

    public function viewDocument($userId)
    {
        $userClickSign = $this->findByUserClickSign($userId);
        $idUserLog = $userId;
        if(!empty($userClickSign))
        {
            $document_key = $userClickSign[0]['document_key'];
        
            if($document_key != '')
            {
                $host = $this->_environment.'/api/v1/documents/'.$document_key.'?access_token='.$this->_token;
                
                $resultClickSign = Http::get($host)->json();
                //update no banco de dados
                if(!empty($resultClickSign['errors'])){
                    $messageLog = $resultClickSign['errors']; 
                    $actionId = 1;
                    
                    $log = Log::createLog($idUserLog, $messageLog, $actionId, false);
                    $this->logRepository->store($log);
                }else{
                    if($resultClickSign['document']['status'] == 'closed'){
                        $this->userRepository->update($userId, ['status_user_id' => '1']);
                    }else{
                        $messageLog = "Documento ainda não assinado ou cancelado na ClickSign";
                        $actionId = 1;
                        
                        $log = Log::createLog($idUserLog, $messageLog, $actionId, false);
                        $this->logRepository->store($log);
                    }
                }
            }else{
                $messageLog = "Documento não encontrado na ClickSign";
                $actionId = 1;
                        
                $log = Log::createLog($idUserLog, $messageLog, $actionId, false);
                $this->logRepository->store($log);
            }
        }
    }

    //visualizar documento
	public function showDocument($request_signature_key) 
    {
        return $this->_environment.'/sign/'.$request_signature_key;
	}

    //deletar documento
	public function deleteDocument() 
    {
        $host = $this->_environment.'/api/v1/documents/'.$this->_keyTemplateDocument.'?access_token='.$this->_token;

        return Http::delete($host)->json();
	}

    //lote de envio
    public function batchSend($signer_key, $documents_keys, $summary = true)
    {
        $host = $this->_environment.'/api/v1/batches?access_token='.$this->_token;

        $postFields = [
            'batch' => [
                'signer_key' => $signer_key,
                'documents_keys' => [
                    $documents_keys
                ],
                'summary' => $summary
            ]
        ];

        return Http::post($host, $postFields)->json();
    }

    //enviar notificação por email
	public function notificationMail($request_signature_key, $message) 
    {
        $host = $this->_environment.'/api/v1/notifications?access_token='.$this->_token;

        $postFields = [
            'request_signature_key' => $request_signature_key,
		    'message' => $message
        ];

        return Http::post($host, $postFields)->json();
	}

    //enviar notificação por whatsapp
	public function notificationWhatsapp($request_signature_key) 
    {
        $host = $this->_environment.'/api/v1/notify_by_whatsapp?access_token='.$this->_token;

        $postFields = [
            'request_signature_key' => $request_signature_key
        ];

        return Http::post($host, $postFields)->json();
	}

    //enviar notificação por sms
	public function notificationSms($request_signature_key) 
    {
        $host = $this->_environment.'/api/v1/notify_by_sms?access_token='.$this->_token;

        $postFields = [
            'request_signature_key' => $request_signature_key
        ];

        return Http::post($host, $postFields)->json();
	}
}