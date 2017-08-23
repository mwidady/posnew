<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expenditures".
 *
 * @property integer $id
 * @property string $ename
 * @property string $etype
 * @property string $edate
 * @property double $amount
 * @property string $company
 * @property string $phone
 * @property integer $user_id
 *
 * @property User $user
 */
class Expenditures extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expenditures';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ename', 'etype', 'edate', 'amount', 'company', 'phone', 'user_id'], 'required'],
            [['edate'], 'safe'],
            [['amount'], 'number'],
            [['user_id'], 'integer'],
            [['ename', 'company'], 'string', 'max' => 50],
            [['etype'], 'string', 'max' => 40],
            [['phone'], 'string', 'max' => 12],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ename' => 'Expenditure name',
            'etype' => 'Expenditure type',
            'edate' => 'Expenditure date',
            'amount' => 'Amount',
            'company' => 'Company',
            'phone' => 'Phone',
            'user_id' => 'Username',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
