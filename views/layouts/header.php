<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
//$this->registerJsFile('@web/js/bootstrap.min.js');
//$this->registerJsFile('@web/js/jquery.peity.min.js');
//$this->registerJsFile('@web/js/fullcalendar.min.js');
//$this->registerJsFile('@web/js/matrix.js');
//$this->registerJsFile('@web/js/matrix.dashboard.js');


//AppAsset::register($this);
use kartik\sidenav\SideNav;
?>
<!--Header-part-->
<div id="header">
    <h1><a href="dashboard.html">POS</a></h1>
</div>
<!--close-Header-part-->


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
    <ul class="nav">
<li  class="dropdown" id="profile-messages" ><a title="" href="#"><i class="icon icon-user"></i>  <span class="text">Welcome

            <?php
            $username = \app\models\User::find()->where(['id' => Yii::$app->user->id])->one();
              echo ucfirst($username['first_name'])." ".ucfirst($username['last_name']);
            ?>
</span></a>
            </li>


        <li class="dropdown" id="menu-messages"><a href="#" ><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span></a>
        <li class="">
           <?= Html::a('<i class="icon icon-share-alt"></i> <span class="text">Logout</span>', ['site/logout'], ['data-method' => 'post']);?>
        </li>
    </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<div id="search">
    <input type="text" class="form-control" style="padding: 15px;" placeholder="Search here..."/>
    <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--close-top-serch-->
<!--sidebar-menu-->
<div id="sidebar">

    <?php
    echo SideNav::widget([
    //'type' => SideNav::TYPE_SUCCESS,
      'options' => ['id' => 'sidebar'],
    //'heading' => '<i class="icon icon-home"></i> Dashboard',
    'encodeLabels' => false,
    'items' => [
    [
    'url' => Url::toRoute('/site/index'),
    'label' => 'Dashboard',
        'options' => ['class' => 'active'],
    'icon' => 'home'
    ],
    [
    'url' => Url::toRoute('/items/sales'),
    'label' => 'Sales',
    'icon' => 'signal'
    ],
    [
        'url' => Url::toRoute('/items/index'),
        'label' => 'Items',
        'icon' => 'list'
    ],
    [
        'url' => Url::toRoute('/customers/index'),
        'label' => 'Customers',
        'icon' => 'th'
    ],
    [
        'url' => Url::toRoute('/transactions/index'),
        'label' => 'Transactions',
        'icon' => 'briefcase'
    ],
    [
    'label' => 'Stocks <span class="pull-right badge btn-danger">3</span>',
    'icon' => 'th-list',
    'items' => [
        ['label' => 'Stock Details', 'icon'=>'list', 'url'=>Url::toRoute('/quantity/stock')],
        ['label' => 'Add Quantity', 'icon'=>'list', 'url'=>Url::toRoute('/quantity/create'),],
        ['label' => 'Quantitity Logs', 'icon'=>'list', 'url'=>Url::toRoute('/quantity/index'),],
    ],
    ],
    [
    'url' => Url::toRoute('/expenditures/index'),
    'label' => 'Expenditures',
    'icon' => 'tint'
    ],
        [
            'label' => 'Users <span class="pull-right badge btn-danger">2</span>','visible' => Yii::$app->user->can('storekeeper'),
            'icon' => 'user',
            'items' => [
                ['label' => 'All Users', 'icon'=>'list', 'url'=>Url::toRoute('/user/index')],
                ['label' => 'Change Password',
                    'icon'=>'rock',
                    'url'=>Url::toRoute('/user/changepassword'),
                ],
            ],
        ],
    [
        'url' => Url::toRoute('/items/invoices'),
        'label' => 'Invoices',
        'icon' => 'record'
    ],

        [
            'url' => Url::toRoute('/items/receipts'),
            'label' => 'Receipts',
            'icon' => 'list'
        ],

        [
            'url' => Url::toRoute('/transactions/profits'),
            'label' => 'Sales Profits',
            'icon' => 'file'
        ],
        [
            'url' => Url::toRoute('/items/profitsn'),
            'label' => 'Profits',
            'icon' => 'copy'
        ],
        [
            'url' => Url::toRoute('/transactions/index'),
            'label' => 'Reports',
            'icon' => 'list'
        ],

    ],
    ]);
    ?>
</div>
<!--sidebar-menu-->