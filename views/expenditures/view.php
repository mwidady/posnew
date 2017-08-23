<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Expenditures */

$this->title = $model->ename;
$this->params['breadcrumbs'][] = ['label' => 'Expenditures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
<div class="expenditures-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          //  'id',
            'ename',
            'etype',
            'edate',
            [
                 'attribute' => 'amount',
                'format'=>['decimal',2],
            ],
            'amount',
            'company',
            'phone',
            //'user_id',
        ],
    ]) ?>

</div>
</div>
