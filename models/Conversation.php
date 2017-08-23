<?php
/**
 * Created by PhpStorm.
 * User: mwidadi
 * Date: 6/27/2017
 * Time: 7:17 PM
 */

namespace app\models;


namespace common\models;

use app\models\User;
//...

class Conversation extends \bubasuma\simplechat\db\Conversation
{
    public function getContact()
    {
        return $this->hasOne(User::className(), ['id' => 'contact_id']);
    }

    /**
     * @inheritDoc
     */
    protected static function baseQuery($userId)
    {
        return parent::baseQuery($userId) ->with(['contact.profile']);
    }

    /**
     * @inheritDoc
     */
    public function fields()
    {
        return [
            //...
            'contact' => function ($model) {
                return $model['contact'];
            },
            'deleteUrl',
            'readUrl',
            'unreadUrl',
            //...
        ];
    }
}