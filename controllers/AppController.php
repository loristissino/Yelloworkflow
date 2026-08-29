<?php
namespace app\controllers;

use yii\web\Controller;

class AppController extends Controller
{
    public $layout = 'app'; // PWA has a specific layout

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionManifest()
    {
        $this->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'name' => \Yii::$app->params['pwa']['name'],
            'short_name' => \Yii::$app->params['pwa']['short_name'],
            'start_url' => '/app/index',
            'id' => '/app/index',
            'display' => 'standalone',
            'theme_color' => '#000000',
            'background_color' => '#ffffff',
            'icons' => [
                [
                    'src' => '/images/app-icon-192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png'
                ],
                [
                    'src' => '/images/app-icon-512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png'
                ]
            ],
            'screenshots' => [
                [
                    'src' => '/images/app-screenshot.png',
                    'sizes' => '540x720',
                    'type' => 'image/png',
                ],
            ]
        ];
    }

    public function actionServiceWorker()
    {
        $this->layout = false;
        $this->response->format = \Yii\web\Response::FORMAT_RAW;
        $this->response->headers->set('Content-Type', 'application/javascript; charset=utf-8');
        $this->response->headers->add('Service-Worker-Allowed', '/');
        return $this->renderFile('@app/views/app/service-worker.js');
    }

    public function actionJs()
    {
        $this->layout = false;
        $this->response->format = \Yii\web\Response::FORMAT_RAW;
        $this->response->headers->add('Content-Type', 'application/javascript; charset=utf-8');
        return $this->renderFile('@app/views/app/app.js');
    }
}   
