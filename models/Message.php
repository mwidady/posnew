<?php
/**
 * Created by PhpStorm.
 * User: mwidadi
 * Date: 6/27/2017
 * Time: 7:19 PM
 */
namespace app\models;


class Message extends \bubasuma\simplechat\db\Message
{
    /**
     * @inheritDoc
     */
    public function fields()
    {
        return [
            //...
            'text',
            'date' => 'created_at',
            //...
        ];
    }
}