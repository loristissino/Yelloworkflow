<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;
use app\models\QuestionnaireResponseQuery;

/**
 * This is the model class for table "questionnaire_responses".
 *
 * @property int $id
 * @property int $user_id
 * @property int $questionnaire_id
 * @property string $label
 * @property string $wf_status
 * @property string|null $content
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Questionnaire $questionnaire
 */
class QuestionnaireResponse extends \yii\db\ActiveRecord
{
    use WorkflowTrait;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questionnaire_responses';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'workflowBehavior' => [
                'class'                    => SimpleWorkflowBehavior::className(),
                'statusAttribute'          => 'wf_status',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'questionnaire_id'], 'required'],
            [['user_id', 'questionnaire_id', 'created_at', 'updated_at'], 'integer'],
            [['content', 'label'], 'string'],
            [['wf_status'], 'string', 'max' => 40],
            [['label'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['questionnaire_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questionnaire::className(), 'targetAttribute' => ['questionnaire_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'questionnaire_id' => Yii::t('app', 'Questionnaire ID'),
            'questionnaire' => Yii::t('app', 'Questionnaire'),
            'label' => Yii::t('app', 'Label'),
            'wf_status' => Yii::t('app', 'Workflow Status'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function hasWfStatus($status)
    {
        return $this->getWorkflowStatus()->getId() == 'QuestionnaireResponseWorkflow/' . $status;
    }
    
    public function getIsDraft()
    {
        return $this->hasWfStatus('draft');
    }

    private function _runWorkflowChecks($event)
    {
        switch ($event->getEndStatus()->getId()) {
            case 'QuestionnaireResponseWorkflow/submitted':
                break;
            case 'QuestionnaireResponseWorkflow/archived':
                break;
        }
    }

    private function _runWorkflowRoutines($event)
    {
        $log = "Running workflow routines...\n";
        
        $log .= $event->getTransition()->getId() . "\n";
        
        $options = [];
        
        if ($event->getEndStatus()->getId() == 'QuestionnaireResponseWorkflow/submitted') {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Thank you for submitting your response.'));
        }
        
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);
        
    }



    /**
     * Gets query for [[Questionnaire]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::className(), ['id' => 'questionnaire_id']);
    }
    
    public function getViewLink($options=[])
    {
        return $this->getViewLinkCode('label', 'questionnaire-responses', $options);
    }
    
     /**
     * {@inheritdoc}
     * @return QuestionnaireQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new QuestionnaireResponseQuery(get_called_class());
    }
    
}
