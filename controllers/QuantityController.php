<?php

namespace app\controllers;

use app\models\QuantityTotal;
use Yii;
use yii\filters\AccessControl;
use app\models\Quantity;
use app\models\QuantitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;

/**
 * QuantityController implements the CRUD actions for Quantity model.
 */
class QuantityController extends Controller
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
            ],
            'access' => [
                'class' => AccessControl::className(),
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
     * Lists all Quantity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuantitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Quantity model.
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
     * Creates a new Quantity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $connection = \Yii::$app->db;
        $sql = "SELECT I.name,Q.quantity_t,Q.last_update FROM items I LEFT JOIN quantity_total Q ON I.id = Q.item_id ORDER BY quantity_t DESC";
        $modelt = $connection->createCommand($sql);
        $provider_quantitya= $modelt->queryAll();

        $count = sizeof($provider_quantitya);

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 4,
            ],
        ]);

        $model = new Quantity();
        $modelTotal = new QuantityTotal();



        if ($model->load(Yii::$app->request->post())) {

            $check_found = QuantityTotal::find()->where(['item_id'=>$model->item_id])->count();
            if($check_found > 0){
                $update_curent_quantity = QuantityTotal::find()->where(['item_id'=>$model->item_id])->one();
                $last_update = date('Y-m-d');
                $connection = \Yii::$app->db;
                $connection->createCommand("UPDATE quantity_total SET quantity_t = '".$model->quantity."' + '".$update_curent_quantity->quantity_t."',last_update = '".$last_update."' WHERE item_id='".$model->item_id."'")
                    ->execute();
            }else {

                $modelTotal->last_update = date('Y-m-d');
                $modelTotal->quantity_t = $model->quantity;
                $modelTotal->item_id = $model->item_id;
                $modelTotal->save(false);
            }
            $model->q_date = date('Y-m-d');
            $model->user_id= Yii::$app->user->id;
            $model->save(false);
           // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect('create');
        } else {
            return $this->render('create', [
                'model' => $model,'provider_quantity'=>$dataProvider->getModels(),'provider' => $dataProvider,
            ]);
        }
    }
    public function actionStock(){
        $connection = \Yii::$app->db;
        $sql ="SELECT I.name,I.bprice,I.sprice,I.vat,Q.quantity_t FROM items I LEFT JOIN quantity_total Q ON I.id = Q.item_id ORDER BY Q.quantity_t DESC";
        $modeli = $connection->createCommand($sql);
        $provider_itemsa= $modeli->queryAll();

        $count = sizeof($provider_itemsa);


        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('stock',[
            'provider_items' => $dataProvider->getModels(),'provider' => $dataProvider
            ,]);

    }

    /**
     * Updates an existing Quantity model.
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

    /**
     * Deletes an existing Quantity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Quantity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quantity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quantity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
