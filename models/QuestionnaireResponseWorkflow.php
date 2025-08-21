<?php

namespace app\models;

use \raoul2000\workflow\source\file\IWorkflowDefinitionProvider;

class QuestionnaireResponseWorkflow implements IWorkflowDefinitionProvider
{
    public function getDefinition() {
        
        return [
            'initialStatusId' => 'draft',
            'status' => [
                'draft' => [
                    'transition' => ['submitted'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Reset to draft',
                        'permission' => 'questionnaire-responses/reset-to-draft',
                    ],
                ],
                'submitted' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => 'green',
                        'verb' => 'Submit',
                        'permission' => 'questionnaire-responses/submit',
                    ],
                ],
                'archived' => [
                    'metadata'   => [
                        'color' => '#BFBFBF',
                        'verb' => 'Archive',
                        'permission' => 'questionnaires-responses/archive',
                        'confirm' => 'Are you sure you want to archive this response?',
                    ],
                ]
            ]
        ];
    }
}
