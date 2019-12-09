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
 * ProcessController implements the CRUD actions for Newsletters model.
 */
class ProcessController extends Controller
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

    public function actionRun($id)
    {
        $model = $this->findModel($id);
        $validator = new EmailValidator();
        $validator->allowName = true;
        $recipients = $model->getRecipients();
        $recipients = array_unique($recipients);

        $email_from = $this->module->newsletterEmail;
        if (isset(Yii::$app->params['newsletters.newsletterEmail']))
            $email_from = Yii::$app->params['newsletters.newsletterEmail'];

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
            if (!is_null(Yii::getAlias($model->views))) {


                    if (file_exists(Yii::getAlias($model->views.'-html.php')))
                        $views['html'] = $model->views.'-html';
                    elseif (file_exists(Yii::getAlias($model->views.'/html.php')))
                        $views['html'] = $model->views.'/html';

                    if (file_exists(Yii::getAlias($model->views.'-text.php')))
                        $views['text'] = $model->views.'-text';
                    elseif (file_exists(Yii::getAlias($model->views.'/text.php')))
                        $views['text'] = $model->views.'/text';

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
        $this->redirect(['list/index']);
    }


    public function actionPause($id)
    {
        $this->redirect(['list/index']);
    }

    public function actionRefresh($id)
    {
        $this->redirect(['list/index']);
    }

    public function actionStop($id)
    {
        $this->redirect(['list/index']);
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
