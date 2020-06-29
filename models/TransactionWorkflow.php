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
                    'transition' => ['confirmed', 'prepared'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Reset to draft',
                        'permission' => "$submissionsController/reset-to-draft",
                        'limit' => 'ou',
                    ],
                ],
                'confirmed' => [
                    'transition' => ['submitted', 'draft'],
                    'metadata'   => [
                        'color' => '#009ACC',
                        'verb' => 'Confirm',
                        'confirm' => 'Confirm this transaction without any project?',
                        'confirmCondition' => 'missingProject',
                        'permission' => "$submissionsController/confirm",
                        'limit' => 'ou',
                    ],
                ],
                'questioned' => [
                    'transition' => ['draft'],
                    'metadata'   => [
                        'color' => 'orange',
                        'verb' => 'Ask clarifications',
                        'permission' => "$managementController/ask-clarifications",
                    ],
                ],
                'submitted' => [
                    'transition' => ['recorded'],
                    'metadata'   => [
                        'color' => 'green',
                        'condition' => 'isDirectlySubmittable',
                        'verb' => 'Submit',
                        'permission' => "periodical-report-submissions/submit"
                        // it is set to this status when the periodical report is submitted
                    ],
                ],
                'prepared' => [
                    'transition' => ['notified'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Prepare',
                        'permission' => "office-transactions/create"
                    ],
                ],
                'notified' => [
                    'transition' => ['submitted'],
                    'metadata'   => [
                        'color' => 'orange',
                        'verb' => 'Notify',
                        'permission' => "office-transactions/create",
                    ],
                ],
                'recorded' => [
                    'transition' => ['archived', 'reimbursed'],
                    'metadata'   => [
                        'color' => '#0000FF',
                        'verb' => 'Set Recorded',
                        'permission' => "$managementController/set-registered",
                    ],
                ],
                'reimbursed' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => '#A52A2A',
                        'verb' => 'Set Reimbursed',
                        'permission' => "projects-management/set-reimbursed",
                    ],
                ],
                'archived' => [
                    'metadata'   => [
                        'color' => '#BFBFBF',
                        'verb' => 'Archive',
                        'permission' => "backend-transactions/archive",
                    ],
                ]
            ]
        ];
    }
}
