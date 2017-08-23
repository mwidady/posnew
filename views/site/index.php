<?php
use yii\helpers\Html;
$this->title = 'Home';
$this->params['breadcrumbs'][] = $this->title;
?>

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li class="bg_lb span3">
                    <a href="#"> <i class="icon-dashboard"></i> <span class="label label-important"><?=$countItem;?></span> My Dashboard </a>
                </li>
                <li class="bg_lg span2">
                    <?= Html::a('<i class="icon-signal"></i> Sales', ['items/sales'], []) ?>
                </li>
                <li class="bg_lo span2">
                    <?= Html::a(' <i class="icon-user"></i>  Customers', ['customers/index'], []) ?>
                </li>
                <li class="bg_ls span3">
                    <?= Html::a(' <i class="icon-list-alt"></i> Items  Customers', ['items/index'], []) ?>
                </li>
            </ul>

            <div class="widget-box">
                <div class="widget-title bg_lg"><span class="icon"><i class="icon-signal"></i></span>
                    <h5>Sales Analytics</h5>
                </div>
                <div class="widget-content" >
                    <div class="row-fluid">
                        <div class="span9">
                            <div class="chart">

                                    <table class="table table-bordered data-table">
                                        <thead>
                                        <tr>
                                            <th><i class="icon-resize-vertical"></i>SNO</th>
                                            <th>Name</th>
                                            <th>Unit</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>VAT</th>
                                            <th>Date-Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sum = 0;
                                        $count =1;
                                        foreach($provider_items AS $items) {
                                            ?>
                                            <tr class="gradeX">
                                                <td><center><?=$count;?></center></td>
                                                <td><?=$items['name']; ?></td>
                                                <td><?=$items['unit']; ?></td>
                                                <td><?=number_format($items['price'],2); ?></td>
                                                <td><?=$items['quantity']; ?></td>
                                                <td><?=number_format($items['quantity'] * $items['price'],2);?></td>
                                                <td><?=$items['vat']; ?></td>
                                                <td><?=$items['ts_time']; ?></td>
                                            </tr>
                                            <?php
                                            $sum += $items['quantity'] * $items['price'];
                                            $count++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                        <div class="span3">
                            <ul class="site-stats">
                                <li class="bg_lh">
                                    <?= Html::a(' <i class="icon-user"></i> <strong>'.$countItem.'</strong> <small>Total Items</small>', ['items/index'], []) ?>
                                </li>
                                <li class="bg_lh">
                                    <?= Html::a(' <i class="icon-plus"></i> <strong>'.$countUser.'</strong> <small>New Users </small>', ['#'], []) ?>
                                </li>
                                <li class="bg_lh">
                                    <?= Html::a(' <i class="icon-shopping-cart"></i> <strong>'.$countTranso.'</strong> <small>Cart</small>', ['users/index'], []) ?>
                                </li>
                                <li class="bg_lh">
                                    <?= Html::a(' <i class="icon-tag"></i> <strong>'.$countTransc.'</strong> <small>Total Sales</small>', ['items/receipts'], []) ?>
                                </li>
                                <li class="bg_lh">
                                    <?= Html::a(' <i class="icon-repeat"></i> <strong>'.$countOrder.'</strong> <small>Pending Orders</small>', ['items/invoices'], []) ?>
                                </li>
                                <li class="bg_lh">
                                    <?= Html::a(' <i class="icon-globe"></i> <strong>'.$countTrans.'</strong> <small>Online Orders</small>', ['transactions/index'], []) ?>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>



