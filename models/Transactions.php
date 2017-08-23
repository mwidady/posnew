<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 * @property integer $item_id
 * @property double $price
 * @property integer $quantity
 * @property integer $user_id
 * @property string $trans_date
 * @property string $ts_time
 * @property integer $customer_id
 * @property string $payment_type
 * @property string $transaction_no
 * @property string $status
 *
 * @property Customers $customer
 * @property User $user
 * @property Customers $customer0
 * @property User $user0
 * @property Items $item
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'price', 'quantity', 'user_id', 'trans_date', 'customer_id', 'payment_type', 'transaction_no'], 'required'],
            [['item_id', 'quantity', 'user_id', 'customer_id'], 'integer'],
            [['price'], 'number'],
            [['trans_date', 'ts_time'], 'safe'],
            [['payment_type', 'status'], 'string', 'max' => 20],
            [['transaction_no'], 'string', 'max' => 50],
            [['transaction_no'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'user_id' => 'User ID',
            'trans_date' => 'Trans Date',
            'ts_time' => 'Ts Time',
            'customer_id' => 'Customer ID',
            'payment_type' => 'Payment Type',
            'transaction_no' => 'Transaction No',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customers::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer0()
    {
        return $this->hasOne(Customers::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }
}
