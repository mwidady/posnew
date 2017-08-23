<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Quantity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container-fluid">
    <hr>
    <div class="row-fluid">
        <div class="span6">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Quantity-info</h5>
                </div>
                <div class="widget-content">

    <?php $form = ActiveForm::begin(); ?>
    <?=
    $form->field($model, 'item_id')
        ->dropDownList(
            ArrayHelper::map(\app\models\Items::find()->all(), 'id', 'name')
        )
    ?>
    <?= $form->field($model, 'quantity')->textInput(['class' => 'span12']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

        <div class="span6">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Quantity-list</h5>
                </div>
                    <div class="widget-content">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><i class="icon-resize-vertical"></i></th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Last Update</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($provider_quantity AS $items) { ?>
                                <tr>
                                    <td><input type="checkbox"/></td>
                                    <td><?=$items['name']; ?></td>
                                    <td><?=$items['quantity_t'];?></td>
                                    <td><?=$items['last_update'];?></td>


                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <div class ="span12">
                <center>
                    <?php
                    echo \yii\widgets\LinkPager::widget(['pagination'=>$provider->pagination,]);
                    ?>
                </center>
            </div>
            </div>
</div>
</div>
