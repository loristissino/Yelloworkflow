<?php

namespace app\models;

use \raoul2000\workflow\source\file\IWorkflowDefinitionProvider;

class DigitalReceiptWorkflow implements IWorkflowDefinitionProvider
{
    public function getDefinition() {
        
        return [
            'initialStatusId' => 'saved',
            'status' => [
                'saved' => [
                    'transition' => ['issued', 'sent'], // the transition to "sent" is for voiding receipt
                    'metadata'   => [
                        'color' => '#800080',
                        'verb' => 'Create',
                        'permission' => "digital-receipts/create",
                        'limit' => 'ou',
                        'notifications' => [
                        ],
                        //'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'issued' => [
                    'transition' => ['sent', 'voided'],
                    'metadata'   => [
                        'color' => 'green',
                        'verb' => 'Issue',
                        'permission' => "digital-receipts/issue",
                        'notifications' => [
                            //'ou',
                        ],
                        'notification_fields' => ['clientId', 'digitalReceiptLines', 'totalAmount', 'organizationalUnit'],
                    ],
                ],
                'sent' => [
                    'transition' => ['journalized', 'voided', 'posted', 'recorded'],
                    'metadata'   => [
                        'color' => 'green',
                        'verb' => 'Send',
                        'permission' => "digital-receipts/send",
                        'notifications' => [
                        ],
                        //'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'voided' => [
                    'transition' => [],
                    'metadata'   => [
                        'color' => 'red',
                        'verb' => 'Void',
                        'confirm' => 'Are you sure you want to void this digital receipt?',
                        'permission' => "digital-receipts/invalidate",
                        'condition' => 'isVoidable',
                        'notifications' => [
                        ],
                        //'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'journalized' => [
                    'transition' => ['recorded'],
                    'metadata'   => [
                        'color' => 'blue',
                        'verb' => 'Journalize',
                        'permission' => 'automations/journalize', // for the console application, not for humans
                        'notifications' => [
                        ],
                        //'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'recorded' => [
                    'transition' => [],
                    'metadata'   => [
                        'color' => '#1E90FF',
                        'verb' => 'Mark as Recorded',
                        'permission' => 'automations/record', // for the console application, not for humans
                        'notifications' => [
                        ],
                        //'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
                'posted' => [
                    'transition' => [],
                    'metadata'   => [
                        'color' => '#004180',
                        'verb' => 'Mark as Posted',
                        'permission' => 'periodical-reports-management/index',
                        'condition' => 'isMarkableAsPosted',
                        'notifications' => [
                        ],
                        //'notification_fields' => ['name', 'organizationalUnit'],
                    ],
                ],
            ]
        ];
    }
}
