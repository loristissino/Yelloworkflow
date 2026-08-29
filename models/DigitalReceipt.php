<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;
use app\models\DigitalReceiptLine;
use app\models\Attachment;
use yii\db\Query;
use yii\db\Expression;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "digital_receipts".
 *
 * @property int $id
 * @property string $date
 * @property string $wf_status
 * @property int $user_id
 * @property int|null $organizational_unit_id
 * @property int $digital_receipt_type_id
 * @property string|null $tags
 * @property float $total_amount
 * @property string|null $email
 * @property string|null $phone
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $sent_at
 * @property int|null $processed_at
 * @property int|null $voided_at
 * @property int|null $expo_id
 * @property int|null $transaction_id
 * @property string $client_id
 * @property string|null $assigned_id
 * @property string|null $voiding_receipt_assigned_id
 * @property string|null $voided_receipt_assigned_id 
 * @property string|null $document_number
 * @property int|null $parent_id 
 * @property float|null $cash_payment_amount
 * @property float|null $electronic_payment_amount
 * @property string|null $api_request
 * @property string|null $api_response
 * @property string|null $api_callback
 * @property string|null $issuer_data 
 * @property string|null $notes 
 * @property int|null $receipt_year 
 *
 * @property DigitalReceiptLine[] $digitalReceiptLines
 * @property DigitalReceiptType $digitalReceiptType
 * @property OrganizationalUnit $organizationalUnit
 * @property Expo $expo
 * @property User $user
 * @property Transaction $transaction
 * @property DigitalReceipt $parent 
 * @property DigitalReceipt[] $digitalReceipts 
 */
 
class DigitalReceipt extends \yii\db\ActiveRecord
{
    
    use WorkflowTrait;
    use ModelTrait;
    
    public $file;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'digital_receipts';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'workflowBehavior' => $this->getDefaultWorkflowBehavior(),
			'fileBehavior' => [
				'class' => \nemmo\attachments\behaviors\FileBehavior::className()
			],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'user_id', 'digital_receipt_type_id', 'client_id'], 'required'],
            [['date', 'tags', 'api_request', 'api_response', 'api_callback', 'issuer_data', 'notes'], 'safe'],
            [['user_id', 'organizational_unit_id', 'digital_receipt_type_id', 'created_at', 'updated_at', 'sent_at', 'processed_at', 'voided_at', 'transaction_id', 'parent_id', 'sequential_number', 'receipt_year'], 'integer'],
            [['total_amount', 'cash_payment_amount', 'electronic_payment_amount'], 'number'],
            [['client_id'], 'string'],
            [['wf_status', 'assigned_id', 'voiding_receipt_assigned_id', 'voided_receipt_assigned_id', 'document_number'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['phone'], 'validatePhoneNumber'],
            [['email', 'phone'], 'validateOnlyOneField'],
            [['client_id'], 'unique'],
            [['digital_receipt_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DigitalReceiptType::className(), 'targetAttribute' => ['digital_receipt_type_id' => 'id']],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
            [['expo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expo::className(), 'targetAttribute' => ['expo_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::className(), 'targetAttribute' => ['transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'wf_status' => Yii::t('app', 'Workflow Status'),
            'user_id' => Yii::t('app', 'User'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'digital_receipt_type_id' => Yii::t('app', 'Digital Receipt Type'),
            'tags' => Yii::t('app', 'Tags'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'sent_at' => Yii::t('app', 'Sent At'),
            'processed_at' => Yii::t('app', 'Processed At'),
            'voided_at' => Yii::t('app', 'Voided At'),
            'expo_id' => Yii::t('app', 'Expo'),
            'transaction_id' => Yii::t('app', 'Transaction'),
            'client_id' => Yii::t('app', 'Client ID'),
            'assigned_id' => Yii::t('app', 'Assigned ID'),
            'sequential_number' => Yii::t('app', 'Sequential Number'),
            'voiding_receipt_assigned_id' => Yii::t('app', 'Voiding Receipt Assigned ID'),
            'document_number' => Yii::t('app', 'Document Number'),
            'parent_id' => Yii::t('app', 'Parent'),
            'cash_payment_amount' => Yii::t('app', 'Cash Payment Amount'),
            'electronic_payment_amount' => Yii::t('app', 'Electronic Payment Amount'),
            'api_request' => Yii::t('app', 'Api Request'),
            'api_response' => Yii::t('app', 'Api Response'),
            'api_callback' => Yii::t('app', 'Api Callback'),
            'issuer_data' => Yii::t('app', 'Issuer Data'),
            'notes' => Yii::t('app', 'Notes'),
            'receipt_year' => Yii::t('app', 'Receipt Year'), 
        ];
    }

    /**
     * Gets query for [[DigitalReceiptLines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalReceiptLines()
    {
        return $this->hasMany(DigitalReceiptLine::className(), ['digital_receipt_id' => 'id']);
    }

    /**
     * Gets query for [[DigitalReceiptType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalReceiptType()
    {
        return $this->hasOne(DigitalReceiptType::className(), ['id' => 'digital_receipt_type_id']);
    }

    /**
     * Gets query for [[OrganizationalUnit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationalUnit()
    {
        return $this->hasOne(OrganizationalUnit::className(), ['id' => 'organizational_unit_id']);
    }

    /**
     * Gets query for [[Expo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpo()
    {
        return $this->hasOne(Expo::className(), ['id' => 'expo_id']);
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

    /**
     * Gets query for [[Transaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id']);
    }
    
    public function getParentReceipt()
    {
        return $this->hasOne(DigitalReceipt::className(), ['id' => 'parent_id']);
    }
    
    public function getChildrenDigitalReceipts()
    {
        return $this->hasMany(DigitalReceipt::className(), ['parent_id' => 'id']);
    }

    public function getViewLink($options=[])
    {
        return Yii\helpers\Html::a($this->__toString(), ['digital-receipts/view', 'id'=>$this->id], $options);
    }

    public function __toString()
    {
        return $this->client_id;
    }
    
    public function getDocumentLabel()
    {
        $documentLabel = $this->digitalReceiptType->issued_text;
        if ($this->isReturnReceipt) {
            $documentLabel = $this->digitalReceiptType->return_text;
        } elseif ($this->voided_receipt_assigned_id != null) {
            $documentLabel = $this->digitalReceiptType->voiding_text;
        }
        return $documentLabel;
    }

    
    public function getFormattedTotalAmount()
    {
        $amount = $this->total_amount * ($this->isReturnReceipt ? -1 : 1);
        return  Yii::$app->formatter->asCurrency($amount);
    }
    
    public function setDefaults(DigitalReceiptType $drt, $organizationalUnit=null)
    {
        $this->organizational_unit_id = $organizationalUnit->id;
        $issuer = Yii::$app->params['receipts']['issuer'];
        if ($organizationalUnit) {
            $issuer['organizational_unit'] = $organizationalUnit->name;
        }
        $this->api_response = JSON_encode([]);
        $this->issuer_data = JSON_encode($issuer);
        $this->date = date('Y-m-d');
        $this->user_id = Yii::$app->user->identity->id;
        $this->digital_receipt_type_id = $drt->id;
        if (trim($this->email) == ''){
            $this->email = null;
        }
        if (trim($this->phone) == ''){
            $this->phone = null;
        }
        $expo = $this->organizationalUnit->currentExpo;
        if ($expo) {
            $this->expo_id = $expo->id;
        }
        return true;
    }
    
    public function saveWithLines($postData)
    {

        // 1. Start a Transaction
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // 2. Save the Master Record (DigitalReceipt) first
            // We call parent::save() to use the standard saving logic
            
            if (!$this->save()) {
                return false;
            }

            // 3. Process the Lines
            // We look for 'DigitalReceiptLine' in the POST data
            if (isset($postData['DigitalReceiptLine']) && is_array($postData['DigitalReceiptLine'])) {

                $linesData = $postData['DigitalReceiptLine'];
                                
                foreach ($linesData as $index => $lineData) {
                    // Create a new line instance
                    $line = new DigitalReceiptLine();
                    
                    // Mass assign attributes (safe attributes only)
                    $line->attributes = $lineData;
                    
                    // We copy the data to preserve history
                    $product = Product::findOne($lineData['product_id']);
                    if(!$product){
                        continue;
                    }
                    $line->description = $product->description;
                    
                    if ($product->isbn) {
                        $line->description = Yii::t('app', 'Book «{title}» (ISBN:&nbsp;{isbn})', [
                            'title'=>$product->description,
                            'isbn'=>$product->isbn]
                            );
                    }
            
                    $line->vat_rate_code = $product->vat_rate_code;
                    $line->sku = $product->sku;
                    $line->amount = round($line->unit_price * $line->quantity - $line->discount, 2);
                    // the discount is cumulative for all the items sold in the line
                    $line->notes = $line->notes;
                    
                    // Manually link the Foreign Key to the just-saved Receipt
                    $line->digital_receipt_id = $this->id; 

                    // Validate and Save the line
                    if (!$line->save()) {
                        // If any line fails, capture errors and rollback
                        $this->addError('digitalReceiptLines', "Error on line " . ($index) . ": " . implode(', ', $line->getFirstErrors()));
                        Yii::debug(implode(', ', $line->getFirstErrors()));
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            // 4. If we got here, everything is valid. Commit!
            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            // In case of a crash, rollback everything
            Yii::debug($this->getErrors());
            $transaction->rollBack();
            throw $e;
        }
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (parent::save($runValidation, $attributeNames)) {
                // afterSave() has already been called here
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    
    public function fixContacts()
    {
        if ($this->email == ''){
            $this->email = null;
        }
        if ($this->phone == ''){
            $this->phone = null;
        }
        if (!$this->email && !$this->phone) {
            $this->email = $this->organizationalUnit->email;
        }
        return true;
    }

    public function getLikelyContact(){
        if ($this->phone)
            return $this->phone;
        if ($this->email)
            return $this->email;
        return $this->organizationalUnit->email;
    }

    public function getUrl($qrcode=300)
    {
        return \yii\helpers\Url::to(['site/digital-receipt', 'id'=>$this->client_id, 'qrcode'=>$qrcode], true);
    }
    
    public function sendSMS()
    {
        $template = NotificationTemplate::find()->withCode('DigitalReceiptWorkflow/texted-to-payer')->one();
        
        $fields = [
            'date'=>Yii::$app->formatter->asDate($this->date),
            'totalAmount'=>Yii::$app->formatter->asCurrency($this->total_amount),
            'url'=>$this->url,
        ];
        
        $text = $template->replace($fields);
        
        return Yii::$app->smsservice->send($this->phone, $text);
                
    }
    
    public function send($code='')
    {
        if ($this->phone) {
            return $this->sendSMS();
        }
        
        if(!$code)
        {
            $code = 'DigitalReceiptWorkflow/sent-to-payer';
            if ($this->email == $this->organizationalUnit->email) {
                $code = 'DigitalReceiptWorkflow/sent-to-ou';
            }
        }        
        
        $template = NotificationTemplate::find()->withCode($code)->one();
        if (!$template) {
            return false;
        }

        $fields = [
            'date'=>Yii::$app->formatter->asDate($this->date),
            'totalAmount'=>Yii::$app->formatter->asCurrency($this->total_amount),
            'url'=>$this->url,
        ];

        $attachments = [
            [
                'name'=>$this->client_id . '.pdf',
                'content'=>base64_encode($this->getPdf()),
            ],
        ];

        if (Yii::$app->mailer
            ->compose()
            ->setTo($this->email)
            ->setSubject(Yii::t('app', $template->subject, $fields))
            ->setTextBody(Yii::t('app', $template->plaintext_body, $fields))
            ->setHtmlBody(
                \yii\helpers\Markdown::process(Yii::t('app', $template->md_body, $fields))
                /*. '<hr>'
                . $html*/
                )
            ->setAttachments($attachments)
            ->send()
        ) {
            return true;
        }
        else {
            return false;
        }
    }
    

    public function getHasLines() {
        return $this->getDigitalReceiptLines()->count()>0;
    }
    
    public function getErrorsOnItemsSavedOnDB() {
        $info = $this->getJsonField('api_response');
        if (!isset($info['data']) || !isset($info['data']['items'])) {
            return Yii::t('app', 'Nothing to compare with');
        }
        
        $items = $info['data']['items'];
        if (empty($items)) {
            return Yii::t('app', 'No items');
        }
        
        // Get keys from first item, excluding 'complimentary'
        $keys = array_keys($items[0]);
        $keys = array_diff($keys, ['complimentary']);
        
        // Get DB lines with same fields, sorted
        $lines = $this->getDigitalReceiptLines()
            ->select($keys)
            ->orderBy(['sku' => SORT_ASC, 'quantity' => SORT_ASC])
            ->asArray()
            ->all();
        
        // Sort JSON items the same way
        usort($items, function($a, $b) {
            $skuCmp = strcmp($a['sku'], $b['sku']);
            return $skuCmp !== 0 ? $skuCmp : $a['quantity'] <=> $b['quantity'];
        });
        
        // Compare
        return $this->compareArrays($items, $lines, $keys);
    }

    private function compareArrays($arr1, $arr2, $keys) {
        if (count($arr1) !== count($arr2)) {
            return Yii::t('app', 'Not the same number of lines');
        }
        
        foreach ($arr1 as $index => $item1) {
            $item2 = $arr2[$index];
            
            foreach ($keys as $key) {
                $a = $item1[$key];
                $b = $item2[$key];
                if (is_numeric($a) and is_numeric($b)){
                    $a=round((float)$a,2);
                    $b=round((float)$b,2);
                }
                if ($a !== $b) {
                    return Yii::t('app', 'Not matching lines') .': '. json_encode($item1) . ' <> ' . json_encode($item2);
                }
            }
        }
        return ''; // empty string means "no differences";
    }

    public function getIsVoidable()
    {
        return !$this->voided_at &&
            !$this->voiding_receipt_assigned_id && 
            !$this->voided_receipt_assigned_id && 
            !$this->parent_id &&
            (date('Y-m-d') === date('Y-m-d', $this->created_at)) &&
            $this->getWorkflowStatus()->getId()!='DigitalReceiptWorkflow/journalized'
            ;
    }
    
    public function getIsMarkableAsPosted()
    {
        // receipts can be marked as posted only by a person of the same organizational unit
        return $this->organizational_unit_id = $this->organizationalUnit->id;
    }

    public function getIsUpdatable() {
        return $this->getWorkflowStatus()->getId()=='DigitalReceiptWorkflow/issued';
    }
    
    public function getCanHaveReturns() {
        return
            (strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', $this->created_at)) <= 14*24*60*60) // within the last 14 days
            &&
            $this->voided_receipt_assigned_id == null  // it is not a voiding receipt
            &&
            $this->getWorkflowStatus()->getId()!='DigitalReceiptWorkflow/voided' // not voided
            &&
            !$this->parent_id // not a return receipt
            ;
    }

    public function getHasBeenProcessed() {
        return $this->processed_at != null;
    }
    
    public function getCanBeProcessed() {
        return
            $this->canHaveReturns
            &&
            $this->digitalReceiptType->validator != '\app\components\api\digitalreceipts\InternalDigitalReceiptValidator'
        ;
    }
    
    public function processCallback($payload)
    {
        try {
            Yii::$app->db->transaction(function($db) use ($payload) {

                $r = json_decode($payload, true);

                if(ArrayHelper::getValue($r, 'data.type') === 'sale') {
                    $this->processSale(ArrayHelper::getValue($r, 'data.items'));
                };
                
                $this->document_number = ArrayHelper::getValue($r, 'data.document_number');
                $this->api_callback = $payload;
                $this->processed_at = time();
                $this->save(false);
                
            return true;
            });
        }
        catch (Exception $e) {
            Yii::debug("Something went wrong while saving...");
            return false;
        }
    }

    public function getNeedsImmediateJournalizing() {
        return $this->getDigitalReceiptLines()->requiringSealing()->count() > 0;
    }
    
    public function getCanBeJournalizedSeparately() {
        if (!$this->transaction) {
            return false;
        }
        if (!$this->getWorkflowStatus()->getId()=='DigitalReceiptWorkflow/journalized'){
            return false;
        }
        if ($this->transaction->getDigitalReceipts()->count()<2) {
            return false;
        }
        return true;
    }
    
    public function journalizeIfNeeded() {
        if (!$this->NeedsImmediateJournalizing) {
            return false;
        }
        
        if (\Yii::$app instanceof \yii\console\Application) {
            // Running in console
            //return false;
        } 
        
        $e = $this->expo;
        if ($e && $e->periodical_report_id) {
            $pr = $e->periodicalReport;
        }
        else {
            $pr = $this->organizationalUnit->getPeriodicalReportByDate($this->date);
        }
        if (!$pr) {
            return false;
        }

        $tt = TransactionTemplate::find()->withRank(-1)->one();
        if (!$tt) {
            return false;
        }

        $dbTransaction = \Yii::$app->db->beginTransaction();
        try {
            $transaction = new Transaction();
            $transaction->detachBehavior('fileBehavior');
            $transaction->date = $this->date;
            $transaction->periodical_report_id = $pr->id;
            $transaction->transaction_template_id = $tt->id;
            $transaction->expo_id = $this->expo_id;
            $transaction->user_id = \Yii::$app instanceof \yii\console\Application ? -1 : Yii::$app->user->identity->id;
            $transaction->description = "a";
            $transaction->save(false);
            
            if (!$transaction) {
                $dbTransaction->rollBack();
                return false;
            } 
            
            $descriptions = [];
            $notes = [];

            // first the debit
            $posting = new Posting();
            $posting->transaction_id = $transaction->id;
            $posting->account_id = Yii::$app->params['receipts']['payment_accounts'][$this->getPaymentMethod(false)];
            $posting->amount = $this->total_amount;
            $posting->save();

            // ... then the credits
            foreach ($this->getDigitalReceiptLines()->all() as $dgrl) {
                $descriptions[$dgrl->description][] = 1;
                $notes[$dgrl->notes][] = 1;
                $posting = new Posting();
                $posting->transaction_id = $transaction->id;
                $posting->account_id = $dgrl->product->sales_account_id;
                $posting->amount = - $dgrl->amount;
                $posting->save();
            }
            
            $transaction->description = implode(' § ', array_keys($descriptions));
            $transaction->notes = implode(' § ', array_keys($notes));
            $this->transaction_id = $transaction->id;
            $transaction->sendToStatus('sealed');
            $transaction->save(false);
            
            // CRITICAL CODE
            // We can't do this because we are alreay in the update() action
            // $this->sendToStatus('journalized');
            // $this->save(false);
            // WE WILL TAKE CARE OF THIS CASE IN THE COMMAND LINE PROCESSING TOOL
            // END CRITICAL CODE
            
            $dbTransaction->commit();
            return true;
        }
        catch (Exception $e)
        {
            $dbTransaction->rollBack();
            return false;
        }
        
    }

    public function processSale($items=[]){
        $fetchedItems = false;
        if (!$items) {
            $items = $this->fetchItems();
            $fetchedItems = true;
        }
        $count = 0;

        Yii::debug('items: ' . json_encode($items));

        foreach($items as $item) {
            $dgrl = $this
                ->getDigitalReceiptLines()
                ->withLineAttributes($item)
                ->one()
            ;
            if ($dgrl) {
                $dgrl->item_assigned_id = $item['id'];
                $dgrl->save(false);
                $count++;
            }
            else {
                Yii::debug('Not found: ' . json_encode($item));
            }
        }
        
        if ($fetchedItems) {
            $this->processed_at = true;
            $this->save(false);
        }

        return $count;
    }

    private function _runWorkflowChecks($event)
    {
        switch ($event->getEndStatus()->getId()) {
            case 'DigitalReceiptWorkflow/issued':
                if (!$this->isReturnReceipt && !$this->hasLines) {
                    $this->workflowError = 'No lines.';
                    $event->invalidate($this->workflowError);
                    break;
                }
                try {
                    Yii::debug("calling issue()");
                    $result = $this->issue();
                    Yii::debug("issue() called");
                    Yii::debug($result);
                }
                catch(Exception $e) {
                    $this->workflowError = 'Error with the external API call.';
                    $event->invalidate($this->workflowError);
                }
                if (!$result) {
                    $this->workflowError = 'The external API refused the processing.';
                    $event->invalidate($this->workflowError);
                }
                break;
            case 'DigitalReceiptWorkflow/voided':
                if (!$this->assigned_id) {
                    $this->workflowError = 'Not registered.';
                    $event->invalidate($this->workflowError);
                    break;
                }
                if (!$this->isVoidable) {
                    $this->workflowError = 'This receipt cannot be voided.';
                    $event->invalidate($this->workflowError);
                    break;
                }
                try {
                    $result = $this->invalidate();
                }
                catch(Exception $e) {
                    $this->workflowError = 'Error with the external API call.';
                    $event->invalidate($this->workflowError);
                }
                if (!$result) {
                    $this->workflowError = 'The external API refused the processing.';
                    $event->invalidate($this->workflowError);
                }
                break;
        }
    }

    private function _runWorkflowRoutines($event)
    {
        $log = "Running workflow routines for digital receipt " . $this->id . "...\n";
        
        $log .= $event->getTransition()->getId() . "\n";
  
        $log .= "My end status is " . $event->getEndStatus()->getId() . "\n";

        if ($event->getEndStatus()->getId() == 'DigitalReceiptWorkflow/issued') {
            // $this->generatePDFIfNeeded();
        }

        if ($event->getEndStatus()->getId() == 'DigitalReceiptWorkflow/sent') {
            $this->journalizeIfNeeded();
        }

        if ($event->getEndStatus()->getId() == 'DigitalReceiptWorkflow/voided') {
            $voidingReceipt = $this->voidingReceipt();
            $log .= "VR found " . $voidingReceipt->id . "\n";
            if ($voidingReceipt) {
                if ($voidingReceipt->send('DigitalReceiptWorkflow/voided')) {
                    $voidingReceipt->sent_at = time();
                    $voidingReceipt->sendToStatus('sent');
                    $voidingReceipt->save(false);
                    $log .= "VR saved " . $voidingReceipt->id . "\n";
                }
            }
        }
        
        //file_put_contents("log_" . time() . ".txt", $log);

        $options = [];
        
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);

    }

    public function issue()
    {
        if ($this->isReturnReceipt) {
            return true;
        }
        $validatorClass = $this->digitalReceiptType->validator;
        return $validatorClass::issueReceipt($this);
    }

    public function invalidate()
    {
        $validatorClass = $this->digitalReceiptType->validator;
        return $validatorClass::voidReceipt($this);
    }
    
    public function fetchPdf()
    {
        $validatorClass = $this->digitalReceiptType->validator;
        return $validatorClass::fetchPdf($this);
    }
    
    public function getCanHavePDFFetched()
    {
        $validatorClass = $this->digitalReceiptType->validator;
        return $this->digitalReceiptType->validator != '\app\components\api\digitalreceipts\InternalDigitalReceiptValidator';
    }
    
    public function getPdf()
    {
        return \Yii::$app->pdfClient->fetchRemotePdf($this->url . '&framed=1', 'A6');
    }

    public function fetchItems()
    {
        $validatorClass = $this->digitalReceiptType->validator;
        return $validatorClass::fetchItems($this);
    }
    
    public function processReturn($items, $reason)
    {
        $validatorClass = $this->digitalReceiptType->validator;
        $id = $validatorClass::processReturn($this, $items, $reason);
        Yii::debug('inside the model ' . $id);
        return $id; //$validatorClass::processReturn($this, $items);
    }
    
    public function adjustQuantities($items, $returnReceipt, $reason)
    {
        $returned_items = [];
        
        $totalAmount = 0;
        
        foreach($items as $item) {
            $drl = $this->getDigitalReceiptLines()->withItemAssignedId($item['id'])->one();
            if ($drl) {
                $drl->quantity_returned += $item['quantity'];
                $drl->save(false);
                $returned_items[] = [
                    'quantity'=>$item['quantity'],
                    'description'=>$drl->description,
                    'unit_price' => $drl->unit_price,
                    'discount' => $drl->unit_discount * $item['quantity'],
                    'sku' => $drl->sku,
                    'vat_rate_code' => $drl->vat_rate_code,
                ];
                $discount = $item['quantity'] * $drl->unit_discount;
                $amount = $item['quantity'] * $drl->unit_price - $discount;
                $totalAmount += $amount;
                $clonedDrl = $drl->cloneModel();
                $clonedDrl->digital_receipt_id = $returnReceipt->id;
                $clonedDrl->line_type = 'RETURN';
                $clonedDrl->quantity = $item['quantity'];
                $clonedDrl->discount = $discount;
                $clonedDrl->amount = $amount;
                $clonedDrl->save(false);
            }
        }
        $notes = $this->getJsonField('notes');
        $notes['items'] = $returned_items;
        $notes['total_amount'] = $totalAmount;
        // $notes['cash_payment_amount'] = $this->cash_payment_amount > 0 ? $totalAmount: 0;
        // $notes['electronic_payment_amount'] = $this->electronic_payment_amount > 0 ? $totalAmount: 0;
        $notes['cash_payment_amount'] = $totalAmount; // returns are always processed by cash
        $notes['electronic_payment_amount'] = 0;
        $notes['reason'] = $reason;
        $returnReceipt->notes = json_encode($notes);
        $returnReceipt->total_amount = $totalAmount;
        // $returnReceipt->cash_payment_amount = $this->cash_payment_amount > 0 ? $totalAmount: 0;
        // $returnReceipt->electronic_payment_amount = $this->electronic_payment_amount > 0 ? $totalAmount: 0;
        
        $returnReceipt->cash_payment_amount = $totalAmount;
        $returnReceipt->electronic_payment_amount = 0;
        
        $returnReceipt->save(false);
    }

    public function cloneModel()
    {
        $model = new DigitalReceipt();
        foreach([
            'organizational_unit_id',
            'digital_receipt_type_id',
            'total_amount',
            'email',
            'phone',
            'cash_payment_amount',
            'electronic_payment_amount',
            'issuer_data',
        ] as $attribute) {
            $model->$attribute = $this->$attribute;
        }
        $model->user_id = Yii::$app->user->id;
        $model->date = date('Y-m-d');
        $model->client_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        ); // we could use uuid_create() as a native alternative (for PHP 8.2+)
        $model->save(false);
        return $model;
    }
    
    public function voidingReceipt(){
        return DigitalReceipt::find()->withAssignedId($this->voiding_receipt_assigned_id)->one();
    }

    public function voidedReceipt(){
        return DigitalReceipt::find()->withAssignedId($this->voided_receipt_assigned_id)->one();
    }
    
    public function getIsVoidOrVoiding() {
        return $this->voided_receipt_assigned_id || $this->voiding_receipt_assigned_id;
    }

    public function getPaymentMethod($i18n=true){
        $v = 'Unknown';
        if ($this->cash_payment_amount > 0) {
            $v = 'Cash';
        } 
        elseif ($this->electronic_payment_amount > 0) {
            $v = 'Card';
        }
        return $i18n? Yii::t('app', $v) : $v;
    }

    public function getPaymentMethodWithUser() 
    {
        if ($this->electronic_payment_amount > 0){
            return $this->getPaymentMethod();
        }
        return sprintf('%s (%s)', $this->getPaymentMethod(), $this->user);
    }
    
    public function getHasPdf() {
        return count($this->files)>0;
    }
    
    public function getHasBeenIssued() {
        return $this->assigned_id !=='NO VALUE' && $this->assigned_id !=='';
    }

    public function getTotalVat() {
        if ($v = $this->voidedReceipt()) {
            return $v->getTotalVat();
        }
        $info = $this->getJsonField('api_response');
        $notes = $this->getJsonField('notes');
        
        $items = $this->isReturnReceipt ? 
            ArrayHelper::getValue($notes, 'items')
            :
            ArrayHelper::getValue($info, 'data.items')
        ;
                
        if($items===false){
            throw new Exception("Computing VAT on a not issued receipt");
        }
        $vat = 0;
        foreach($items as $item) {
            if (is_numeric($item['vat_rate_code'])) {
                $vat_rate = $item['vat_rate_code'];
                $amount = (float)$item['quantity'] * ((float)$item['unit_price'] - (float)$item['discount']);
                $vat += round($amount / (100+$vat_rate) * $vat_rate, 2);
            }
        }
        return $vat;
        
    }

    public function getCompleteSequentialNumber() {
        if (!$this->sequential_number) {
            return null;
        }
        return sprintf('%s-%05d/%s', 
            date('Y', strtotime($this->date)),
            $this->sequential_number,
            $this->digitalReceiptType->sequential_number_code
        );
    }
    
    public function getIsReturnReceipt()
    {
        return $this->parent_id !== null;
    }
    
    public function getTitle() {
        $number = $this->completeSequentialNumber ?? $this->client_id;
        return Yii::t('app', 'Document #') . ' ' .$number;
    }
    
    public function markAsPosted()
    {
        if (!in_array($this->getWorkflowStatus()->getId(), [
                'DigitalReceiptWorkflow/sent',
            ])) {
            return false;
        }

        try {
            $this->sendToStatus('posted');
            return $this->save(false);
        }
        catch (Exception $e) {
            return false;
        }
    }

    public function validatePhoneNumber($attribute)
    {
        $value = $this->$attribute;
        
        // Strip formatting characters
        $cleaned = preg_replace('/[\s\-\.]/', '', $value);
        
        // Check if it starts with +, if not add default country code
        if (!preg_match('/^\+/', $cleaned)) {
            $cleaned = '+' . Yii::$app->params['defaultCountryCode'] . $cleaned;
        }
        
        // Validate that it only contains digits and + now
        if (!preg_match('/^\+\d{10,15}$/', $cleaned)) {
            $this->addError($attribute, Yii::t('app', 'Phone number must be 10-15 digits.'));
        }
        
        // Store cleaned version
        $this->$attribute = $cleaned;
    }

    public function validateOnlyOneField($attribute, $params)
    {
        $email = $this->email;
        $phone = $this->phone;
        
        if (!empty($email) && !empty($phone)) {
            $this->addError('email', Yii::t('app', 'Email and phone cannot both be filled.'));
            $this->addError('phone', Yii::t('app', 'Email and phone cannot both be filled.'));
        }
    }
    
    public static function getBulkActionMessage($action)
    {
        $messages = [
            'markAsPosted' => "{count,plural,=0{No digital receipt have} =1{One digital receipt has} other{# digital receipts have}} been marked as posted.",
        ];
        return ArrayHelper::getValue($messages, $action, '');
    }

    public static function findNextNumber($receipt) 
    {
        return (new Query())
            ->select(['next_number' => new \yii\db\Expression('COALESCE(MAX(sequential_number), 0) + 1')])
            ->from(DigitalReceipt::tableName())
            ->where('YEAR(date) = YEAR(:date)', [':date' => $receipt->date])
            ->andWhere(['=', 'digital_receipt_type_id', $receipt->digital_receipt_type_id])
            ->scalar();
    }

    /**
     * {@inheritdoc}
     * @return DigitalReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DigitalReceiptQuery(get_called_class());
    }

}
