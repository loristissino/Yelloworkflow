<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "periodical_report_comments".
 *
 * @property int $id
 * @property int $periodical_report_id
 * @property int $user_id
 * @property string $comment
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PeriodicalReport $periodicalReport
 * @property User $user
 */
class PeriodicalReportComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodical_report_comments';
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
            [['periodical_report_id', 'user_id', 'comment'], 'required'],
            [['periodical_report_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['periodical_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodicalReport::className(), 'targetAttribute' => ['periodical_report_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'periodical_report_id' => Yii::t('app', 'Periodical Report'),
            'user_id' => Yii::t('app', 'User'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PeriodicalReport]].
     *
     * @return \yii\db\ActiveQuery|PeriodicalReportQuery
     */
    public function getPeriodicalReport()
    {
        return $this->hasOne(PeriodicalReport::className(), ['id' => 'periodical_report_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function setPeriodicalReport(PeriodicalReport $periodicalReport)
    {
        $this->periodical_report_id = $periodicalReport->id;
        return $this;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getIsUpdateable() {
        return $this->user_id == Yii::$app->user->identity->id and $this->created_at >= $this->periodicalReport->lastLoggedActivityTime;
    }

    public function beforeSave($insert)
    {
        if (!$insert && $this->created_at < $this->getPeriodicalReport()->one()->lastLoggedActivityTime) {
            $this->addError('*', 'It is not possible to edit comments created before the last workflow event.');
            return false;
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     * @return PeriodicalReportCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PeriodicalReportCommentQuery(get_called_class());
    }
}
