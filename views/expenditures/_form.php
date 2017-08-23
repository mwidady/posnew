<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Expenditures */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container-fluid">
    <hr>
    <div class="row-fluid">
        <div class="span6">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Expenditure-info</h5>
                </div>
                <div class="widget-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ename')->textInput(['maxlength' => true,'class' => 'span11']) ?>

    <?php
    $et= ['Domestic' => 'Domestic', 'Transport' => 'Transport','Petty Cash' => 'Petty Cash'];
    echo $form->field($model, 'etype')->dropDownList($et,['prompt'=>'Select Expenditure','class' => 'span11']);
    ?> <?php
                    echo \kartik\date\DatePicker::widget([
                        'model' => $model,
                        'form' => $form,
                        'attribute' => "edate",
                        'options' => [
                            'placeholder' => 'Select date ...',
                            'template' => '{widget}{error}',
                            'class' => 'span11',
                        ],
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
        ?>
    <?= $form->field($model, 'amount')->textInput(['class' => 'span11']) ?>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true,'class' => 'span11']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true,'class' => 'span11']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
</div>
