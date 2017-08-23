<?php

namespace app\controllers;


use app\models\Customers;
use app\models\QuantityTotal;
use Yii;
use yii\filters\AccessControl;
use app\models\Transactions;
use app\models\Items;
use app\models\ItemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;

use kartik\mpdf\Pdf;
use yii\helpers\json;


/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],

            ], 'access' => [
                'class' => AccessControl::className(),
                //'denyCallback' => function ($rule, $action) {
                  //  throw new \Exception('You are not allowed to access this page');
               // },
                'rules' => [
                    [
                        'actions' => ['login','error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Items models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Items model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Items();

        if ($model->load(Yii::$app->request->post())) {
            $model->uid =  Yii::$app->user->id;
              $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Items model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionRemove($id)
    {
        $connection = \Yii::$app->db;
        $connection	->createCommand()
            ->delete('transactions', 'id ='.$id)
            ->execute();
        return $this->redirect(['sales']);
    }
    public function actionRemoveall()
    {
        $connection = \Yii::$app->db;

         $connection ->createCommand("DELETE FROM transactions WHERE user_id ='".Yii::$app->user->id."' AND status = 'unsold'")
            ->execute();
        return $this->redirect(['sales']);
    }

    public function actionAddquantity($id)
    {
       $check_item_id = Transactions::find()->where(['id' =>$id])->one();

       $check_quantity = QuantityTotal::find()->where(['item_id'=>$check_item_id->item_id])->one();

        if($check_item_id->quantity == $check_quantity->quantity_t){
            \Yii::$app->getSession()->setFlash('error','Product out of stock');
        }else{
            $connection = \Yii::$app->db;
            $connection->createCommand('UPDATE transactions SET quantity = quantity + 1 WHERE id='.$id)
                ->execute();
        }


        return $this->redirect(['sales','show_customer' => '1']);
    }

    public function actionRemovequantity($id)
    {
        $connection = \Yii::$app->db;
             $connection->createCommand('UPDATE transactions SET quantity = quantity - 1 WHERE id='.$id)
            ->execute();
        return $this->redirect(['sales','show_customer' => '1']);
    }

    public function actionSell()
    {
        $modelc = new Customers();
        if ($modelc->load(Yii::$app->request->post())){
            $customer_id = $_POST['Customers']['id'];
        }

        $ts_time = new \yii\db\Expression('NOW()');

        $connection = \Yii::$app->db;

        $modelcheck = $connection->createCommand("SELECT * FROM transactions WHERE status ='unsold' AND user_id=".Yii::$app->user->id);
        $check_items= $modelcheck->queryAll();
            foreach($check_items AS $ckeck){
           $connection->createCommand('UPDATE transactions SET status = "invoice",ts_time = '.$ts_time.',customer_id ='.$customer_id.' WHERE status= "unsold" AND item_id = '.$ckeck['item_id'].' AND user_id='.Yii::$app->user->id)
           ->execute();

         $connection->createCommand("UPDATE quantity_total SET quantity_t = quantity_t - '".$ckeck['quantity']."' WHERE  item_id = '".$ckeck['item_id']."'")
           ->execute();
            }
        $model2 = $connection->createCommand("SELECT DISTINCT(T.ts_time) AS pdatetime,U.first_name,U.last_name,C.clogo,C.fname,C.lname,C.phone,C.company,C.address FROM 
          transactions T,user U,customers C WHERE T.customer_id = C.id AND U.id = T.user_id AND T.status='invoice' AND T.user_id=".Yii::$app->user->id." ORDER BY T.id DESC");
        $receipt_details= $model2->queryOne();
        $ts_time2 = $receipt_details['pdatetime'];

        $model = $connection->createCommand("SELECT T.id, I.name,T.price,T.quantity,I.supplier,I.vat,I.unit,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='invoice' AND T.ts_time ='".$ts_time2."'");
        $receipt_items= $model->queryAll();


        return $this->render('receipt', [
            'receipt_items' => $receipt_items,
            'receipt_details' => $receipt_details,
        ]);

    }
    public function actionInvdetails($inv_time)
    {
        $ts_time = $inv_time;

        $connection = \Yii::$app->db;

       // "SELECT DISTINCT(T.ts_time) AS pdatetime,U.first_name,U.last_name,C.clogo,C.fname,C.lname,C.phone,C.company,C.address FROM transactions T,customers C,users U WHERE T.customer_id = C.id AND T.user_id = U.id
        $model2 = $connection->createCommand("SELECT DISTINCT(T.ts_time) AS pdatetime,U.first_name,U.last_name,C.clogo,C.fname,C.lname,C.phone,C.company,C.address FROM 
          transactions T,customers C,user U WHERE T.customer_id = C.id AND T.user_id = U.id AND T.status='invoice' AND T.ts_time='".$ts_time."' ORDER BY T.id DESC");
        $receipt_details= $model2->queryOne();


        $model = $connection->createCommand("SELECT T.id, I.name,T.price,T.quantity,I.supplier,I.vat,I.unit,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='invoice' AND T.ts_time ='".$ts_time."'");
        $receipt_items= $model->queryAll();


        return $this->render('receipt', [
            'receipt_items' => $receipt_items,
            'receipt_details' => $receipt_details,
        ]);

    }
    public function actionRecdetails($rec_time)
    {
        $ts_time = $rec_time;

        $connection = \Yii::$app->db;


        $model2 = $connection->createCommand("SELECT DISTINCT(T.ts_time) AS pdatetime,U.first_name,U.last_name,C.fname,C.lname,C.phone,C.company,C.address FROM 
          transactions T,customers C,user U WHERE T.customer_id = C.id  AND T.user_id = U.id AND T.status='receipt' AND T.ts_time = '".$ts_time."' ORDER BY T.id DESC");
        $receipt_details= $model2->queryOne();


        $model = $connection->createCommand("SELECT T.id, I.name,T.price,T.quantity,I.supplier,I.vat,I.unit,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='receipt' AND T.ts_time ='".$ts_time."'");
        $receipt_items= $model->queryAll();


        return $this->render('receipt_final', [
            'receipt_items' => $receipt_items,
            'receipt_details' => $receipt_details,
        ]);

    }
    public function actionPayinvoice($ts_time)
    {
       

        $connection = \Yii::$app->db;

		
        $connection->createCommand("UPDATE transactions SET status = 'receipt' WHERE status= 'invoice' AND ts_time = '".$ts_time."'")
                ->execute();

        $model2 = $connection->createCommand("SELECT DISTINCT(T.ts_time) AS pdatetime,U.first_name,U.last_name,C.fname,C.lname,C.phone,C.company,C.address FROM transactions T,customers C,user U WHERE T.customer_id = C.id AND T.user_id = U.id
AND T.status='receipt' AND T.ts_time='".$ts_time."' ORDER BY T.id DESC");
        $receipt_details= $model2->queryOne();

        $model = $connection->createCommand("SELECT T.id, I.name,T.price,T.quantity,I.supplier,I.vat,I.unit,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND status = 'receipt' AND T.ts_time ='".$ts_time."'");
        $receipt_items= $model->queryAll();

                return $this->render('receipt_final', [
                    'receipt_items' => $receipt_items,
                    'receipt_details' => $receipt_details,
                ]);

    }

    public function actionCdetails($id){
        $customer_d = Customers::find()->where(['id' => $id])->one();
      ?>
        <table class="table table-bordered table-striped" >

            <tr>
                <td colspan="2">
                    <center>
                        <img src="<?php echo Yii::getAlias('@web').'/uploads/'.$customer_d['clogo']; ?>" style="width:100px; height:50px;">
                    </center>
                </td>
            </tr>
            <tr>
                <th>Full Name</th><td><?=$customer_d['fname']." ".$customer_d['lname'];?></td>
            </tr>
            <tr>
                <th>Gender</th><td><?=$customer_d['gender'];?></td>
            </tr>
            <tr>
                <th>Phone</th><td><?=$customer_d['phone'];?></td>
            </tr>
            <tr>
                <th>Company</th><td><?=$customer_d['company'];?></td>
            </tr>
        </table>
<?php
    }
    public function actionSales()
    {
       $userid =  Yii::$app->user->id;
        $connection = \Yii::$app->db;
       // $sql=  "SELECT T.id,(T.quantity * T.price) AS total1, I.name,T.price,T.quantity,I.supplier,I.vat FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='unsold' AND T.user_id = '".$userid."'";
        $sql=  "SELECT T.id, I.name,I.sprice,T.quantity,I.supplier,I.vat FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='unsold' AND T.user_id = '".$userid."' ";
        $count =  "SELECT COUNT(T.id) AS id, I.name,I.sprice,T.quantity,I.supplier,I.vat FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='unsold' AND T.user_id = '".$userid."' ";

          //  $modeli = $connection->createCommand("SELECT T.id, I.name,I.sprice,T.quantity,I.supplier,I.vat FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='unsold' AND T.user_id = '".$userid."' ");
           // $provider_items= $modeli->queryAll();

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'id',
            'totalCount' => $count,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => Transactions::find()->where(['status' => 'unsold', 'user_id' => $userid]),
            'pagination' => [
                'pageSize' => 20,
            ],
            ]);
        $provider_items = $dataProvider->getModels();

           $model = new Items();
           $modelt = new Transactions();
           $customer = new Customers();
/*
        if (isset($_POST['hasEditable'])) {
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $bookId = Yii::$app->request->post('editableKey');
            $modeltt = Transactions::findOne($bookId);

            // store a default json response as desired by editable
            $out = Json::encode(['output'=>'', 'message'=>'']);

            // fetch the first entry in posted data (there should only be one entry
            // anyway in this array for an editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $posted = current($_POST['Transactions']);
            $post = ['Transactions' => $posted];

            // read your posted model attributes
            if ($modeltt->load($post)) {

                $modeltt->save();
                // read or convert your posted information
                $value = $modeltt->quantity;

                // return JSON encoded output in the below format
                //return ['output' => $value, 'message' => ''];
                $out = Json::encode(['output'=>$value, 'message'=>'']);
            }
            // return ajax json encoded response and exit
           // echo $out;
           // return;
                // alternatively you can return a validation error
                // return ['output'=>'', 'message'=>'Validation error'];
            } // else if nothing to do always return an empty JSON encoded output
            else {
               echo ['output' => '', 'message' => ''];
            }
*/
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            //$bookId =
          $bookId = Yii::$app->request->post('editableKey');

          // $bookId = 195;
            $modeltt = Transactions::findOne($bookId);

            // store a default json response as desired by editable
          //  $out = Json::encode(['output'=>'', 'message'=>'']);

            // fetch the first entry in posted data (there should only be one entry
            // anyway in this array for an editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation

            $posted = current($_POST['Transactions']);
            $post = ['Transactions' => $posted];
            // load model like any single model validation
           // if ($modeltt->load($post)) {
            if ($modeltt->load($post)) {
              //  $modeltt->save(false);
                $output = $posted['quantity'];

                $check_quantity = QuantityTotal::find()->where(['item_id'=>$modeltt->item_id])->one();

                if($output > $check_quantity->quantity_t) {
                    $out = Json::encode(['output'=>$output, 'message'=>'Quantity out of stock']);
                }else{



                $connection = \Yii::$app->db;
                 $connection->createCommand("UPDATE transactions SET quantity = '".$output."' WHERE id = '".$bookId."'")
                        ->execute();
                // custom output to return to be displayed as the editable grid cell
                // data. Normally this is empty - whereby whatever value is edited by
                // in the input by user is updated automatically.


                // specific use case where you need to validate a specific
                // editable column posted when you have more than one
                // EditableColumn in the grid view. We evaluate here a
                // check to see if buy_amount was posted for the Book model

                // similarly you can check if the name attribute was posted as well
                // if (isset($posted['name'])) {
                // $output = ''; // process as you need
                // }
                $out = Json::encode(['output'=>$output, 'message'=>'']);
                }
            }
            // return ajax json encoded response and exit
            echo $out;
            return  $this->redirect('sales?show_customer=1') ;
        }
        if ($model->load(Yii::$app->request->post())) {


            $check_exist_item =Items::find()->where(['id'=>$model->name])->count();
            $check_exist_item_curent_sales =Transactions::find()->where(['item_id'=>$model->name,'user_id' =>Yii::$app->user->id,'status' => 'unsold'])->count();

            if($check_exist_item_curent_sales > 0){
               // //\Yii::$app->getSession()->setFlash('error','No product with such number found');

            }
           else if($check_exist_item < 1){
                \Yii::$app->getSession()->setFlash('error','No product with such number found');
               // print("no product found");
            }else{
				$check_sold_price =Items::find()->where(['id'=>$model->name])->one();
                $modelt->user_id = Yii::$app->user->id;
                $modelt->trans_date = new \yii\db\Expression('NOW()');
                $modelt->price=$check_sold_price->sprice;
                $modelt->customer_id=1;
                $modelt->item_id = $model->name;
                $modelt->payment_type=1;
                $modelt->quantity=1;
                $modelt->transaction_no=rand(100,10000);
                $modelt->save(false);
            }

          return $this->redirect(['sales', 'provider_items' => $provider_items,'dataProvider'=>$dataProvider,'show_customer' => '1',]);
        } else {

            return $this->render('sales', [
                'model' => $model,'customer' => $customer,'dataProvider'=>$dataProvider,'provider_items' => $provider_items,
            ]);
        }


    }
    public function actionTransactions()
    {
        $connection = \Yii::$app->db;
        $sql = "SELECT T.id, I.name,I.sprice,T.quantity,I.supplier,I.vat,I.unit,T.status,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status != 'unsold' ";
        $model = $connection->createCommand($sql);
        $provider_itemsa= $model->queryAll();

        $count = sizeof($provider_itemsa);


        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

            return $this->render('transactions', [
                'provider_items' => $dataProvider->getModels(),'provider' => $dataProvider
            ]);



    }
    public function actionProfits()
    {
        $connection = \Yii::$app->db;
        $sql = "SELECT T.id, I.name,I.sprice,I.bprice,T.quantity,I.supplier,I.vat,I.unit,T.status,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status = 'receipt' ";
        $model = $connection->createCommand($sql);
        $provider_itemsa= $model->queryAll();

        $count = sizeof($provider_itemsa);


        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

            return $this->render('profits', [
                'provider_items' => $dataProvider->getModels(),'provider' => $dataProvider
            ]);



    }
    public function actionProfitsn()
    {

        $modelt = new Transactions();
        $connection = \Yii::$app->db;
        if(isset($_POST['Transactions']['ts_time'])) {

                $sdate = explode(' To ',$_POST['Transactions']['ts_time']);

            $sql1 = "SELECT T.id, I.name,I.bprice,T.price,T.quantity,I.supplier,I.unit,T.status,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status = 'receipt' AND DATE(T.ts_time) BETWEEN '".$sdate[0]."' AND '".$sdate[1]."'";
            $sql2 = "SELECT ename,etype,edate,amount,company,phone FROM expenditures WHERE  edate BETWEEN '".$sdate[0]."' AND '".$sdate[1]."'";
            $pdate = $_POST['Transactions']['ts_time'];
        }else{
           $date_today = date('Y-m-d');

            $sql1 = "SELECT T.id, I.name,I.bprice,T.price,T.quantity,I.supplier,I.unit,T.status,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status = 'receipt' AND DATE(T.ts_time) = '".$date_today."'";
            $sql2 = "SELECT ename,etype,edate,amount,company,phone FROM expenditures WHERE edate = '".$date_today."'";
            $pdate =0;
        }
        $model = $connection->createCommand($sql1);
        $provider_trans = $model->queryAll();

        $model2 = $connection->createCommand($sql2);
        $provider_expend = $model2->queryAll();

        return $this->render('profitsn', [
            'provider_trans' => $provider_trans,
            'model' => $modelt,
            'pdate' => $pdate,
            'provider_expend' => $provider_expend,
        ]);
    }




    public function actionInvoices()
    {
        $connection = \Yii::$app->db;
        $sql = "SELECT SUM(T.price * T.quantity) AS sumin,COUNT(T.item_id) AS nitem,U.first_name,U.last_name,C.fname,C.lname,C.phone,C.company, T.ts_time,T.status FROM items I,user U,transactions T LEFT JOIN customers C ON C.id=T.customer_id  WHERE I.id = T.item_id AND T.user_id = U.id AND T.status = 'invoice' GROUP BY ts_time";
        $model = $connection->createCommand($sql);
        $provider_itemsa= $model->queryAll();

        $count = sizeof($provider_itemsa);


        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('invoices', [
            'provider_items' => $dataProvider->getModels(),'provider' => $dataProvider
        ]);
    }



    public function actionReceipts()
    {
        $connection = \Yii::$app->db;
        $sql = "SELECT SUM(T.price * T.quantity) AS sumin,COUNT(T.item_id) AS nitem,U.first_name,U.last_name,C.fname,C.lname,C.phone,C.company,T.ts_time,T.status FROM items I,user U,transactions T LEFT JOIN customers C ON C.id=T.customer_id  WHERE I.id = T.item_id AND U.id = T.user_id AND T.status = 'receipt' GROUP BY ts_time";
        $model = $connection->createCommand($sql);
        $provider_itemsa= $model->queryAll();
        $count = sizeof($provider_itemsa);


        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('receipts', [
            'provider_items' => $dataProvider->getModels(),'provider' => $dataProvider,
        ]);



    }

    /**
     * Deletes an existing Items model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionInvoiceprint($inv_pid)
    {

        $connection = \Yii::$app->db;
        $model2 = $connection->createCommand("SELECT DISTINCT(T.ts_time) AS pdatetime,U.first_name,U.last_name,C.clogo,C.fname,C.lname,C.phone,C.company,C.address FROM transactions T,customers C,users U WHERE T.customer_id = C.id AND T.user_id = U.id
         AND T.status='invoice' AND T.ts_time='".$inv_pid."' ORDER BY T.id DESC");

        $receipt_details= $model2->queryOne();
        $ts_time2 = $receipt_details['pdatetime'];

        $model = $connection->createCommand("SELECT T.id, I.name,T.price,T.quantity,I.supplier,I.vat,I.unit,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='invoice' AND T.ts_time ='".$ts_time2."'");
        $receipt_items= $model->queryAll();


       // $pdf = Yii::$app->pdf;
        //$pdf->content = $this->renderPartial('receipt', [
        $content = $this->renderPartial('receipt_p', [
            'receipt_items' => $receipt_items,
            'receipt_details' => $receipt_details,
            'showoftion' =>1,
        ]);

        $pdf = new Pdf([
            // set to use core fonts only
           // 'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting

            'cssFile' => '@webroot/css/bootstrap.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:10px; text-align:center;} .htable td{border:1px solid #f5f5f5; padding:5px; font-family:serif; border-collapse:collapse;} .htable{font-family:serif; border:1px solid #f5f5f5; border-collapse:collapse; margin-left:5x;}',
            // set mPDF properties on the fly
            'options' => ['title' => 'K-POS INVOICE'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['K-POS'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
    public function actionReceiptprint($inv_pid)
    {
        $ts_time = $inv_pid;

        $connection = \Yii::$app->db;


        $model2 = $connection->createCommand("SELECT DISTINCT(T.ts_time) AS pdatetime,C.clogo,C.fname,C.lname,C.phone,C.company,C.address FROM transactions T,customers C  WHERE T.status='receipt' AND T.ts_time='".$ts_time."' ORDER BY T.id DESC");
        $receipt_details= $model2->queryOne();


        $model = $connection->createCommand("SELECT T.id, I.name,T.price,T.quantity,I.supplier,I.vat,I.unit,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status ='receipt' AND T.ts_time ='".$ts_time."'");
        $receipt_items= $model->queryAll();


       // $pdf = Yii::$app->pdf;
        //$pdf->content = $this->renderPartial('receipt', [
        $content = $this->renderPartial('receipt_final', [
            'receipt_items' => $receipt_items,
            'receipt_details' => $receipt_details,
            'showoftion' =>1,
        ]);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting


            'cssFile' => '@webroot/css/bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:10px} .htable td{border:1px solid #f5f5f5;} .htable{border:1px solid #f5f5f5; margin-left:5x;}',
            // set mPDF properties on the fly
                'options' => ['title' => 'K-POS RECEIPT'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['K-POS'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
