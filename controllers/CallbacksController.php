<?php

namespace app\controllers;

use Yii;
use app\models\DigitalReceipt;

class CallbacksController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        // Disable CSRF validation
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }    
    
    public function actionReceipt()
    {
        return $this->callback('receipt');
    }

    public function actionReceiptError()
    {
        return $this->callback('receipt-error');
    }
    
    public function actionReceiptRetry()
    {
        return $this->callback('receipt-retry');
    }

    public function actionReceiptCredentials()
    {
        return $this->callback('receipt-credentials');
    }
    
    public function actionSms()
    {
        return $this->saveContents('SMS');
    }
    
    private function saveContents($type)
    {
        try {
            $payload = Yii::$app->request->getRawBody();
            $r = json_decode($payload, true);

            $filename = sprintf('callback_%s_%s.json', $type, time());
            file_put_contents($filename, $payload);
        }
        catch (\Exception $e) {
            // Log the error
            Yii::error($e->getMessage());
            // Return 401 on error
            Yii::$app->response->statusCode = 401;
        }
        // Always return empty response
        return '';
    }

    private function callback($type)
    {
        try {
            // Validate the webhook signature
            if (!$this->validateWebhookSignature()) {
                throw new \yii\web\BadRequestHttpException('Invalid signature');
            }
            
            // Get and process the payload
            $payload = Yii::$app->request->getRawBody();
            $r = json_decode($payload, true);

            $processed = false;
            if (isset($r['data']) && isset($r['data']['id'])){
                $documentId = $r['data']['id'];
                $model = DigitalReceipt::find()->withAssignedId($documentId)->one();
                if ($model) {
                    if ($model->processCallback($payload)){
                        $processed = true;
                    }
                }
            };
            
            if (!$processed) {
                $filename = sprintf('unprocessed_callback_%s_%s.json', $type, time());
                file_put_contents($filename, $payload);
            }
        }
        catch (\Exception $e) {
            // Log the error
            Yii::error($e->getMessage());
            // Return 401 on error
            Yii::$app->response->statusCode = 401;
        }
        
        // Always return empty response
        return '';
    }

    private function validateWebhookSignature()
    {
        // Get the Authorization header
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        
        if (!$authHeader) {
            return false;
        }
        
        // Extract the token from "Bearer XYZ" format
        if (preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            return false;
        }

        // Yii::debug('token: ' .$token);
        if (!$token) {
            return false;
        }
        
        $expectedToken = Yii::$app->params['receipts']['api']['callback_bearer_auth_token'];
        return hash_equals($expectedToken, $token);
    }

}


