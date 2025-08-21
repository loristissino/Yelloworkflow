<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;
use app\models\QuestionnaireQuery;

/**
 * This is the model class for table "questionnaires".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $version
 * @property string $wf_status
 * @property string|null $definition
 * @property int $created_at
 * @property int $updated_at
 *
 * @property QuestionnaireResponse[] $questionnaireResponses
 */
class Questionnaire extends \yii\db\ActiveRecord
{
    use WorkflowTrait;
    
    public $google_form_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questionnaires';
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
            [['title'], 'required'],
            [['description', 'definition'], 'string'],
            [['version', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['wf_status'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'version' => Yii::t('app', 'Version'),
            'wf_status' => Yii::t('app', 'Workflow Status'),
            'definition' => Yii::t('app', 'Definition'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[QuestionnaireResponses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireResponses()
    {
        return $this->hasMany(QuestionnaireResponse::className(), ['questionnaire_id' => 'id']);
    }
    
    public function __toString()
    {
        return $this->title;
    }
    
    private function _runWorkflowChecks($event)
    {
        switch ($event->getEndStatus()->getId()) {
            case 'QuestionnaireWorkflow/published':
                break;
            case 'QuestionnaireWorkflow/archived':
                break;
        }
    }

    private function _runWorkflowRoutines($event)
    {
        $log = "Running workflow routines...\n";
        
        $log .= $event->getTransition()->getId() . "\n";
        
        $options = [];
        
        if ($event->getEndStatus()->getId() == 'QuestionnaireWorkflow/published') {
            // Yii::$app->session->setFlash('info', Yii::t('app', 'The project must be submitted again after the edits.'));
        }
        
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);
        
    }

    /**
     * {@inheritdoc}
     * @return QuestionnaireQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new QuestionnaireQuery(get_called_class());
    }
    
}
