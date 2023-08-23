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
        if (!$data->errorCode) {
            $this->keyword = $data->url->keyword;
            return true;
        }
        return false;
    }

    private static function _call($options=[]) {
        
        // see https://go.tissino.it/readme.html#API
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Yii::$app->params['shortener']['api_url']);
        curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
        curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
        
        $options = array_merge($options, [
            'format'   => 'json',
            'signature' => Yii::$app->params['shortener']['signature']
        ]);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $options);

        // Fetch and return content
        $data = curl_exec($ch);
        curl_close($ch);

//        print_r(json_decode( $data ));
//        die();
        return json_decode( $data );
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
