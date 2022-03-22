<?php

/**
 * @author Alireza Akhtari
 * @edit by VahabOnline.ir
 */

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function webpay_MetaData()
{
    return array(
        'DisplayName' => 'وب پی',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}
/**
 * Define gateway configuration options.
 * @return array
 */
function webpay_config()
{
    return array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Webpay'
        ),
        'webpay_api_key' => array(
            'FriendlyName' => 'Api Key',
            'Type' => 'text',
            'Description' => 'Enter your api key'
        ),
        'webpay_sandbox' => array(
            'FriendlyName' => 'Test Gateway',
            'Type' => 'yesno',
            'Description' => 'Tick checkbox for connect to sandbox'
        )
    );
}

/**
 * Payment link.
 *
 * @param array $param Payment Gateway Module Parameters
 * 
 * @see https://developers.whmcs.com/payment-gateways/third-party-gateway/
 * 
 * @return string
 */
function webpay_link($params){

    // request to create payment
    $paymentUrl = $params['systemurl'] . 'modules/gateways/link/webpay.php';
    $callbackUrl = $params['systemurl'] . 'modules/gateways/callback/webpay.php';
    $amount = round($params['amount']);
    
    if($params['currency'] == "IRT"){
        $amount = round($amount * 10);
    }

    // create html form
    $htmlForm = "
        <form method='post' action='{$paymentUrl}'>
        <input type='hidden' name='api_key' value='{$params['webpay_api_key']}'> 
        <input type='hidden' name='reference' value='{$params['invoiceid']}'> 
        <input type='hidden' name='amount_irr' value='{$amount}'> 
        <input type='hidden' name='payer_mobile' value='{$params['clientdetails']['phonenumber']}'> 
        <input type='hidden' name='callback_url' value='{$callbackUrl}'> 
        <input type='hidden' name='sandbox_mode' value='{$params['webpay_sandbox']}' />
        <input type='hidden' name='webpay_create_request' value='yes' />
        <input type='submit' value='{$params['langpaynow']}'>
        </form> 
    ";
    
    return $htmlForm;
}