<?php

namespace app\models;

use yii\db\conditions\OrCondition;
use app\models\Authorization;

/**
 * This is the ActiveQuery class for [[Authorization]].
 *
 * @see Authorization
 */
class AuthorizationQuery extends \yii\db\ActiveQuery
{

    public function active($active=true)
    {
        $now =  date('Y-m-d H:i:s');
        if ($active) {
            return $this->andWhere("'$now' >= [[begin_date]]")->andWhere("'$now' <= [[end_date]]");
        }
        else {
            return $this->andWhere(new OrCondition([
                ['<', '[[end_date]]', $now],
                ['>', '[[begin_date]]', $now]
            ]));
        }
    }
    
    public function withController($controller_id)
    {
        return $this->andWhere(new OrCondition([
            ['=', 'controller_id', $controller_id],
            ['=', 'controller_id', '*']
            ]));
    }

    public function withAction($action_id)
    {
        return $this->andWhere(new OrCondition([
            ['=', 'action_id', $action_id],
            ['=', 'action_id', '*']
            ]));
    }

    public function withMethod($method)
    {
        return $this->andWhere(new OrCondition([
            ['=', 'method', $method],
            ['=', 'method', '*']
            ]));
    }

    public function withActionAndMethod($action_id, $method)
    {
        // for bulk actions, we use the method field as action identifier
        // (we are sure that the real method is 'POST', anyway)
        
        /* In order to allow bulk actions, the authorization should contain
         * the value "bulk" as action_id and the name of the action as method.
         */
        
        if ($action_id=='process') {
            $action_id = 'bulk';
            $method = \Yii::$app->request->get('action');
        }
        return $this
            ->withAction($action_id)
            ->withMethod($method)
            ;
    }
    
    public function withActivePermission($permission)
    {
        $items = Authorization::tokenize($permission);
        return $this
            ->active()
            ->withController($items['controller_id'])
            ->withAction($items['action_id'])
            ->withMethod($items['method'])
            ;
    }
    
    public function withRole($role_id)
    {
        return $this->andWhere(['=', 'role_id', $role_id]);
    }

    
    public function requestedBy(\Yii\web\User $user)
    {
        /* 
         * Special cases in the database:
         *    * all users
         *    @ logged-in user
         *    ? anonymous user
         */
        if ($user->isGuest) {
            return $this->andWhere(new OrCondition([
                ['=', 'type', '*'],
                ['=', 'type', '?']
                ]));
            }
            
        return $this->andWhere(new OrCondition([
            ['=', 'user_id', $user->identity->id],
            ['=', 'type', '*'],
            ['=', 'type', '@'],
            ]));
    }

    public function grantedTo($user_id)
    {
        return $this
            ->andWhere(['=', 'user_id', $user_id])
            ->andWhere(['=', 'type', '-'])
            ;
    }

    public function grantedToRecognizedUsers()
    {
        return $this->andWhere(['=', 'username', '*']);
    }

    /**
     * {@inheritdoc}
     * @return Authorization[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Authorization|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
