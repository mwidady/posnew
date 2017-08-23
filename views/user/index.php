<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'first_name',
            'last_name',
            'phone_number',
            'username',
            'email:email',
            // 'password',
            // 'authKey',
            // 'password_reset_token',
            // 'user_image',
            // 'user_level',

            ['class' => 'yii\grid\ActionColumn',
             'header'=>'Action',
            'headerOptions' => ['width' => '120px'],
            'template' => '{view}{link}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
