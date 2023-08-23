<?php

namespace app\models;

use \raoul2000\workflow\source\file\IWorkflowDefinitionProvider;

class TransactionWorkflow implements IWorkflowDefinitionProvider
{
    public function getDefinition() {
        $submissionsController = 'transaction-submissions';
        $managementController = 'transactions-management';
        
        return [
            'initialStatusId' => 'draft',
            'status' => [
                'draft' => [
                    'transition' => ['confirmed', 'prepared', 'sealed'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Reset to draft',
                        'permission' => "$submissionsController/reset-to-draft",
                        'weigth' => 1,
                        'limit' => 'ou',
                        'icon' => '📝',
                    ],
                ],
                'confirmed' => [
                    'transition' => ['submitted', 'draft', 'sealed'],
                    'metadata'   => [
                        'color' => '#009ACC',
                        'verb' => 'Confirm',
                        'condition' => 'canBeConfirmed',
                        'confirm' => 'Confirm this transaction without any project?',
                        'confirmCondition' => 'missingProject',
                        'permission' => "$submissionsController/confirm",
                        'limit' => 'ou',
                        'weigth' => 2,
                    ],
                ],
                'sealed' => [
                    'transition' => ['handled', 'rejected'],
                    'metadata'   => [
                        'color' => '#0000FF',
                        'verb' => 'Seal',
                        'confirm' => 'Seal this transaction?',
                        'condition' => 'canBeSealed',
                        'permission' => "$submissionsController/confirm",
                        'limit' => 'ou',
                        'weigth' => 4,
                        'notifications' => [
                            "office-transactions/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['description', 'templateTitle', 'organizationalUnit'],
                    ],
                ],
                'handled' => [
                    'transition' => ['recorded'],
                    'metadata'   => [
                        'color' => '#005700',
                        'verb' => 'Mark handled',
                        'permission' => "fast-transactions/view",
                        'weigth' => 8,
                        'notifications' => [
                            "office-transactions/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['description', 'templateTitle', 'organizationalUnit'],
                    ],
                ],
                'rejected' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => '#FF0000',
                        'verb' => 'Reject',
                        'confirm' => 'Reject this transaction?',
                        'permission' => "fast-transactions/view",
                        'weigth' => -1,
                        'notifications' => [
                            "office-transactions/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['description', 'templateTitle', 'handling', 'organizationalUnit'],
                    ],
                ],
                'questioned' => [
                    'transition' => ['draft'],
                    'metadata'   => [
                        'color' => 'orange',
                        'verb' => 'Ask Clarifications',
                        'condition' => 'isDirectlyQuestionable', // this is used only in the interface -- we don't want the button to be shown
                        'permission' => "$managementController/ask-clarifications",
                        'weigth' => 16,
                    ],
                ],
                'submitted' => [
                    'transition' => ['recorded', 'draft', 'questioned'],
                    'metadata'   => [
                        'color' => 'green',
                        'condition' => 'isDirectlySubmittable',
                        'verb' => 'Submit',
                        'permission' => "periodical-report-submissions/submit",
                        // it is set to this status when the periodical report is submitted
                        'weigth' => 32,
                    ],
                ],
                'prepared' => [
                    'transition' => ['notified', 'extra'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Prepare',
                        'permission' => "office-transactions/create",
                        'weigth' => 64,
                    ],
                ],
                'notified' => [
                    'transition' => ['recorded'],  // CHECK: submitted take away
                    'metadata'   => [
                        'color' => 'orange',
                        'verb' => 'Notify',
                        'permission' => "office-transactions/create",
                        'weigth' => 128,
                        'notifications' => [
                            "office-transactions/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['description', 'organizationalUnit'],
                    ],
                ],
                'recorded' => [
                    'transition' => ['archived', 'reimbursed'],
                    'metadata'   => [
                        'color' => '#0000FF',
                        'verb' => 'Set Recorded',
                        'permission' => "$managementController/set-registered",
                        'weigth' => 256,
                        'icon' => '📒',
                    ],
                ],
                'reimbursed' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => '#A52A2A',
                        'verb' => 'Set Reimbursed',
                        'permission' => "projects-management/set-reimbursed",
                        'weigth' => 512,
                    ],
                ],
                'extra' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => '#BFBFBF',
                        'condition' => 'isExtra',
                        'verb' => 'Set Extra',
                        'permission' => "office-transactions/create",
                        'weigth' => 1024,
                        'notifications' => [
                            "office-transactions/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['description', 'organizationalUnit'],
                    ],
                ],
                'archived' => [
                    'metadata'   => [
                        'color' => '#BFBFBF',
                        'verb' => 'Archive',
                        'permission' => "backend-transactions/archive",
                        'weigth' => -1,
                    ],
                ]
            ]
        ];
    }
}
