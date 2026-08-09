<?php

namespace app\components;

use yii\base\Component;
use yii\base\Exception;

class PDFServiceClient extends Component 
{
    public $baseUrl;
    public $apiKey;
    public $defaultFormat = 'A4';

    public function fetchRemotePdf($targetUrl, $format = null)
    {
        $format = $format ?? $this->defaultFormat;
        
        $context = stream_context_create([
            'http' => [
                'header' => [
                    "Authorization: Bearer {$this->apiKey}",
                    "X-Page-Format: {$format}",
                    "X-Target-URL: {$targetUrl}"
                ],
                'timeout' => 30.0,
                'ignore_errors' => true,
            ]
        ]);

        $result = @file_get_contents($this->baseUrl, false, $context);
        
        if ($result === false) {
            throw new Exception("PDF Service connection failed.");
        }

        return $result;
    }
}
