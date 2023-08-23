<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "attachments".
 *
 * @property int $id
 * @property string $name
 * @property string $model
 * @property int $itemId
 * @property string $hash
 * @property int $size
 * @property string $type
 * @property string $mime
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'model', 'itemId', 'hash', 'size', 'type', 'mime'], 'required'],
            [['itemId', 'size'], 'integer'],
            [['name', 'model', 'hash', 'type', 'mime'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'model' => Yii::t('app', 'Model'),
            'itemId' => Yii::t('app', 'Item ID'),
            'hash' => Yii::t('app', 'Hash'),
            'size' => Yii::t('app', 'Size'),
            'type' => Yii::t('app', 'Type'),
            'mime' => Yii::t('app', 'Mime'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AttachmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttachmentQuery(get_called_class());
    }
    
}
