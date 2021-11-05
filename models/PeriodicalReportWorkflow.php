<?php

namespace app\models;

use \raoul2000\workflow\source\file\IWorkflowDefinitionProvider;

class PeriodicalReportWorkflow implements IWorkflowDefinitionProvider
{
    public function getDefinition() {
        $submissionsController = 'periodical-report-submissions';
        $managementController = 'periodical-reports-management';
        
        return [
            'initialStatusId' => 'draft',
            'status' => [
                'draft' => [
                    'transition' => ['submitted', 'submitted-empty'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Reset to draft',
                        'permission' => "$submissionsController/reset-to-draft",
                        'limit' => 'ou',
                        'notifications' => [
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'submitted' => [
                    'transition' => ['closed', 'questioned'],
                    'metadata'   => [
                        'color' => '#009ACC',
                        'verb' => 'Submit',
                        'permission' => "$submissionsController/submit",
                        'limit' => 'ou',
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'submitted-empty' => [
                    'transition' => ['closed', 'questioned'],
                    'metadata'   => [
                        'color' => '#797F81',
                        'verb' => 'Submit Empty',
                        'permission' => "$submissionsController/submit",
                        'condition' => 'isEmpty',
                        'limit' => 'ou',
                        'confirm' => 'Are you sure you want to submit an empty periodical report?',
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'closed' => [
                    'metadata'   => [
                        'color' => 'green',
                        'verb' => 'Set Closed',
                        'permission' => "$managementController/close",
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'questioned' => [
                    'transition' => ['draft'],
                    'metadata'   => [
                        'color' => 'orange',
                        'verb' => 'Ask Clarifications',
                        'permission' => "$managementController/ask-clarifications",
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['name', 'organizationalUnit'],
                    ],
               ],
                'archived' => [
                    'metadata'   => [
                        'color' => '#BFBFBF',
                        'verb' => 'Archive',
                        'permission' => "$managementController/archive",
                        'confirm' => 'Are you sure you want to archive this periodical report?',
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ]
            ]
        ];
    }
}
