<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$img = Url::to('@web/');
?>
<div id="loginbox">
    <?php $form = ActiveForm::begin([
        'id' => 'loginform',
       
    ]); ?>
        <div class="control-group normal_text">
            <img src="<?php echo $img; ?>/img/logo.png">
        </div>
		
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lr"><i class="icon-user"> </i></span>
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span>
                    <?= $form->field($model, 'password')->passwordInput()->label(false)  ?>
                </div>
            </div>
        </div>
    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"col-lg-offset-1 col-lg-12\">{input} {label}</div>\n<div class=\"col-lg-12\">{error}</div>",
    ]) ?>
    <div class="form-actions" style="width:100%; float:left;">
        <span class="pull-left"><a href="#" class="flip-link btn btn-danger" id="to-recover">Lost password?</a></span>
        <span class="pull-right"><?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?></span>
    </div>
    </form>
    <?php ActiveForm::end(); ?>
    <?php $form = ActiveForm::begin([
        'id' => 'recoverform',
        'class' => 'form-vertical',
        'method'=>'POST',
        'action' => ['forgotpassword'],

    ]); ?>
        <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>

        <div class="controls">
            <div class="main_input_box">
                <span class="add-on bg_lo"><i class="icon-envelope"></i></span>
                <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(false) ?>
            </div>
        </div>

        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
            <span class="pull-right">
            <?= Html::submitButton('Recover', ['class' =>'btn btn-info']) ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>

    <?php
    if(Yii::$app->session->hasFlash('error')){
        ?>
        <div class="alert alert-danger">
            <?php
            echo Yii::$app->session->getFlash('error');
            ?>
        </div>
        <?php
    }
    ?>
    <?php
    if(Yii::$app->session->hasFlash('error')){
        ?>
        <div class="alert alert-success">
            <?php
            echo Yii::$app->session->getFlash('success');
            ?>
        </div>
        <?php
    }
    ?>
</div>