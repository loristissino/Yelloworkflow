<?php

namespace app\models;

use Yii;
use yii\web\Link; // represents a link object as defined in JSON Hypermedia API Language.
use yii\web\Linkable;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use app\models\OrganizationalUnit;
use app\models\DigitalReceiptType;

/**
 * This is the model class for the REST resource associated to Product.
 */
class DigitalReceiptResource extends DigitalReceipt implements Linkable
{
	// https://www.yiiframework.com/doc/guide/2.0/en/rest-resources
	public function fields()
	{
 		$fields = parent::fields();
        
        unset ($fields['created_at']);
        unset ($fields['updated_at']);
		return $fields;
	}

	public function extraFields()
	{
        // call the API with /api/v1/digital-receipts?expand=digitalReceiptLines to make it work
		return  ['digitalReceiptLines'];
	}
    
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['digital-receipts/view', 'id' => $this->id], true),
        ];
    }
    /* old code
    public static function create($data)
    {
        $ou = OrganizationalUnit::findOne($data['organizational_unit_id']);
        if (!$ou) {
            throw new BadRequestHttpException('Not a valid Organizational Unit provided');
        }
        $drt = DigitalReceiptType::findOne($data['digital_receipt_type_id']);
        if (!$drt) {
            throw new BadRequestHttpException('Not a valid Digital Receipt Type');
        }
        
        $dr = new DigitalReceipt();
        foreach ([
            'client_id',
            'total_amount',
            'cash_payment_amount',
            'electronic_payment_amount',
            'email',
            'phone',
        ] as $key) {
            $dr->$key = $data[$key];
        };
        
        $dr->setDefaults($drt, $ou);
        
        $dr->saveWithLines(['DigitalReceiptLine'=>$data['lines']]);
        $dr->sendToStatus('issued');
        $dr->save(false);
        
        // file_put_contents('datareceived.txt', json_encode(['data'=> $data, 'ou' => $organizationalUnit]));
        
        return $dr;
    }
    */
    
    public static function create($data)
    {
        $ou = OrganizationalUnit::findOne($data['organizational_unit_id']);
        if (!$ou) {
            throw new BadRequestHttpException(
                'Not a valid Organizational Unit provided'
            );
        }

        $drt = DigitalReceiptType::findOne($data['digital_receipt_type_id']);
        if (!$drt) {
            throw new BadRequestHttpException(
                'Not a valid Digital Receipt Type'
            );
        }

        $dr = new DigitalReceipt();

        foreach ([
            'client_id',
            'total_amount',
            'cash_payment_amount',
            'electronic_payment_amount',
            'email',
            'phone',
        ] as $key) {
            $dr->$key = $data[$key];
        }

        $dr->setDefaults($drt, $ou);

        try {
            if (!$dr->saveWithLines([
                'DigitalReceiptLine' => $data['lines']
            ])) {

                if ($dr->hasErrors('client_id')) {
                    return self::findExisting($data);
                }

                throw new BadRequestHttpException(
                    'Unable to save digital receipt'
                );
            }
                        
        } catch (\yii\db\IntegrityException $e) {
            // This could happen in the rare case when a duplicate is inserted
            // just between the check by Yii 'unique' constraint (as per the model's rules)
            // is performed before the insertion and the actual insertion 
            if (str_contains($e->getMessage(), 'unique_client_id')) {
                if ($existing = self::findExisting($data))
                    return $existing;
            }
            throw $e;
        }

        $dr->sendToStatus('issued');
        $dr->save(false);

        return [
            'receipt' => $dr,
            'created' => true,
        ];
    }
    
    private static function findExisting($data)
    {
        $dr = self::find()
            ->where(['client_id' => $data['client_id']])
            ->one();
        if ($dr === null) {
            throw new \RuntimeException(
                'Receipt reported as duplicate, but could not be found.'
            );
        }
        return [
            'receipt' => $dr,
            'created' => false,
        ];
    }
    
}
