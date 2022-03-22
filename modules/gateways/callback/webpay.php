<?php

/**
 * @author Alireza Akhtari
 * @edit by VahabOnline.ir
 */

use WHMCS\Database\Capsule;

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

// Detect module name from filename.
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Die if module is not active.
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

// is reference set 
if(isset($_REQUEST['reference'])){

    // invoice
    $invoice = Capsule::table('tblinvoices')->where('id', $_REQUEST['reference'])->where('status', 'Unpaid')->first();

    // invocie page
    $invoicePage = $gatewayParams['systemurl'] . 'viewinvoice.php?id=' .  $_REQUEST['reference'];

    // success 
    if(isset($_REQUEST['state']) && $_REQUEST['state'] == 'wait_for_confirm'){
        
        if($gatewayParams['webpay_sandbox'] == 'on'){
            $url = 'https://testwebpay.bahamta.com/api/confirm_payment?';
        } else{
            $url = 'https://webpay.bahamta.com/api/confirm_payment?';
        }

        $url .= http_build_query(array(
            'api_key' => $gatewayParams['webpay_api_key'],
            'reference' => $_REQUEST['reference'],
            'amount_irr' => round($invoice->total)
        ));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);

        // success
        if($result->ok == ture){
            if($result->result->state == "paid"){
				$amount = $invoice->total;
				if($gatewayParams['currency'] == "IRT"){
					$amount = round($amount / 10);
				}
                addInvoicePayment(
                    $invoice->id,
                    $invoice->id,
                    round($amount),
                    0,
                    $gatewayModuleName
                );

                header("Location: " . $invoicePage);
            }
        } else{
            header("Location: " . $invoicePage);
        }

    }
    else{
        if(isset($_REQUEST['error_message'])){
            header("Location: " . $invoicePage);
        }
    }
}
else{
    die("Webpay gateway!");
}