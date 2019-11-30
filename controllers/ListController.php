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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true
                    ],
                ],
            ]
        ];

        // If auth manager not configured use default access control
        if(!Yii::$app->authManager) {
            $behaviors['access'] = [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true
                    ],
                ]
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


    public function actionProcess($id, $newsletter)
    {
        $model = $this->findModel($id);
        $validator = new EmailValidator();

        if ($newsletter == "run") {

            $recipients = $model->getRecipients();
            $recipients = array_unique($recipients);

            $email_from = $this->module->newsletterEmail;
            if(isset(Yii::$app->params['newsletterEmail']))
                $email_from = Yii::$app->params['newsletterEmail'];

            if (is_null($email_from) || !($validator->validate($email_from)))
                throw new InvalidConfigException(Yii::t('app/modules/newsletters', 'Param `newsletterEmail` must be a valid email adress.'));

            $workflow = [];
            if ($mailer = Yii::$app->getMailer()) {

                if (!is_null($model->layouts)) {
                    if (is_dir(Yii::getAlias($model->layouts))) {
                        //$mailer->viewPath = $model->layouts;

                        if (file_exists(Yii::getAlias($model->layouts.'/layouts/html.php')))
                            $mailer->htmlLayout = 'layouts/html';

                        if (file_exists(Yii::getAlias($model->layouts.'/layouts/text.php')))
                            $mailer->htmlLayout = 'layouts/text';

                    }
                }

                $views = null;
                if (!is_null($model->views)) {
                    if (is_dir(Yii::getAlias($model->views))) {

                        if (file_exists(Yii::getAlias($model->views.'/html.php')))
                            $views['html'] = $model->views.'/html';

                        if (file_exists(Yii::getAlias($model->views.'-html.php')))
                            $views['html'] = $model->views.'-html';

                        if (file_exists(Yii::getAlias($model->views.'/text.php')))
                            $views['text'] = $model->views.'/text';

                        if (file_exists(Yii::getAlias($model->views.'-text.php')))
                            $views['text'] = $model->views.'-text';

                    }
                }

                foreach ($recipients as $email_to) {

                    if ($compose = $mailer->compose($views, ['content' => $model->content])) {

                        $compose->setFrom($email_from)
                            ->setTo($email_to)
                            ->setSubject($model->subject);

                        if (is_null($views)) {
                            $compose->setHtmlBody($model->content);
                            $compose->setTextBody(StringHelper::stripTags($model->content, "", " "));
                        }

                        if ($compose->send()) {

                            $workflow[$email_to] = [
                                'is_send' => true
                            ];
                        } else {
                            $workflow[$email_to] = [
                                'is_send' => false
                            ];
                        }

                    }
                }
            }
            $model->updateAttributes(['workflow' => Json::encode($workflow)]);
        } else if ($newsletter == "refresh") {
            $model->updateAttributes(['workflow' => Json::encode([])]);
        }

        die();
        $this->redirect(['list/index']);
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

            if ($model->save())
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/newsletters', 'Newsletter has been successfully updated!')
                );
            else
                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/newsletters', 'An error occurred while updating the newsletter.')
                );

        }

        return $this->render('update', [
            'module' => $this->module,
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

            if ($model->save())
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/newsletters', 'Newsletter has been successfully created!')
                );
            else
                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/newsletters', 'An error occurred while creating the newsletter.')
                );

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'module' => $this->module,
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete())
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('app/modules/newsletters', 'Newsletter has been successfully deleted!')
            );
        else
            Yii::$app->getSession()->setFlash(
                'danger',
                Yii::t('app/modules/newsletters', 'An error occurred while deleting the newsletter.')
            );

        return $this->redirect(['index']);
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
