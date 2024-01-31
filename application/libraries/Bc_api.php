<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bc_api
{
    private $CI;

    function __construct()
    {
        $this->CI = get_instance();
    }

    function getSingleCustomerAddress($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.bigcommerce.com/stores/ilhtqzrn07/v2/customers/".$data['customer_id']."/addresses/".$data['address_id'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/json",
            "x-auth-client: ".$data['client_secret'],
            "x-auth-token: ".$data['apitoken']
          ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $err = json_decode($err); 
            return $err;
        } else {
            $product_hook_res = json_decode($response); 
            return $product_hook_res;
        }
    }
}
?>