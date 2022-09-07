<?php
namespace App\Services\Store;

use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\UserStoreRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Exception;

use GuzzleHttp\Client;

class StoreService
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userStoreRepository = new UserStoreRepository();
        $this->client = new Client();
    }

    /**
     * cria loja para o usuario no magento
     * @param $id int //user id
     * @return array
    */
    public function createStoreMagento(int $id, int $clientId)
    {
        $user = $this->userRepository->findById($id);
        $urlStore = 'https://loja.easytoque.com.br/createStore.php';

        $storeName = $user->first_name . ' ' . $user->last_name;

        $response = Http::asForm()->post($urlStore, [
            'store_name' => $storeName,
            'rev_id' => $clientId,
        ]);
        
        return $response;
    }

    /**
     * acessa o pagamento da loja no magento
     */
    public function storeMagento(string $type){
        $userId = Auth::user()->id;

        $store = $this->userStoreRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'commission, store_id');
        $commission = $store[0]['commission'];
        $storeId = $store[0]['store_id'];
        
        $urlStore = 'http://painel.easytoque.com.br/vendas_api.php?storeId='.$storeId.'&type='.$type;

        $response = Http::get($urlStore)->json();

        if($type == 'list')
        {
            foreach($response as $key => $value){
                
                if(isset($value['commission']))
                { 
                    $value['commission'] = $value['commission'] * ($commission / 100);
                    $value['commission'] = number_format($value['commission'], 2, ',', '.');
                }
                
                if(isset($value['sales']))
                { 
                    $value['sales'] = number_format($value['sales'], 2, ',', '.');
                }

                $response[$key] = $value;
            }
        }

        if($type == 'sum')
        {
            if(isset($response['salesApproved']))
            {   
                $response['salesApproved'] = $response['salesApproved'] * ($commission / 100);
                $response['salesApproved'] = number_format($response['salesApproved'], 2, ',', '.');
            }
        }

        return $response;
    }

}