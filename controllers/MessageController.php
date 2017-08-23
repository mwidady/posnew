<?php
/**
 * Created by PhpStorm.
 * User: mwidadi
 * Date: 6/27/2017
 * Time: 7:22 PM
 */
namespace app\controllers;
use yii\web\Controller;
use app\models\Conversation;
use app\models\Message;
use bubasuma\simplechat\controllers\ControllerTrait;

class MessageController extends Controller
{
    use ControllerTrait;

    /**
     * @return string
     */
    public function getMessageClass()
    {
        return Message::className();
    }

    /**
     * @return string
     */
    public function getConversationClass()
    {
        return Conversation::className();
    }
}