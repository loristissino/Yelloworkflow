<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
//use yii\helpers\Markdown;
use app\models\Message;
use app\models\Notification;
use app\models\PeriodicalReport;
use app\models\NotificationTemplate;
use app\models\PetitionSignature;
use app\components\LogHelper;

class NotificationsController extends Controller
{
    public function actionIndex($limit=100)
    {
        $messages = Message::find()->sent(false)->orderBy(['created_at' => SORT_ASC])->limit($limit)->all();

        foreach($messages as $message) {
            echo sprintf("%d: %s\n", $user->id, $user->email);
        }
        return ExitCode::OK;
    }
    
    public function actionSend($limit=6)
    {
        $data = ['notifications' => [], 'messages' => [] ];

        $messages = Message::find()->sent(false)->orderBy(['created_at' => SORT_ASC])->limit($limit)->all();

        $count = 0;
        foreach($messages as $message) {
            $status = $message->sendEmail() ? 'sent': 'not sent';
            $data['messages'][] = [
                'message_id' => $message->id,
                'status' => $status,
            ];
            sleep(1); // let's wait one second between each email...
            $count++;
        }

        $notifications = Notification::find()->sent(false)->orderBy(['created_at' => SORT_ASC])->limit(6-$count)->all();
        
        foreach($notifications as $notification) {
            $status = $notification->sendEmail() ? 'sent': 'not sent';
            $data['notifications'][] = [
                'notification_id' => $notification->id,
                'status' => $status,
            ];
            sleep(1); // let's wait one second between each email...
        }

        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        return ExitCode::OK;
    }
    
    public function actionPrepareReminders($type='workflow', $petition_key='') {
        $data = [];
        switch($type) {
            case 'workflow':
                $reports = PeriodicalReport::find()->toRemindToday()->all();
                $template = NotificationTemplate::find()->withCode('PeriodicalReportWorkflow/remind')->one();
                if ($template) {
                    foreach($reports as $report){
                        $count = LogHelper::notify($report, $template);
                        $data[] = [
                            'report' => $report->id,
                            'due_date' => $report->dueDate,
                            'notifications' => $count,
                            ];
                    }
                }
                break;

            case 'petition_signatures':
                $signatures = PetitionSignature::find()->confirmed(false)->reminded(false)->createdBefore(time() - 24*60*60)->all();
                foreach($signatures as $signature) {
                    $signature->prepareRemindEmail($petition_key);
                    $data[] = [
                        'signature' => $signature->id,
                    ];
                }
        }
        
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        return ExitCode::OK;

    }
    
}
