<?php

namespace app\components\api\digitalreceipts;

use Yii;
use app\models\DigitalReceipt;
use app\models\DigitalReceiptLine;
use app\components\api\digitalreceipts\DigitalReceiptValidatorInterface;

/**
 * OpenApiDigitalReceiptValidator.
 */
class OpenApiDigitalReceiptValidator implements DigitalReceiptValidatorInterface
{
    private static $urls=[
        'dev' =>'https://test.invoice.openapi.com',
        'prod'=>'https://invoice.openapi.com',
    ];
    
    public static function issueReceipt(DigitalReceipt $receipt)
    {
        $url = self::$urls[$receipt->digitalReceiptType->environment];
        $info = Yii::$app->params['receipts'];
        
        $data = [
            "fiscal_id"=>$info['issuer']['fiscal_id'],
            "items"=> [],
            "cash_payment_amount"=> (float)$receipt->cash_payment_amount,
            "electronic_payment_amount"=> (float)$receipt->electronic_payment_amount,
            "ticket_restaurant_payment_amount"=> 0,
            "ticket_restaurant_quantity"=> 0,
            "goods_uncollected_amount"=> 0,
            "services_uncollected_amount"=> 0,
            "invoice_issuing"=> false,
            //"linked_receipt"=> false,
            "discount"=> 0,
            //"lottery_code"=> false,
            "tags"=> [
                //"experiment"
            ]
        ];

        foreach($receipt->digitalReceiptLines as $line){
            $lineValues=[
              "quantity"=>(int)$line->quantity,
              "description"=>$line->description,
              "unit_price"=>(float)$line->unit_price,
              "discount"=>(float)$line->discount,
              "complimentary"=>false,
              "sku"=>$line->sku,
              "vat_rate_code"=>$line->vat_rate_code,
            ];
            
            $data['items'][] = $lineValues;
        }
        
        $payload=json_encode($data);
        Yii::debug($payload);
        //print_r($payload);
        //die();

        $result = false;
        
        $result = @file_get_contents(
            $url . '/IT-receipts',
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"POST",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/json\r\n" .
                              "Content-Length: " . strlen($payload) . "\r\n" .
                              "Authorization: Bearer " . $info['api'][$receipt->digitalReceiptType->environment]['token'] . "\r\n",
                    'content'=>$payload,
                ]
            ])
        );
        
        if ($result===false) {
            $receipt->assigned_id = "NO VALUE";
            $receipt->api_response = JSON_encode([
                'success' => false,
                'message' => 'API server unreachable'
            ]);
            $receipt->save();
            return false;
        }

        $r=json_decode($result, true);

        $receipt->api_request = $payload;
        
        if($r['success']=='true'){
            $receipt->assigned_id = $r['data']['id'];
            $receipt->document_number = $r['data']['document_number'];
            $receipt->api_response = $result;
            //$receipt->sendToStatus('issued');
            $nextNumber = DigitalReceipt::findNextNumber($receipt);
            $receipt->sequential_number = $nextNumber;
            return $receipt->save();
            
        }
        else {
            $receipt->assigned_id = "NO VALUE";
            $receipt->api_response = $result;
            $receipt->save();
            return false;
        }
        
        return false;
    }

    public static function voidReceipt(DigitalReceipt $receipt)
    {
        $url = self::$urls[$receipt->digitalReceiptType->environment];
        $info = Yii::$app->params['receipts'];
        $result = false;
        $result = @file_get_contents(
            $url . '/IT-receipts/' . $receipt->assigned_id,
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"DELETE",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/json\r\n" .
                              "Authorization: Bearer " . $info['api'][$receipt->digitalReceiptType->environment]['token'] . "\r\n",
                ]
            ])
        );
        
        if ($result===false) {
            Yii::debug('NO RESULT');
            return false;
        }
        
        Yii::debug($result);
        $r=json_decode($result, true);
        
        if($r['success']=='true'){
            try {
                Yii::$app->db->transaction(function($db) use ($receipt, $r, $result) {

                    $voidingReceipt = $receipt->cloneModel();
                    $voidingReceipt->created_at = $r['data']['create_timestamp'];
                    $voidingReceipt->assigned_id = $r['data']['id'];
                    $voidingReceipt->document_number = $r['data']['document_number'];
                    $voidingReceipt->voided_receipt_assigned_id = $receipt->assigned_id;
                    
                    $nextNumber = DigitalReceipt::findNextNumber($receipt);
                    $voidingReceipt->sequential_number = $nextNumber;
            
                    $voidingReceipt->api_response = $result;
                    //$voidingReceipt->wf_status = 'DigitalReceiptWorkflow/issued';
                    $receipt->voiding_receipt_assigned_id = $voidingReceipt->assigned_id;
                    $receipt->voided_at = time();
                    //$receipt->sendToStatus('issued');
                    //$receipt->wf_status = 'DigitalReceiptWorkflow/voided';
                    Yii::debug($voidingReceipt);
                    Yii::debug($receipt);
                    $voidingReceipt->save(false);
                    foreach($receipt->digitalReceiptLines as $drl) {
                        $clonedDrl = $drl->cloneModel();
                        $clonedDrl->line_type = 'VOIDING';
                        $clonedDrl->digital_receipt_id = $voidingReceipt->id;
                        $clonedDrl->save(false);
                    }
                    $receipt->save(false);
                });
                return true;
            }
            catch (Exception $e) {
                Yii::debug("Something went wrong while saving...");
                return false;
            }
        }
        else {
                Yii::debug("Tried to void " . $receipt->assigned_id . "\nThe response was " . $result);
                return false;
            }
            
        return false;
 
    }

    // Fetches the PDF from the external service    
    public static function fetchPdf(DigitalReceipt $receipt)
    {
        $url = self::$urls[$receipt->digitalReceiptType->environment];
        $info = Yii::$app->params['receipts'];

        $result = false;
        $result = @file_get_contents(
            $url . '/IT-receipts/' . $receipt->assigned_id,
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"GET",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/pdf\r\n" .
                              "Authorization: Bearer " . $info['api'][$receipt->digitalReceiptType->environment]['token'] . "\r\n",
                ]
            ])
        );
        
        if ($result===false) {
            Yii::debug('Could not fetch the PDF');
            return false;
        }

        // Save to file
        $filePath = Yii::getAlias('@runtime') . "/receipt-{$receipt->client_id}.pdf";
        file_put_contents($filePath, $result);
        
        \Yii::$app->getModule('attachments')->attachFile($filePath, $receipt);
        
        return true;
    }

    public static function fetchItems(DigitalReceipt $receipt)
    {
        $url = self::$urls[$receipt->digitalReceiptType->environment];
        $info = Yii::$app->params['receipts'];

        $result = false;
        $result = @file_get_contents(
            $url . '/IT-receipts/' . $receipt->assigned_id,
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"GET",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/json\r\n" .
                              "Authorization: Bearer " . $info['api'][$receipt->digitalReceiptType->environment]['token'] . "\r\n",
                ]
            ])
        );
        
        if ($result===false) {
            Yii::debug('Could not fetch the items');
            return false;
        }
        
        $data = JSON_decode($result, true);
        return $data['data']['items'];
    }

    public static function processReturn(DigitalReceipt $receipt, $items, $reason)
    {
        $url = self::$urls[$receipt->digitalReceiptType->environment];
        $info = Yii::$app->params['receipts'];
        
        $data = ['items' => []];
        
        foreach($items as $key=>$value){
            if ($value>0){
                $data['items'][] = [
                    'id'=>(string)$key,
                    'quantity'=>(int)$value,
                ];
            }
        };
        
        $payload=json_encode($data);
        
        Yii::debug($payload);
        
        $result = false;
        $result = @file_get_contents(
            $url . '/IT-receipts/' . $receipt->assigned_id,
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"PATCH",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/json\r\n" .
                              "Content-Length: " . strlen($payload) . "\r\n" .
                              "Authorization: Bearer " . $info['api'][$receipt->digitalReceiptType->environment]['token'] . "\r\n",
                    'content'=>$payload,
                ]
            ])
        );
        
        if ($result===false) {
            Yii::debug('NO RESULT');
            return false;
        }
        
        Yii::debug($result);
        $r=json_decode($result, true);
        $r['reason']=$reason;
        
        $r['returned_items']=$data['items'];
                
        if($r['success']=='true'){
            $returnReceiptId = -1;
            try {
                Yii::$app->db->transaction(function($db) use ($receipt, $r, $result, &$returnReceiptId, $payload) {

                    $returnReceipt = $receipt->cloneModel();
                    $returnReceipt->created_at = $r['data']['create_timestamp'];
                    $returnReceipt->assigned_id = $r['data']['id'];
                    $returnReceipt->document_number = $r['data']['document_number'];
                    
                    $nextNumber = DigitalReceipt::findNextNumber($receipt);
                    $returnReceipt->sequential_number = $nextNumber;
                    $returnReceipt->parent_id = $receipt->id;
                    $returnReceipt->api_request = $payload;
                    $returnReceipt->api_response = $result;

                    $returnReceipt->save(false);
                    $returnReceiptId = $returnReceipt->id;
                    
                    $receipt->adjustQuantities($r['returned_items'], $returnReceipt, $r['reason']);
                    
                    Yii::debug('returning the receipt ' . $returnReceipt->id);
                });
                return $returnReceiptId;
            }
            catch (Exception $e) {
                Yii::debug("Something went wrong while saving...");
                return false;
            }
        }
        else {
                Yii::debug("Tried to record a return for " . $receipt->assigned_id . "\nThe request was " . $payload . "\nThe response was " . $result);
                return false;
            }
            
        return false;
         
    }

    public static function getConfiguration($digitalReceiptType) {
        $url = self::$urls[$digitalReceiptType->environment];
        $info = Yii::$app->params['receipts'];

        $result = false;
        $result = @file_get_contents(
            $url . '/IT-configurations/' . $info['issuer']['fiscal_id'],
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"GET",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/json\r\n" .
                              "Authorization: Bearer " . $info['api'][$digitalReceiptType->environment]['token'] . "\r\n",
                ]
            ])
        );
        
        if ($result===false) {
            Yii::debug('Could not fetch the configuration');
            return false;
        }
        
        return $result;
    }

    public static function setConfiguration($digitalReceiptType, $taxCode, $password, $pin) {
        $url = self::$urls[$digitalReceiptType->environment];
        $info = Yii::$app->params['receipts'];

        $data = [
          'receipts_authentication' => [
             'taxCode' => $taxCode,
             'password' => $password,
             'pin' => $pin,
          ],
        ];
        
        $payload=json_encode($data);

        $result = false;
        $result = @file_get_contents(
            $url . '/IT-configurations/' . $info['issuer']['fiscal_id'],
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"PATCH",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/json\r\n" .
                              "Content-Length: " . strlen($payload) . "\r\n" .
                              "Authorization: Bearer " . $info['api'][$digitalReceiptType->environment]['token'] . "\r\n",
                    'content'=>$payload,
                ]
            ])
        );
        
        if ($result===false) {
            Yii::debug('Could not patch the configuration');
            return false;
        }
        
        return $result;
    }

}
