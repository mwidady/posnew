<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property string $fname
 * @property string $lname
 * @property string $gender
 * @property string $phone
 * @property string $company
 * @property string $clogo
 * @property string $position
 * @property integer $id
 * @property string $customer_no
 * @property string $address
 * @property string $email
 *
 * @property Transactions[] $transactions
 * @property Transactions[] $transactions0
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fname', 'lname', 'gender', 'phone', 'company', 'clogo', 'position', 'customer_no', 'address', 'email'], 'required'],
            [['fname', 'lname', 'email'], 'string', 'max' => 40],
            [['gender', 'customer_no'], 'string', 'max' => 30],
            [['phone'], 'string', 'max' => 12],
            [['company', 'position'], 'string', 'max' => 50],
            [['clogo'], 'string', 'max' => 200],
            [['address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fname' => 'First name',
            'lname' => 'Last name',
            'gender' => 'Gender',
            'phone' => 'Phone Number',
            'company' => 'Company',
            'clogo' => 'Company logo',
            'position' => 'Position',
            'id' => 'ID',
            'customer_no' => 'Customer No',
            'address' => 'Address',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transactions::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions0()
    {
        return $this->hasMany(Transactions::className(), ['customer_id' => 'id']);
    }
}
