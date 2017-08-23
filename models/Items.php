<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property double $bprice
 * @property double $sprice
 * @property string $vat
 * @property integer $uid
 * @property string $unit
 * @property string $supplier
 * @property string $sphone
 *
 * @property User $u
 */
class Items extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'bprice', 'sprice', 'vat', 'uid', 'unit', 'supplier', 'sphone'], 'required'],
            [['bprice', 'sprice'], 'number'],
            [['uid'], 'integer'],
            [['name', 'supplier'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 100],
            [['vat'], 'string', 'max' => 3],
            [['unit'], 'string', 'max' => 40],
            [['sphone'], 'string', 'max' => 20],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'bprice' => 'Buying price',
            'sprice' => 'Selling price',
            'vat' => 'Vat',
            'uid' => 'Username',
            'unit' => 'Unit',
            'supplier' => 'Supplier',
            'sphone' => 'Supplier Phone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }
    public function getQuantitytotal()
    {
        return $this->hasOne(QuantityTotal::className(), ['item_id' => 'id']);
    }

}
