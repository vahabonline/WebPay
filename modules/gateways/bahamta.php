<?php

/**
 * @edit by VAHABONLINE.IR
 */

function bahamta_MetaData()
{
    return array(
        'DisplayName' => 'وب پی',
        'APIVersion' => '1.2', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}


function bahamta_config()
{
    return array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'bahamta'
        ),
        'bahamta_api_key' => array(
            'FriendlyName' => 'کلید ارتباطی API',
            'Type' => 'text',
            'Description' => 'کلید ارتباطی که از سایت وب پی دریافت کرده اید را وارد کنید'
        ),
        'bahamta_sandbox' => array(
            'FriendlyName' => 'درگاه تست',
            'Type' => 'yesno',
            'Description' => 'اتصال به وبسرویس تست درگاه'
        )
    );
}

function bahamta_link($params){

    $paymentUrl = $params['systemurl'] . 'modules/gateways/link/bahamta.php';
    $callbackUrl = $params['systemurl'] . 'modules/gateways/callback/bahamta.php';
    $amount = round($params['amount']);
    
    if($params['currency'] == "IRT"){
        $amount = round($amount * 10);
    }

    $htmlForm = "
        <form method='post' action='{$paymentUrl}'>
        <input type='hidden' name='api_key' value='{$params['bahamta_api_key']}'> 
        <input type='hidden' name='reference' value='{$params['invoiceid']}'> 
        <input type='hidden' name='amount_irr' value='{$amount}'> 
        <input type='hidden' name='payer_mobile' value='{$params['clientdetails']['phonenumber']}'> 
        <input type='hidden' name='callback_url' value='{$callbackUrl}'> 
        <input type='hidden' name='sandbox_mode' value='{$params['bahamta_sandbox']}' />
        <input type='hidden' name='bahamta_create_request' value='yes' />
        <input type='submit' value='{$params['langpaynow']}'>
        </form> 
    ";
    
    return $htmlForm;
}
