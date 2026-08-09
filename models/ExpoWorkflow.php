<?php

namespace app\models;

use \raoul2000\workflow\source\file\IWorkflowDefinitionProvider;

class ExpoWorkflow implements IWorkflowDefinitionProvider
{
    public function getDefinition() {
        
        return [
            'initialStatusId' => 'draft',
            'status' => [
                'draft' => [
                    'transition' => ['active'],
                    'metadata'   => [
                        'color' => '#7F7F7F',
                        'verb' => 'Create',
                        'permission' => "expos/create",
                        'notifications' => [
                        ],
                    ],
                ],
                'active' => [
                    'transition' => ['closed'],
                    'metadata'   => [
                        'color' => 'green',
                        'verb' => 'Activate',
                        'permission' => "expos/activate",
                        'notifications' => [
                        ],
                    ],
                ],
                'closed' => [
                    'transition' => [],
                    'metadata'   => [
                        'color' => '#FFA500',
                        'verb' => 'Close',
                        'permission' => "expos/close",
                        'notifications' => [
                        ],
                    ],
                ],
            ]
        ];
    }
}
