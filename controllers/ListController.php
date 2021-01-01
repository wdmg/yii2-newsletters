<?php

namespace wdmg\newsletters\controllers;

use wdmg\helpers\ArrayHelper;
use Yii;
use wdmg\newsletters\models\Newsletters;
use wdmg\newsletters\models\NewslettersSearch;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use yii\validators\EmailValidator;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use wdmg\helpers\StringHelper;

/**
 * ListController implements the CRUD actions for Newsletters model.
 */
class ListController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public $defaultAction = 'index';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true
                    ],
                ],
            ]
        ];

        // If auth manager not configured use default access control
        if (!Yii::$app->authManager) {
            $behaviors['access'] = [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true
                    ],
                ]
            ];
        } else if ($this->module->moduleExist('admin/rbac')) { // Ok, then we check access according to the rules
            $behaviors['access'] = [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['update', 'create', 'delete'],
                        'roles' => ['updatePosts'],
                        'allow' => true
                    ], [
                        'roles' => ['viewDashboard'],
                        'allow' => true
                    ],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * Lists all Newsletters models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewslettersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new Newsletters();

        if (Yii::$app->request->isAjax && ($value = Yii::$app->request->get('value'))) {

            $response = [];
            $list = $model->getSubscribersList(['like', 'title', $value], ['id', 'title'], true);
            foreach ($list as $id => $item) {
                $response['list_id:'.$id] = $item['title'];
            }

            $subscribers = $model->getSubscribers(['like', 'email', $value], ['id', 'name', 'email'], true);
            foreach ($subscribers as $id => $subscriber) {
                $response['email_id:'.$id] = ($subscriber['name']) ? $subscriber['name'] . htmlspecialchars(' <'. $subscriber['email'] .'>') : $subscriber['email'];
            }

            return $this->asJson($response);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                // Log activity
                $this->module->logActivity(
                    'Newsletter `' . $model->title . '` with ID `' . $model->id . '` has been successfully added.',
                    $this->uniqueId . ":" . $this->action->id,
                    'success',
                    1
                );

                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/newsletters', 'Newsletter has been successfully created!')
                );
            } else {
                // Log activity
                $this->module->logActivity(
                    'An error occurred while add the new newsletter: ' . $model->title,
                    $this->uniqueId . ":" . $this->action->id,
                    'danger',
                    1
                );

                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/newsletters', 'An error occurred while creating the newsletter.')
                );
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'module' => $this->module,
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && ($value = Yii::$app->request->get('value'))) {

            $response = [];
            $list = $model->getSubscribersList(['like', 'title', $value], ['id', 'title'], true);
            foreach ($list as $id => $item) {
                $response['list_id:'.$id] = $item['title'];
            }

            $subscribers = $model->getSubscribers(['like', 'email', $value], ['id', 'name', 'email'], true);
            foreach ($subscribers as $id => $subscriber) {
                $response['email_id:'.$id] = ($subscriber['name']) ? $subscriber['name'] . htmlspecialchars(' <'. $subscriber['email'] .'>') : $subscriber['email'];
            }

            return $this->asJson($response);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                // Log activity
                $this->module->logActivity(
                    'Newsletter `' . $model->title . '` with ID `' . $model->id . '` has been successfully updated.',
                    $this->uniqueId . ":" . $this->action->id,
                    'success',
                    1
                );

                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/newsletters', 'Newsletter has been successfully updated!')
                );
            } else {
                // Log activity
                $this->module->logActivity(
                    'An error occurred while updating the newsletter `' . $model->title . '` with ID `' . $model->id . '`.',
                    $this->uniqueId . ":" . $this->action->id,
                    'danger',
                    1
                );

                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/newsletters', 'An error occurred while updating the newsletter.')
                );
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'module' => $this->module,
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            // Log activity
            $this->module->logActivity(
                'Newsletter `' . $model->title . '` with ID `' . $model->id . '` has been successfully deleted.',
                $this->uniqueId . ":" . $this->action->id,
                'success',
                1
            );

            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('app/modules/newsletters', 'Newsletter has been successfully deleted!')
            );
        } else {
            // Log activity
            $this->module->logActivity(
                'An error occurred while deleting the newsletter `' . $model->title . '` with ID `' . $model->id . '`.',
                $this->uniqueId . ":" . $this->action->id,
                'danger',
                1
            );

            Yii::$app->getSession()->setFlash(
                'danger',
                Yii::t('app/modules/newsletters', 'An error occurred while deleting the newsletter.')
            );
        }

        return $this->redirect(['index']);
    }

    public function actionPreview($id)
    {
        $model = self::findModel($id);
        return $this->renderAjax('_preview', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Newsletters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Newsletters::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException(Yii::t('app/modules/newsletters', 'The requested page does not exist.'));
    }
}
