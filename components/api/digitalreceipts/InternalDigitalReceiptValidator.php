<?php

namespace app\components\api\digitalreceipts;

use Yii;
use app\models\DigitalReceipt;
use app\models\DigitalReceiptLine;
use app\components\api\digitalreceipts\DigitalReceiptValidatorInterface;

/**
 * InternalDigitalReceiptValidator.
 */
class InternalDigitalReceiptValidator implements DigitalReceiptValidatorInterface
{
    
    public static function issueReceipt(DigitalReceipt $receipt)
    {
        
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
        
        $data['total_amount'] = 0;
        
        foreach($receipt->digitalReceiptLines as $line){
            $lineValues=[
              "quantity"=>(int)$line->quantity,
              "description"=>$line->description,
              "notes"=>$line->notes,
              "unit_price"=>(float)$line->unit_price,
              "discount"=>(float)$line->discount,
              "complimentary"=>false,
              "sku"=>$line->sku,
              "vat_rate_code"=>$line->vat_rate_code,
            ];
            
            $data['total_amount']+=round(((int)$line->quantity*((float)$line->unit_price-(float)$line->discount)),2);
            
            $data['items'][] = $lineValues;
        }

        $id = uniqid('', true);

        $data['id']=$id;
        $data['type']='internal';
        
        $payload=json_encode([
            'data' => $data,
            'success' => true,
            'message' => '',
            'error' => null,
            ]
        );
        
        $receipt->assigned_id = $id;
        $receipt->api_request = JSON_encode([]);
        $receipt->api_response = $payload;
        //$receipt->sendToStatus('issued');
        
        $nextNumber = DigitalReceipt::findNextNumber($receipt);
        $receipt->sequential_number = $nextNumber;
        $receipt->document_number = $nextNumber . '/I';
        return $receipt->save(false);
    }

    public static function voidReceipt(DigitalReceipt $receipt)
    {
        if (time()-$receipt->created_at>24*60*60){
            Yii::debug('Too late. Voiding impossible. '.$receipt->id);
            return false;
        }

        $info = Yii::$app->params['receipts'];

        $id = uniqid('', true);

        $data['id']=$id;
        $data['type']='internal';
        $data['create_timestamp']=time();
        
        $r=[
            'data' => $data,
            'success' => true,
            'message' => 'voided receipt:'.$receipt->assigned_id,
            'error' => null,
            ];
            
        $result=json_encode($r);
        
        try {
            Yii::$app->db->transaction(function($db) use ($receipt, $r, $result) {

                $voidingReceipt = $receipt->cloneModel();
                $voidingReceipt->created_at = $r['data']['create_timestamp'];
                $voidingReceipt->assigned_id = $r['data']['id'];
                $voidingReceipt->voided_receipt_assigned_id = $receipt->assigned_id;
                
                $nextNumber = DigitalReceipt::findNextNumber($receipt);
                $voidingReceipt->sequential_number = $nextNumber;
                $voidingReceipt->document_number = $nextNumber . '/I';
                
                $voidingReceipt->api_response = $result;
                //$voidingReceipt->wf_status = 'DigitalReceiptWorkflow/issued';
                $receipt->voiding_receipt_assigned_id = $voidingReceipt->assigned_id;
                $receipt->voided_at = time();
                //$receipt->sendToStatus('issued');
                //$receipt->wf_status = 'DigitalReceiptWorkflow/voided';
                Yii::debug($voidingReceipt);
                Yii::debug($receipt);
                $voidingReceipt->save(false);
                $receipt->save(false);
            });
            return true;
        }
        catch (Exception $e) {
            Yii::debug("Something went wrong while saving...");
            return false;
        }
            
        return false;
        
    }
    
    public static function fetchPdf($receipt)
    {
        return false;
    }
    
    public static function fetchItems(DigitalReceipt $receipt)
    {
        return [];
    }

}
