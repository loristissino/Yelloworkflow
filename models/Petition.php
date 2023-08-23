<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "petitions".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $target
 * @property string|null $introduction
 * @property string|null $picture_url
 * @property string|null $request
 * @property string|null $updates
 * @property string|null $promoted_by
 * @property string $wf_status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $launched_at
 * @property int|null $expired_at
 * @property int $goal
 *
 * @property PetitionSignature[] $petitionSignatures
 */
class Petition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'petitions';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'target', 'wf_status', 'goal'], 'required'],
            [['target', 'introduction', 'request', 'updates', 'promoted_by'], 'string'],
            [['created_at', 'updated_at', 'launched_at', 'expired_at', 'goal'], 'integer'],
            [['slug'], 'string', 'max' => 32],
            [['title', 'picture_url'], 'string', 'max' => 256],
            [['wf_status'], 'string', 'max' => 40],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'slug' => Yii::t('app', 'Slug'),
            'title' => Yii::t('app', 'Title'),
            'target' => Yii::t('app', 'Target'),
            'introduction' => Yii::t('app', 'Introduction'),
            'picture_url' => Yii::t('app', 'Picture Url'),
            'request' => Yii::t('app', 'Request'),
            'updates' => Yii::t('app', 'Updates'),
            'promoted_by' => Yii::t('app', 'Promossa da'),
            'wf_status' => Yii::t('app', 'Wf Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'launched_at' => Yii::t('app', 'Launched At'),
            'expired_at' => Yii::t('app', 'Expired At'),
            'goal' => Yii::t('app', 'Goal'),
        ];
    }

    /**
     * Gets query for [[PetitionSignatures]].
     *
     * @return \yii\db\ActiveQuery|PetitionSignatureQuery
     */
    public function getPetitionSignatures()
    {
        return $this->hasMany(PetitionSignature::className(), ['petition_id' => 'id']);
    }
    
    public function getNumberOfConfirmedSignatures()
    {
        return (int)$this->getPetitionSignatures()->confirmed()->count();
    }

    /**
     * {@inheritdoc}
     * @return PetitionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PetitionQuery(get_called_class());
    }
    
    public static function getActivePetitions()
    {
        return self::find()->active()->all();
    }
    
}
