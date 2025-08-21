<?php

namespace app\models\sm;

use Yii;
use yii\base\Model;
use yii\validators\DateValidator;
use yii\web\UploadedFile;


/**
 * MastodonPostForm is the model behind the issues form.
 */
class MastodonPostForm extends Model
{
    public $status;
    public $at;
    public $image;
    public $description;
    
    public $mastodon;

    public function __construct($mastodon)
    {
        $this->mastodon = $mastodon;
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['status', 'at'], 'required'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'gif, jpeg, jpg, png'],
            [['status', 'description'], 'string', 'max'=>500],
            [['at'], 'date', 'min'=>time()],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'status' => Yii::t('app', 'Status'),
            'at' => Yii::t('app', 'Scheduled At'),
            'image' => Yii::t('app', 'Image'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
    
    public function schedule()
    {
        $this->image = UploadedFile::getInstance($this, 'image');
        $d = new \DateTime($this->at);
//        $d->setTimezone(new \DateTimeZone('Europe/Rome'));
        $at = $d->format('U');
        return $this->mastodon->schedule($at, $this->status, $this->image, $this->description);
    }

}
