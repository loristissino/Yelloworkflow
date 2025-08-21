<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class Shortener extends Model
{
    public $keyword;
    public $url;
    public $title;
    public $shorturl;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url', 'title', 'shorturl'], 'string'],
            [['keyword'], 'string', 'max'=>100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'url' => Yii::t('app', 'URL'),
            'title' => Yii::t('app', 'Titolo'),
            'keyword' => Yii::t('app', 'Keyword'),
            'shorturl' => Yii::t('app', 'Short URL'),
        ];
    }

    public function save() {
        $params = ['action'=>'shorturl', 'url'=>$this->url, 'title'=>$this->title];
        if ($this->keyword) {
            $params['keyword']=$this->keyword;
        }
        $data = self::_call($params);
        if ($data && !$data->errorCode) {
            $this->keyword = $data->url->keyword;
            return true;
        }
        return false;
    }

    private static function _call($options=[]) {
        
        // see https://go.uaar.it/readme.html#API

        $options = array_merge($options, [
            'format'   => 'json',
            'signature' => Yii::$app->params['shortener']['signature']
        ]);
        
        $payload = http_build_query($options);
        
        $data = file_get_contents(
            Yii::$app->params['shortener']['api_url'],
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"POST",
                    'header'=>"Content-Type: application/x-www-form-urlencoded\r\n" .
                              "Content-Length: " . strlen($payload) . "\r\n",
                    'content'=>$payload,
                ]
            ])
        );

        return $data ? json_decode( $data ) : false;
    }

    public static function findOne($keyword)
    {
        $data = self::_call(['action'=>'expand', 'shorturl'=>$keyword]);
        if ($data->statusCode==200) {
            $model = new Shortener();
            $model->url = $data->longurl;
            $model->title = $data->title;
            $model->keyword = $keyword;
            $model->shorturl = $data->shorturl;
            return $model;
        }
        return null;
    }
    
    public static function findLatest($number=20, $perpage=10)
    {
        $data = self::_call(['action'=>'stats', 'filter'=>'last', 'limit'=>$number]);
        $items = [];
        foreach((array)$data->links as $key=>$value) {
            $items[] = [
                'shorturl'=>$value->shorturl,
                'title'=>$value->title,
                'url'=>$value->url,
                'timestamp'=>$value->timestamp,
            ];
        }
        return new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => $perpage,
            ],
            'sort' => [
                'attributes' => ['title', 'url', 'timestamp', 'shorturl'],
            ],            
        ]);
    }
}
