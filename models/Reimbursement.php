<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reimbursements".
 *
 * @property int $id
 * @property int $project_id
 * @property string $wf_status
 * @property float $requested_amount
 * @property string|null $request_description
 * @property float $reimbursed_amount
 * @property string $reimbursement_description
 * @property int $created_at
 * @property int $updated_at
 */
class Reimbursement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reimbursements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'wf_status', 'requested_amount', 'reimbursed_amount', 'reimbursement_description', 'created_at', 'updated_at'], 'required'],
            [['project_id', 'created_at', 'updated_at'], 'integer'],
            [['requested_amount', 'reimbursed_amount'], 'number'],
            [['wf_status'], 'string', 'max' => 40],
            [['request_description', 'reimbursement_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'wf_status' => Yii::t('app', 'Wf Status'),
            'requested_amount' => Yii::t('app', 'Requested Amount'),
            'request_description' => Yii::t('app', 'Request Description'),
            'reimbursed_amount' => Yii::t('app', 'Reimbursed Amount'),
            'reimbursement_description' => Yii::t('app', 'Reimbursement Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ReimbursementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReimbursementQuery(get_called_class());
    }
}
