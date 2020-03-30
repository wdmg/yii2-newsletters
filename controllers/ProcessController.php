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
        if(!Yii::$app->authManager) {
            $behaviors['access'] = [
                'class' => AccessControl::class,
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
        if ($model) {
            $model->updateWorkflow('status', 'run');
        } else {
            $this->redirect(['list/index']);
        }

        $validator = new EmailValidator();
        $validator->allowName = true;
        $recipients = $model->getRecipients();

        if ($recipients) {
            $model->updateWorkflow('count', count($recipients));
        } else {
            $model->updateWorkflow('count', 0);
            $model->updateWorkflow('status', 'stop');

            // Log activity
            if (
                class_exists('\wdmg\activity\models\Activity') &&
                $this->module->moduleLoaded('activity') &&
                isset(Yii::$app->activity)
            ) {
                Yii::$app->activity->set(
                    'Newsletter `' . $model->title . '` with ID `' . $model->id . '` has been stopped.',
                    $this->uniqueId . ":" . $this->action->id,
                    'warning',
                    1
                );
            }

            $this->redirect(['list/index']);
        }

        $newsletterEmail = $this->module->newsletterEmail;
        if (isset(Yii::$app->params['newsletters.newsletterEmail']))
            $newsletterEmail = Yii::$app->params['newsletters.newsletterEmail'];

        $unlimitExecution = $this->module->unlimitExecution;
        if (isset(Yii::$app->params['newsletters.unlimitExecution']))
            $unlimitExecution = Yii::$app->params['newsletters.unlimitExecution'];

        if ($unlimitExecution) {
            $max_time = ini_get('max_execution_time');
            set_time_limit(0);
        }

        if (is_null($newsletterEmail) || !($validator->validate($newsletterEmail)))
            throw new InvalidConfigException(Yii::t('app/modules/newsletters', 'Param `newsletterEmail` must be a valid email adress.'));

        if ($mailer = Yii::$app->getMailer()) {

            if ($layouts = $model->getTemplateLayouts()) {

                if (isset($layouts['html']))
                    $mailer->htmlLayout = $layouts['html'];

                if (isset($layouts['text']))
                    $mailer->textLayout = $layouts['text'];

            } else {
                throw new InvalidConfigException('Layouts for mail should be defined');
            }

            if (!$views = $model->getTemplateViews())
                throw new InvalidConfigException('Views for mail should be defined');

            // Log activity
            if (
                class_exists('\wdmg\activity\models\Activity') &&
                $this->module->moduleLoaded('activity') &&
                isset(Yii::$app->activity)
            ) {
                Yii::$app->activity->set(
                    'Newsletter `' . $model->title . '` with ID `' . $model->id . '` has begun processed.',
                    $this->uniqueId . ":" . $this->action->id,
                    'info',
                    1
                );
            }

            foreach ($recipients as $data) {
                if (isset($data['email'])) {

                    $email_to = $data['email'];

                    $workflow = $model->getWorkflow();
                    if (isset($workflow['recipients'])) {

                        if (array_search($email_to, array_column($workflow['recipients'], null)))
                            continue;

                    }

                    if (isset($workflow['status'])) {
                        if ($workflow['status'] == "run") {
                            if ($compose = $mailer->compose($views, array_merge(['content' => $model->content], $data))) {

                                $compose->setFrom($newsletterEmail)->setTo($email_to)->setSubject($model->subject);

                                if (!is_null($model->reply_to)) {
                                    $compose->setReplyTo($model->reply_to);
                                }

                                if (is_null($views)) {
                                    $compose->setHtmlBody($model->content);
                                    $compose->setTextBody(StringHelper::stripTags($model->content, "", " "));
                                }

                                if ($compose->send())
                                    $model->updateWorkflow('recipients', [$email_to => true]);
                                else
                                    $model->updateWorkflow('recipients', [$email_to => false]);

                            }
                        } else {
                            break;
                        }
                    }
                }

                if (isset($workflow['recipients'])) {
                    if (count($workflow['recipients']) == count($recipients)) {
                        $model->updateWorkflow('status', 'complete');

                        // Log activity
                        if (
                            class_exists('\wdmg\activity\models\Activity') &&
                            $this->module->moduleLoaded('activity') &&
                            isset(Yii::$app->activity)
                        ) {
                            Yii::$app->activity->set(
                                'Newsletter `' . $model->title . '` with ID `' . $model->id . '` has been successfully processed.',
                                $this->uniqueId . ":" . $this->action->id,
                                'success',
                                1
                            );
                        }
                    }
                }
            }
        }

        if ($unlimitExecution) {
            set_time_limit(intval($max_time));
        }

        $this->redirect(['list/index']);
    }


    public function actionPause($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->updateWorkflow('status', 'pause');
        } else {
            $this->redirect(['list/index']);
        }

        //...

        $this->redirect(['list/index']);
    }

    public function actionRefresh($id)
    {
        $model = $this->findModel($id);
        $model->updateAttributes(['workflow' => Json::encode([])]);

        //...

        $this->redirect(['list/index']);
    }

    public function actionStop($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->updateWorkflow('status', 'stop');
        } else {
            $this->redirect(['list/index']);
        }

        //...

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
