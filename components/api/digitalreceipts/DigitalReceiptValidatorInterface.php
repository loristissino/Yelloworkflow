<?php

namespace app\components\api\digitalreceipts;

use Yii;
use app\models\DigitalReceipt;
use app\models\DigitalReceiptLine;

interface DigitalReceiptValidatorInterface
{
    public static function issueReceipt(DigitalReceipt $receipt);

    public static function voidReceipt(DigitalReceipt $receipt);

    public static function fetchPdf(DigitalReceipt $receipt);

    public static function fetchItems(DigitalReceipt $receipt);

}
