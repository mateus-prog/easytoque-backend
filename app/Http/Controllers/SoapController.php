<?php
namespace App\Http\Controllers;

use SoapClient;

//use Artisaninweb\SoapWrapper\SoapWrapper;

class SoapController extends Controller
{
    /*protected $soapWrapper;
    public function __construct(
        SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
    }*/
    public function index()
    {

        phpinfo();
        exit;
        /*$response = $this->soapWrapper->add('Currency', function ($service) {
            $service
                ->wsdl('https://loja.easytoque.com.br/api/soap/?wsdl')                 // The WSDL url
                ->trace(true)            // Optional: (parameter: true/false)
                //->header()               // Optional: (parameters: $namespace,$name,$data,$mustunderstand,$actor)
                //->customHeader()         // Optional: (parameters: $customerHeader) Use this to add a custom SoapHeader or extended class                
                //->cookie()               // Optional: (parameters: $name,$value)
                //->location()             // Optional: (parameter: $location)
                //->certificate()          // Optional: (parameter: $certLocation)
                //->cache(WSDL_CACHE_NONE) // Optional: Set the WSDL cache
            
                // Optional: Set some extra options
                ->options([
                    'login' => 'revendedor',
                    'password' => 'mortadela15'
                ]);
        });

        //$filter = array('status'=>array('in'=>['complete','processing','pending_payment']), 'store_id'=>array('eq'=>250));
        //$orders_magento = $response->call('sales_order.list', array($filter));

        return $response;*/

        // Add a new service to the wrapper
        /*$this->soapWrapper->add(function ($service) {
            $service->name('MDS')->wsdl('https://loja.easytoque.com.br/api/soap/?wsdl')->trace(true);
        });
        //$data = ['app_name' => 'Shopify', 'app_version' => '1.0', 'app_host' => 'shopify.dev', 'app_url' => 'shopify.dev'];
        // Using the added service
        $this->soapWrapper->service('MDS', function ($service) use($data) {
            var_dump($service->getFunctions());
            var_dump($service->call('authenticate', ['revendedor', 'mortadela15']));
            //var_dump($service->call('get_location_types', $data)->get_location_typesResult);
        });*/

        /*$client = $this->soapWrapper->add('https://loja.easytoque.com.br/api/soap/?wsdl');
        $result = $client->authenticate('revendedor', 'mortadela15');
        //$result = $client->__soapCall('authenticate', ['revendedor', 'mortadela15']);
        var_dump($result);*/
        $proxy = new SoapClient('https://loja.easytoque.com.br/api/soap/?wsdl');

        $session = $proxy->login('revendedor', 'mortadela15'); // connect to the API
                    
        $filter = array('status'=>array('in'=>['complete','processing','pending_payment']), 'store_id'=>array('eq'=>$_SESSION['auth']['loja_id']));
        $orders_magento = $proxy->call($session, 'sales_order.list', array($filter));
        dd($orders_magento);
    }
}
