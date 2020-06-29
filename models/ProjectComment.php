<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project_comments".
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $comment
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Project $project
 * @property User $user
 */
class ProjectComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_comments';
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
            [['project_id', 'user_id', 'comment'], 'required'],
            [['project_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
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
            'project_id' => Yii::t('app', 'Project'),
            'user_id' => Yii::t('app', 'User'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Project]].
     *
     * @return \yii\db\ActiveQuery|ProjectQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
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

    public function setProject(Project $project)
    {
        $this->project_id = $project->id;
        return $this;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getIsUpdateable() {
        return $this->user_id == Yii::$app->user->identity->id and $this->created_at >= $this->project->lastLoggedActivityTime;
    }

    public function beforeSave($insert)
    {
        if (!$insert && $this->created_at < $this->getProject()->one()->lastLoggedActivityTime) {
            $this->addError('*', 'It is not possible to edit comments created before the last workflow event.');
            return false;
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     * @return ProjectCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectCommentQuery(get_called_class());
    }
}
