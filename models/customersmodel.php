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
 * @property string $position
 * @property integer $id
 * @property string $customer_no
 * @property string $address
 * @property string $email
 *
 * @property Transactions[] $transactions
 */
class Customers extends \yii\db\ActiveRecord
{
    public $fullname;
    public $clogo;
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
            [['fname', 'lname', 'gender', 'phone', 'company', 'position', 'customer_no', 'address', 'email'], 'required'],
            [['fname', 'lname', 'email'], 'string', 'max' => 40],
            [['gender', 'customer_no'], 'string', 'max' => 30],
            [['phone'], 'string', 'max' => 12],
            [['company', 'position'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
            [['fullname','clogo'], 'safe'],
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
            'phone' => 'Phone',
            'company' => 'Company',
            'clogo' => 'Company Logo',
            'position' => 'Position',
            'id' => 'ID',
            'customer_no' => 'Customer Number',
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

    public function getCustomerFullname()
    {
        return $this->fname." ".$this->lname;
    }
}
