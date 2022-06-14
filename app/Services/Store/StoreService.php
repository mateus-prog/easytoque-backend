<?php
namespace App\Services\Store;

use App\Repositories\Elouquent\UserRepository;
use Illuminate\Support\Facades\Http;
use Exception;

use GuzzleHttp\Client;

class StoreService
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->client = new Client();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function storeMagento(int $id)
    {
        $user = $this->userRepository->findById($id);
        $urlStore = 'https://loja.easytoque.com.br/createStore.php';

        $storeName = $user->first_name . ' ' . $user->last_name;

        $id = 204;

        $response = Http::asForm()->post($urlStore, [
            'store_name' => $storeName,
            'rev_id' => $id,
        ]);
        
        return $response;
    }

}