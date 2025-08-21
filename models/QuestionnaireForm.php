<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html; // Needed for Html::getInputId in rules whenClient

/**
 * QuestionnaireForm is a dynamic model used to collect answers for a specific questionnaire.
 */
class QuestionnaireForm extends Model
{
    public $label;
    private $_questionnaireDefinition; // Stores the parsed JSON definition
    private $_questionnaireId;         // Stores the ID of the Questionnaire DB record
    private $_attributes = [];         // Array to store dynamically created attributes (answers)

    /**
     * Constructor.
     * @param array $questionnaireDefinition The parsed JSON definition of the questionnaire.
     * @param int $questionnaireId The ID of the Questionnaire record.
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($questionnaireDefinition, $questionnaireId, $config = [])
    {
        $this->_questionnaireDefinition = $questionnaireDefinition;
        $this->_questionnaireId = $questionnaireId;
        
        // Initialize dynamic attributes based on the definition so they are known by __get()
        if (isset($this->_questionnaireDefinition['questions']) && is_array($this->_questionnaireDefinition['questions'])) {
            foreach ($this->_questionnaireDefinition['questions'] as $question) {
                $attributeName = 'q_' . $question['id'];
                
                // For checkboxes, the initial value should be an empty array
                if ($question['type'] === 'checkbox') {
                    $this->_attributes[$attributeName] = []; 
                } else {
                    $this->_attributes[$attributeName] = null; // Initialize to null for other types
                }

                // Initialize 'other_text' attribute if applicable
                if ($question['type'] === 'multiple_choice' && isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                     $this->_attributes[$attributeName . '_other_text'] = null;
                }
                // Add _other_text for checkbox if it has an other option
                if ($question['type'] === 'checkbox' && isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                     $this->_attributes[$attributeName . '_other_text'] = null;
                }
            }
        }

        parent::__construct($config);
    }

    /**
     * Magic method to get dynamic attributes.
     * {@inheritdoc}
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        return parent::__get($name);
    }

    /**
     * Magic method to set dynamic attributes.
     * {@inheritdoc}
     */
    public function __set($name, $value)
    {
        // Only set if the attribute name starts with 'q_' indicating a question answer
        // and it's explicitly defined in attributes()
        if (str_starts_with($name, 'q_') && in_array($name, $this->attributes())) {
            $this->_attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * Magic method to check if a dynamic attribute is set.
     * {@inheritdoc}
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->_attributes) || parent::__isset($name);
    }

    /**
     * Returns the list of attribute names that can be mass assigned.
     * This is crucial for `load()` method to work correctly with dynamic attributes.
     * {@inheritdoc}
     */
    public function attributes()
    {
        $attributes = parent::attributes(); 
                        
        if (isset($this->_questionnaireDefinition['questions']) && is_array($this->_questionnaireDefinition['questions'])) {
            foreach ($this->_questionnaireDefinition['questions'] as $question) {
                $attributes[] = 'q_' . $question['id'];
                
                // Add 'other_text' attribute for multiple_choice and checkbox if applicable
                if (($question['type'] === 'multiple_choice' || $question['type'] === 'checkbox') && isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                     $attributes[] = 'q_' . $question['id'] . '_other_text'; 
                }
            }
        }
        return array_unique($attributes);
    }

    /**
     * Dynamically defines validation rules based on the questionnaire definition.
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['label'], 'string', 'max' => 50],
        ];

        if (isset($this->_questionnaireDefinition['questions']) && is_array($this->_questionnaireDefinition['questions'])) {
            foreach ($this->_questionnaireDefinition['questions'] as $question) {
                $attributeName = 'q_' . $question['id'];

                // Add 'required' rule if question is required
                if (isset($question['isRequired']) && $question['isRequired']) {
                    // For checkbox, 'required' means at least one option must be selected
                    if ($question['type'] === 'checkbox') {
                         $rules[] = [$attributeName, 'required', 'message' => Yii::t('app', 'Please select at least one option.'), 'when' => function ($model, $attribute) {
                            return empty($model->$attribute); // Check if the array is empty
                         }];
                    } else {
                        $rules[] = [$attributeName, 'required', 'message' => Yii::t('app', 'This field is required.')];
                    }
                }

                $options = $question['options'] ?? [];
                $optionValues = array_values($options); // Ensure we're working with just values

                // Add type-specific validation rules
                switch ($question['type']) {
                    case 'text':
                    case 'paragraph_text':
                        $rules[] = [$attributeName, 'string'];
                        break;
                    case 'multiple_choice':
                        // Handle 'hasOtherOption'
                        if (isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                            $rules[] = [$attributeName, 'in', 'range' => array_merge($optionValues, ['__other__']), 'message' => Yii::t('app', 'Invalid option selected.')];
                            $otherTextAttribute = $attributeName . '_other_text';
                            $rules[] = [$otherTextAttribute, 'string', 'max' => 255];
                            $rules[] = [$otherTextAttribute, 'required', 'when' => function ($model) use ($attributeName) {
                                return $model->{$attributeName} === '__other__';
                            }, 'whenClient' => "function (attribute, value) { return $('#" . Html::getInputId($this, $attributeName) . "-__other__').is(':checked'); }",
                            'message' => Yii::t('app', 'Please specify other option.')
                            ];
                        } else {
                            $rules[] = [$attributeName, 'in', 'range' => $optionValues, 'message' => Yii::t('app', 'Invalid option selected.')];
                        }
                        break;
                    case 'checkbox':
                        // Checkbox values are typically an array
                        $rules[] = [$attributeName, 'each', 'rule' => ['in', 'range' => array_merge($optionValues, ['__other__'])], 'message' => Yii::t('app', 'Invalid option selected.')];

                        // Handle 'other' option for checkboxes
                        if (isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                            $otherTextAttribute = $attributeName . '_other_text';
                            $rules[] = [$otherTextAttribute, 'string', 'max' => 255];
                            $rules[] = [$otherTextAttribute, 'required', 'when' => function ($model) use ($attributeName) {
                                return is_array($model->{$attributeName}) && in_array('__other__', $model->{$attributeName});
                            }, 'whenClient' => "function (attribute, value) { return $('#" . Html::getInputId($this, $attributeName) . "-__other__').is(':checked'); }",
                            'message' => Yii::t('app', 'Please specify other option.')
                            ];
                        }
                        break;
                    case 'list': // Dropdown
                        $rules[] = [$attributeName, 'in', 'range' => $optionValues, 'message' => Yii::t('app', 'Invalid option selected.')];
                        break;
                    // Add more cases for other question types (e.g., 'number', 'email', 'date')
                    // case 'number':
                    //     $rules[] = [$attributeName, 'number'];
                    //     break;
                    // case 'email':
                    //     $rules[] = [$attributeName, 'email'];
                    //     break;
                }
            }
        }
        return $rules;
    }

    /**
     * Dynamically defines attribute labels based on the questionnaire definition.
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = [
            'label' => Yii::t('app', 'Label'),
        ];

        if (isset($this->_questionnaireDefinition['questions']) && is_array($this->_questionnaireDefinition['questions'])) {
            foreach ($this->_questionnaireDefinition['questions'] as $question) {
                $attributeName = 'q_' . $question['id'];
                $labels[$attributeName] = $question['title']; // Use question title as label
                
                // Add label for 'other_text' if applicable for multiple_choice or checkbox
                if (($question['type'] === 'multiple_choice' || $question['type'] === 'checkbox') && isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                    $labels[$attributeName . '_other_text'] = Yii::t('app', 'Other (please specify)');
                }
            }
        }
        return $labels;
    }

    /**
     * Returns the questionnaire definition.
     * @return array
     */
    public function getQuestionnaireDefinition()
    {
        return $this->_questionnaireDefinition;
    }

    /**
     * Returns the questionnaire ID.
     * @return int
     */
    public function getQuestionnaireId()
    {
        return $this->_questionnaireId;
    }

    /**
     * Collects the answers into a structured array.
     * @return array
     */
    public function getResponseData()
    {
        $answers = [];
        if (isset($this->_questionnaireDefinition['questions']) && is_array($this->_questionnaireDefinition['questions'])) {
            foreach ($this->_questionnaireDefinition['questions'] as $question) {
                $attributeName = 'q_' . $question['id'];
                
                if (array_key_exists($attributeName, $this->_attributes)) {
                    $answerValue = $this->_attributes[$attributeName];

                    // Special handling for 'other' option in multiple choice and checkbox
                    if (($question['type'] === 'multiple_choice' || $question['type'] === 'checkbox') && isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                        $otherTextAttribute = $attributeName . '_other_text';
                        
                        // If '__other__' was selected (for radio) or is in the array (for checkbox)
                        $isOtherSelected = ($question['type'] === 'multiple_choice' && $answerValue === '__other__') ||
                                           ($question['type'] === 'checkbox' && is_array($answerValue) && in_array('__other__', $answerValue));

                        if ($isOtherSelected && array_key_exists($otherTextAttribute, $this->_attributes)) {
                            // If 'other' was chosen, store the text from the other field
                            $answers[$question['id']] = $this->_attributes[$otherTextAttribute];
                        } else if ($question['type'] === 'checkbox' && is_array($answerValue)) {
                            // For checkboxes, remove '__other__' from the array if it's there
                            // and store the other text separately.
                            $filteredAnswers = array_filter($answerValue, function($val) {
                                return $val !== '__other__';
                            });
                            // If only 'other' was selected, and text provided, store text. Else, store filtered answers.
                            if (empty($filteredAnswers) && $isOtherSelected && array_key_exists($otherTextAttribute, $this->_attributes)) {
                                $answers[$question['id']] = $this->_attributes[$otherTextAttribute];
                            } else {
                                $answers[$question['id']] = $filteredAnswers;
                            }
                        } else {
                            $answers[$question['id']] = $answerValue;
                        }
                    } else {
                        // For all other cases, store the direct answer
                        $answers[$question['id']] = $answerValue;
                    }
                } else {
                    $answers[$question['id']] = null; // Or some default if an answer wasn't provided for a non-required field
                }
            }
        }
        return $answers;
    }

    /**
     * Populates the model with existing answers data (e.g., for editing).
     * @param array $answersData The answers data array (e.g., from UserAnswer->answers_data)
     */
    public function setResponseData(array $answersData)
    {
        $this->_attributes = []; 

        foreach ($answersData as $questionId => $answer) {
            $attributeName = 'q_' . $questionId;
            // Assuming $answer directly corresponds to the form value (e.g., '__other__' or actual option)
            // This might need more complex logic if your stored answer combines multiple_choice and other_text
            $this->_attributes[$attributeName] = $answer; 

            // If you have a separate 'other' text field, you'd need to reconstruct its value here too
            // E.g., if $answer is "Other: user input", parse it back into main choice and other text
            // For example, if your stored answer for an 'other' choice is like ['value' => '__other__', 'text' => 'user input']
            // Then you'd do:
            // if (is_array($answer) && isset($answer['value']) && $answer['value'] === '__other__') {
            //     $this->_attributes[$attributeName] = '__other__';
            //     $this->_attributes[$attributeName . '_other_text'] = $answer['text'] ?? null;
            // } else {
            //     $this->_attributes[$attributeName] = $answer;
            // }
        }
        // After loading, ensure any missing attributes from definition are still null
        // This is handled by the constructor now, but good to be aware for re-population.
        // It's crucial that any attribute accessed by the view after setResponseData is still known.
    }
}
