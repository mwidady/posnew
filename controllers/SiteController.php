<?php

namespace app\controllers;

use app\models\Items;
use Yii;
use yii\data\SqlDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Transactions;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','error','forgotpassword'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $connection = \Yii::$app->db;
        $sql = "SELECT T.id, I.name,T.price,T.quantity,I.supplier,I.vat,I.unit,T.status,T.ts_time FROM items I,transactions T WHERE I.id = T.item_id AND T.status != 'unsold' ORDER BY T.id DESC";
        $model = $connection->createCommand($sql);
        $provider_itemsa= $model->queryAll();

        $count = sizeof($provider_itemsa);


        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 7,
            ],
        ]);

        $countItem = Items::find()->count();
        $countUser = User::find()->count();
		$countTrans =Transactions::find()->count();
		$countOrder =Transactions::find()->where(['status' => 'invoice'])->count();
		$countTransc =Transactions::find()->where(['status' => 'receipt'])->count();
		$countTranso =Transactions::find()->where(['status' => 'unsold'])->count();
        return $this->render('index',[
            'countItem' => $count,
			'countUser' => $countItem,
			'countOrder' => $countOrder,
			'countTrans' => $countTrans,
			'countTransc' => $countTransc,
			'countTranso' => $countTranso,
			'provider_items' => $dataProvider->getModels(),
			'provider' => $dataProvider
			]);
    }

    //sales
    public function actionSales()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        return $this->render('sales');
    }
    public function actionTransactions()
    {
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'mainlogin';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
public function actionForgotpassword(){
        $model = new LoginForm();

        if($model->load(Yii::$app->request->post())){
            $count_us = User::find()->where(['email' => $model->email])->count();
            if($count_us > 0){
                $connection = \Yii::$app->db;
                $connection->createCommand("UPDATE users SET password = md5('12345') email='".$model->email."'")->execute();
                \Yii::$app->getSession()->setFlash('success','Please login using password sent to your email');
            }else{
                \Yii::$app->getSession()->setFlash('error','Wrong email Address');
            }
        }
         return $this->redirect('login');
}
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
