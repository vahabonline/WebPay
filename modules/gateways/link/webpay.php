<?php
/**
 * @author Alireza Akhtari
 */

if (isset($_POST['webpay_create_request'])) {   

    if($_POST['sandbox_mode'] == 'on') {
        $url = 'https://testwebpay.bahamta.com/api/create_request?';
    } 
    else{
        $url = 'https://webpay.bahamta.com/api/create_request?';
    }

    $url .= http_build_query(array(
        'api_key' => $_POST['api_key'],
        'reference' => $_POST['reference'],
        'amount_irr' => $_POST['amount_irr'],
        'payer_mobile' => $_POST['payer_mobile'],
        'payer_mobile' => $_POST['payer_mobile'],
        'callback_url' => $_POST['callback_url'],
    ));
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($result);

    if($result->ok == true){
        // redirect
        header("Location: " . $result->result->payment_url);
    } else{
        die("Error: " . $result->error);
    }
}
