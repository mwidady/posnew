<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'bprice') ?>

    <?= $form->field($model, 'sprice') ?>

    <?php // echo $form->field($model, 'vat') ?>

    <?php // echo $form->field($model, 'uid') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'supplier') ?>

    <?php // echo $form->field($model, 'sphone') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
