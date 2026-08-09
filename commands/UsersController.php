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
use yii\helpers\Markdown;
use app\models\User;
use app\components\api\sms\OpenApiSmsService;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()->active()->all();

        foreach($users as $user) {
            echo sprintf("%d: %s\n", $user->id, $user->email);
        }
        return ExitCode::OK;
    }
    
    public function actionSyncMemberships()
    {
        $year = (int)date('Y');
        $users = User::find()->active()->all();
        $renewalsUrl = \Yii::$app->params['externalInfo']['renewalsUrl'];
        if ($renewalsUrl){
            $ids = file($renewalsUrl);
            $updated = [];
            foreach($ids as $id) {
                $id = trim($id);
                $user = User::find()->where(['external_id' => $id])->andWhere(['<>', 'last_renewal', $year])->one();
                if ($user) {
                    $user->updateAttributes(['last_renewal'=>$year]);
                    $updated[] = $user->fullName;
                }
            }
            
            $count = sizeof($updated);
            
            $notifiedUser = User::find()->withUsername(\Yii::$app->params['externalInfo']['renewalsNotificationUser'])->one();
            if($notifiedUser) {
                $message = Yii::t('app', 'Updated users: {list}.', ['list'=>join(', ', $updated)]);
                $notification = new \app\models\Notification();
                $notification->user_id = $notifiedUser->id;
                $notification->subject = \Yii::t('app', 'Last renewal updated for {count,plural,=0{no users} =1{one user} other{# users}}.', ['count'=>$count]);
                $notification->plaintext_body = $message;
                $notification->html_body = $message;
                $notification->sendEmail(false);
            }
        }
        return ExitCode::OK;
    }

    public function actionSendSms($phone, $message)
    {
        echo $phone . ' ' .$message . "\n";
	// $service = new OpenApiSmsService();
        // $service->send($phone, $message);
        Yii::$app->smsservice->send($phone, $message);
        return ExitCode::OK;
    }
    
    public function actionGenerateAuthKeys()
    {
        foreach (User::find()->all() as $user) {
            $user->auth_key = $user->generateAuthKey();
            echo $user->auth_key . "\n";
            $user->save(false);
        }
    }
    
}

