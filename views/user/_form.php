<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container-fluid">
    <hr>
    <div class="row-fluid">
        <div class="span6">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>User-info</h5>
                </div>
                <div class="widget-content">

    <?php $form = ActiveForm::begin(); ?>
        <div class="control-group">
        <div class="controls">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true,'class' => 'span11']) ?>
        </div>
        </div>
        <div class="control-group">
        <div class="controls">
        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true,'class' => 'span11']) ?>
        </div>
        </div>
        <div class="control-group">
        <div class="controls">
        <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true,'class' => 'span11']) ?>
        </div>
        </div>
        <div class="control-group">
        <div class="controls">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true,'class' => 'span11']) ?>
        </div>
        </div>
        <div class="control-group">
        <div class="controls">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true,'class' => 'span11']) ?>
        </div>
        </div>
        <div class="control-group">
        <div class="controls">
        <?= $form->field($model, 'user_level')->dropDownList([ 'Super Admin' => 'Super Admin', 'Admin' => 'Admin', ], ['prompt' => '','class' => 'span11']) ?>
        </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
</div>
