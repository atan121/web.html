<?php
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Origin: https://for4.us');
header('Content-type: application/json');
session_start();
require('../config.php');
if($_SESSION['session'] == $_GET['session']){
    
    $url = 'https://api.cloudflare.com/client/v4/user/tokens/verify'.$zone_id.'/dns_records';

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
    // ganti pake api key domain kalian yang di clouflare 
       "Authorization: bearer I6cozrZiv5DwqZjQxhK6Vl0Q430XCmIQ5nizvHSD",
       "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
    $data = array(
        'type' => 'A',
        'name' => $_GET['domain'],
        'content' => $_GET['ip'],
        'proxied' => true
        );
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    
    $c = json_decode($resp,true);
    if($c['success']){
        $p = array(
            'success' => true,
            'details' => array(
                'domain' => $_GET['domain'],
                'ip_address' => $_GET['ip']
            )
        );
    }else{
        $p = array(
            'success' => false
        );
    }
    print_r(json_encode($p, JSON_PRETTY_PRINT));
    session_destroy();
}else{
    $p = array(
            'success' => false,
            'reason' => 'Invalid Session Key'
        );
    print_r(json_encode($p, JSON_PRETTY_PRINT));
    
}

?>

