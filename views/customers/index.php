<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <p>
        <?= Html::a('Create Customers', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fname',
            'lname',
            'gender',
            'phone',
            'company',
            // 'position',
            // 'id',
            /*[
                'attribute' => 'clogo',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@web/')."uploads/".$data['clogo'], ['width' => '70px']);
                },
            ],
*/
            // 'address',
            'email:email',

            ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
