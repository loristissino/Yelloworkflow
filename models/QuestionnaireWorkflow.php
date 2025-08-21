<?php

namespace app\models;

use \raoul2000\workflow\source\file\IWorkflowDefinitionProvider;

class QuestionnaireWorkflow implements IWorkflowDefinitionProvider
{
    public function getDefinition() {
        
        return [
            'initialStatusId' => 'draft',
            'status' => [
                'draft' => [
                    'transition' => ['published'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Reset to draft',
                        'permission' => 'questionnaires/reset-to-draft',
                    ],
                ],
                'published' => [
                    'transition' => ['archived', 'draft'],
                    'metadata'   => [
                        'color' => 'green',
                        'verb' => 'Publish',
                        'permission' => 'questionnaires/publish',
                    ],
                ],
                'archived' => [
                    'metadata'   => [
                        'color' => '#BFBFBF',
                        'verb' => 'Archive',
                        'permission' => 'questionnaires/archive',
                        'confirm' => 'Are you sure you want to archive this questionnaire?',
                    ],
                ]
            ]
        ];
    }
}
