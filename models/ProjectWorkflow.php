<?php

namespace app\models;

use \raoul2000\workflow\source\file\IWorkflowDefinitionProvider;

class ProjectWorkflow implements IWorkflowDefinitionProvider
{
    public function getDefinition() {
        $submissionsController = 'project-submissions';
        $managementController = 'projects-management';
        
        return [
            'initialStatusId' => 'draft',
            'status' => [
                'draft' => [
                    'transition' => ['submitted', 'deleted'],
                    'metadata'   => [
                        'color' => 'gray',
                        'verb' => 'Reset to draft',
                        'permission' => "$submissionsController/reset-to-draft",
                        'limit' => 'ou',
                        'notifications' => [
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ],
                'submitted' => [
                    'transition' => ['approved', 'rejected', 'questioned'],
                    'metadata'   => [
                        'color' => '#009ACC',
                        'verb' => 'Submit',
                        'permission' => "$submissionsController/submit",
                        'limit' => 'ou',
                        'notifications' => [
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ],
                'approved' => [
                    'transition' => ['draft', 'suspended', 'ended'],
                    'metadata'   => [
                        'color' => 'green',
                        'verb' => 'Approve',
                        'permission' => "$managementController/approve",
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ],
                'rejected' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => 'red',
                        'verb' => 'Reject',
                        'permission' => "$managementController/reject",
                        'confirm' => 'Are you sure you want to reject this project?',
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ],
                'questioned' => [
                    'transition' => ['submitted', 'draft'],
                    'metadata'   => [
                        'color' => 'orange',
                        'verb' => 'Ask Clarifications',
                        'permission' => "$managementController/ask-clarifications",
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
               ],
                'deleted' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => 'red',
                        'verb' => 'Delete',
                        'confirm' => 'Are you sure you want to delete this project?',
                        'permission' => "$submissionsController/delete",
                        'limit' => 'ou',
                    ],
                ],
                'suspended' => [
                    'transition' => ['approved'],
                    'metadata'   => [
                        'color' => '#ADD8E6',
                        'verb' => 'Suspend',
                        'permission' => "$managementController/suspend",
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ],
                'ended' => [
                    'transition' => ['reimbursed'],
                    'metadata'   => [
                        'color' => '#7F7F7F',
                        'verb' => 'Mark ended',
                        'confirm' => 'Are you sure you want to mark this project ended (with no expenses)?',
                        'confirmCondition' => 'missingExpenses',
                        'permission' => "$submissionsController/end",
                        'limit' => 'ou',
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ],
                'reimbursed' => [
                    'transition' => ['archived'],
                    'metadata'   => [
                        'color' => '#738579',
                        'verb' => 'Mark Reimbursed',
                        'permission' => "$managementController/suspend",
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ],
                'archived' => [
                    'metadata'   => [
                        'color' => '#BFBFBF',
                        'verb' => 'Archive',
                        'permission' => "$managementController/archive",
                        'confirm' => 'Are you sure you want to archive this project?',
                        'notifications' => [
                            "$managementController/view" => '*',
                            "$submissionsController/view" => 'ou',
                        ],
                        'notification_fields' => ['title', 'organizationalUnit'],
                    ],
                ]
            ]
        ];
    }
}
