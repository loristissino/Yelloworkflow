<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "transaction_templates".
 *
 * @property int $id
 * @property int|null $organizational_unit_id
 * @property int $status
 * @property int $rank
 * @property string $title
 * @property string $description
 * @property string $o_title // title from the main office's point of view
 * @property string $o_description // description from the main office's point of view
 * @property int $needs_attachment
 * @property int $needs_project
 * @property int $needs_vendor
 *
 * @property TransactionTemplatePosting[] $transactionTemplatePostings
 * @property OrganizationalUnit $organizationalUnit
 */
class TransactionTemplate extends \yii\db\ActiveRecord
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organizational_unit_id', 'status', 'rank'], 'integer'],
            [['status', 'rank', 'title', 'description', 'needs_attachment', 'needs_project', 'needs_vendor'], 'required'],
            [['title', 'o_title'], 'string', 'max' => 60],
            [['description', 'o_description'], 'string', 'max' => 255],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'status' => Yii::t('app', 'Is Active?'),
            'rank' => Yii::t('app', 'Rank'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'o_title' => Yii::t('app', 'Title from the Office\'s Point of View'),
            'o_description' => Yii::t('app', 'Description from the Office\'s Point of View'),
            'needs_attachment' => Yii::t('app', 'Needs attachment?'),
            'needs_project' => Yii::t('app', 'Project?'),
            'needs_vendor' => Yii::t('app', 'Needs vendor?'),
        ];
    }

    /**
     * Gets query for [[TransactionTemplatePostings]].
     *
     * @return \yii\db\ActiveQuery|TransactionTemplatePostingQuery
     */
    public function getTransactionTemplatePostings()
    {
        return $this->hasMany(TransactionTemplatePosting::className(), ['transaction_template_id' => 'id']);
    }

    /**
     * Gets query for [[OrganizationalUnit]].
     *
     * @return \yii\db\ActiveQuery|OrganizationalUnitQuery
     */
    public function getOrganizationalUnit()
    {
        return $this->hasOne(OrganizationalUnit::className(), ['id' => 'organizational_unit_id']);
    }

    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['id' => 'account_id'])->viaTable('transaction_template_postings', ['account_id' => 'id']);
    }

    public function __toString()
    {
        return $this->title;
    }
    
    public function getInfoView()
    {
        $html = Html::tag('span', $this->title, ['style'=>'font-weight: bold']) . '<br />';
        if ($this->o_title) {
            $html .= Html::tag('span', $this->o_title, ['style'=>'font-weight: bold; font-style: italic']) . '<br />';
        }
        
        foreach ($this->getTransactionTemplatePostings()->all() as $posting) {
            $html .= sprintf('%s %s<br />', 
                $posting->iconDC,
                $posting->account
            );
        }
        return $html;
    }

    public static function getActiveTransactionTemplatesAsArray()
    {
        return self::find()
            ->active()
            ->select(['id', 'title', 'description', 'needs_attachment', 'needs_project', 'needs_vendor'])
            ->indexBy('id')
            ->orderBy(['rank' => SORT_ASC, 'title' => SORT_ASC])
            ->asArray()
            ->all()
             ;
    }

    public static function getActiveOfficeTransactionTemplatesAsArray()
    {
        return self::find()
            ->active()
            ->officeOnly()
            ->select(['id', 'o_title AS title', 'o_description AS description', 'needs_attachment', 'needs_project', 'needs_vendor'])
            ->indexBy('id')
            ->orderBy(['rank' => SORT_ASC, 'title' => SORT_ASC])
            ->asArray()
            ->all()
             ;
    }


    public function getNeedsAttachmentView()
    {
        return $this->getBooleanRepresentation($this->needs_attachment);
    }

    public function getNeedsProjectView()
    {
        return $this->getTernarianRepresentation($this->needs_project);
    }

    public function getNeedsVendorView()
    {
        return $this->getBooleanRepresentation($this->needs_vendor);
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $array = null;
        if (!isset($options['array'])) {
            $options['array'] = self::getActiveTransactionTemplatesAsArray();
        }
        $options = array_merge([
            'field_name' => 'transaction_template_id',
            'prompt' => Yii::t('app', 'Choose the template'),
            'array' => $array,
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(\yii\helpers\ArrayHelper::map($options['array'], 'id', 'title'), $options)
            ;
    }

    /**
     * {@inheritdoc}
     * @return TransactionTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionTemplateQuery(get_called_class());
    }
}
