<?php

namespace wdmg\newsletters\models;

use wdmg\helpers\ArrayHelper;
use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\validators\EmailValidator;
use yii\helpers\FileHelper;
use wdmg\validators\JsonValidator;
use wdmg\validators\EmailsValidator;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%newsletters}}".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $subject
 * @property string $content
 * @property string $layouts
 * @property string $views
 * @property string $recipients
 * @property string $reply_to
 * @property string $unique_token
 * @property integer $status
 * @property string $workflow
 * @property string $params
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Newsletters extends ActiveRecord
{
    const NEWSLETTERS_STATUS_DISABLED = 0;
    const NEWSLETTERS_STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%newsletters}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' =>  [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['title', 'subject', 'content', 'layouts', 'recipients'], 'required'],
            [['title', 'subject', 'layouts', 'reply_to', 'views'], 'string', 'max' => 255],
            [['description', 'content', 'recipients', 'workflow', 'params'], 'string'],
            [['recipients'], JsonValidator::class, 'message' => Yii::t('app/modules/newsletters', 'The value of field `{attribute}` must be a valid JSON, error: {error}.')],
            [['recipients'], 'validateRecipients'],
            [['reply_to'], EmailsValidator::class],
            [['status'], 'boolean'],
            [['unique_token', 'created_at', 'updated_at'], 'safe'],
        ];

        if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $rules[] = [['created_by', 'updated_by'], 'required'];
        }

        return $rules;
    }

    public function validateRecipients($attribute, $params)
    {
        $validator = new EmailsValidator();
        $validator->allowName = true;

        $data = \yii\helpers\Json::decode($this->recipients);
        if ($emails = array_values($data)) {
            $validator->validate($emails);
        }
    }

    public function beforeValidate()
    {

        if (is_array($this->recipients))
            $this->recipients = \yii\helpers\Json::encode($this->recipients);

        if (is_array($this->workflow))
            $this->workflow = \yii\helpers\Json::encode($this->workflow);

        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {

        $this->unique_token = Yii::$app->security->generateRandomString(32);
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->unique_token = Yii::$app->security->generateRandomString(32);
            }
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {

        if (is_string($this->workflow)) {
            if (!($this->workflow = Json::decode($this->workflow)))
                $this->workflow = [];
        }
        parent::afterFind();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/newsletters', 'ID'),
            'title' => Yii::t('app/modules/newsletters', 'Title'),
            'description' => Yii::t('app/modules/newsletters', 'Description'),
            'subject' => Yii::t('app/modules/newsletters', 'Subject'),
            'content' => Yii::t('app/modules/newsletters', 'Content'),
            'layouts' => Yii::t('app/modules/newsletters', 'Layouts'),
            'views' => Yii::t('app/modules/newsletters', 'Views'),
            'recipients' => Yii::t('app/modules/newsletters', 'Recipients'),
            'reply_to' => Yii::t('app/modules/newsletters', 'Reply to'),
            'unique_token' => Yii::t('app/modules/newsletters', 'Unique token'),
            'status' => Yii::t('app/modules/newsletters', 'Status'),
            'workflow' => Yii::t('app/modules/newsletters', 'Workflow'),
            'params' => Yii::t('app/modules/newsletters', 'Params'),
            'created_at' => Yii::t('app/modules/newsletters', 'Created at'),
            'created_by' => Yii::t('app/modules/newsletters', 'Created by'),
            'updated_at' => Yii::t('app/modules/newsletters', 'Updated at'),
            'updated_by' => Yii::t('app/modules/newsletters', 'Updated by'),
        ];
    }

    /**
     * @return array of list
     */
    public function getStatusesList($allStatuses = false)
    {
        if($allStatuses)
            $list[] = [
                '*' => Yii::t('app/modules/newsletters', 'All statuses')
            ];

        $list[] = [
            self::NEWSLETTERS_STATUS_DISABLED => Yii::t('app/modules/newsletters', 'Disabled'),
            self::NEWSLETTERS_STATUS_ACTIVE => Yii::t('app/modules/newsletters', 'Active')
        ];

        return $list;
    }

    /**
     * @return array of list
     */

    /**
     * @return array of list
     */
    public function getLayouts($notSelected = false)
    {
        $list = [];

        if($notSelected)
            $list[] = [
                '*' => Yii::t('app/modules/newsletters', 'Not selected')
            ];

        foreach (Yii::$app->extensions as $extension) {
            $aliases = array_keys($extension['alias']);
            foreach ($aliases as $alias) {
                if (is_dir(Yii::getAlias($alias . '/mail/layouts'))) {
                    $files = FileHelper::findFiles(Yii::getAlias($alias . '/mail/layouts'), ['only' => ['html.php', 'text.php'], 'recursive' => true]);
                    foreach ($files as $file) {
                        $filename = str_replace('html.php', '', $file);
                        $filename = str_replace('text.php', '', $filename);
                        $filename = str_replace(Yii::getAlias($alias . '/mail'), $alias . '/mail', $filename);
                        $list[$filename] = $filename.'<span class="text-muted">[html|text]</span>'.'.php';
                    }
                }
            }
        }

        return array_unique($list);
    }

    /**
     * @return array of list
     */
    public function getViews($notSelected = false)
    {
        $list = [];

        if($notSelected)
            $list[] = [
                '*' => Yii::t('app/modules/newsletters', 'Not selected')
            ];

        foreach (Yii::$app->extensions as $extension) {
            $aliases = array_keys($extension['alias']);
            foreach ($aliases as $alias) {
                if (is_dir(Yii::getAlias($alias . '/mail'))) {
                    $files = FileHelper::findFiles(Yii::getAlias($alias . '/mail'), ['only' => ['*-html.php', '*-text.php'], 'recursive' => true]);
                    foreach ($files as $file) {
                        $filename = str_replace('-html.php', '', $file);
                        $filename = str_replace('-text.php', '', $filename);
                        $filename = str_replace(Yii::getAlias($alias . '/mail'), $alias . '/mail', $filename);
                        $list[$filename] = $filename.'-'.'<span class="text-muted">[html|text]</span>'.'.php';
                    }
                }
            }
        }

        return array_unique($list);
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getSubscribers($cond = null, $select = ['id', 'email'], $asArray = false)
    {
        if (class_exists('\wdmg\subscribers\models\Subscribers')) {
            if ($cond) {

                $list = new \wdmg\subscribers\models\Subscribers();

                if ($asArray)
                    return $list::find()->select($select)->where($cond)->asArray()->indexBy('id')->all();
                else
                    return $list::find()->select($select)->where($cond)->all();

            } else {

                $list = new \wdmg\subscribers\models\Subscribers();

                if ($asArray)
                    return $list::find()->select($select)->asArray()->indexBy('id')->all();
                else
                    return $list::find()->select($select)->all();
            }

        } else {
            return null;
        }
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getSubscribersFromList($cond = null, $asArray = true)
    {
        if (class_exists('\wdmg\subscribers\models\Subscribers')) {
            if ($cond) {

                $list = new \wdmg\subscribers\models\Subscribers();
                if ($asArray)
                    return $list::find()->select(['id', 'email', 'name'])->where($cond)->asArray()->indexBy('id')->all();
                else
                    return $list::find()->where($cond)->indexBy('id')->all();

            } else {

                $list = new \wdmg\subscribers\models\Subscribers();
                if ($asArray)
                    return $list::find()->select(['id', 'email', 'name'])->asArray()->indexBy('id')->all();
                else
                    return $list::find()->indexBy('id')->all();

            }
        } else {
            return null;
        }
    }

    /**
     * @return array of recipients
     */
    public function getRecipients()
    {
        $recipients = [];
        $validator = new EmailValidator();
        $data = \yii\helpers\Json::decode($this->recipients);
        $validator->allowName = true;
        foreach ($data as $key => $item) {
            if (preg_match('/email_id:(\d)/', $key)) {
                preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $item, $matches);
                if ($email = $matches[0]) {
                    if ($validator->validate($email)) {

                        $isExist = false;
                        foreach ($recipients as $recipient) {
                            if ($recipient['email'] == $email)
                                $isExist = true;
                        }

                        if (!$isExist)
                            $recipients[] = ['email' => $email];
                        else
                            continue;

                    }
                }
            } else if (preg_match('/list_id:(\d)/', $key, $match)) {
                if ($list = $this->getSubscribersFromList(['list_id' => intval($match[1]), 'status' => \wdmg\subscribers\models\Subscribers::SUBSCRIBERS_STATUS_ACTIVE], false)) {
                    foreach ($list as $key => $item) {
                        if ($validator->validate($item->email)) {

                            $isExist = false;
                            foreach ($recipients as $recipient) {
                                if ($recipient['email'] == $item->email)
                                    $isExist = true;
                            }

                            if (!$isExist) {
                                $recipients[] = [
                                    'email' => $item->email,
                                    'name' => (isset($item->name)) ? $item->name : null,
                                    'user_id' => (isset($item->user_id)) ? $item->user_id : null,
                                    'username' => (isset($item->user)) ? $item->user->username : null,
                                    'unsubscribe_url' => (isset($item->unsubscribe_link)) ? $item->unsubscribe_link : null,
                                    'manage_url' => (isset($item->manage_link)) ? $item->manage_link : null
                                ];
                            } else {
                                continue;
                            }
                        }
                    }
                }
            } else {
                preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $item, $matches);
                if ($email = $matches[0]) {
                    if ($validator->validate($email)) {

                        $isExist = false;
                        foreach ($recipients as $recipient) {
                            if ($recipient['email'] == $email)
                                $isExist = true;
                        }

                        if (!$isExist)
                            $recipients[] = ['email' => $email];
                        else
                            continue;

                    }
                }
            }
        }

        return $recipients;
    }

    /**
     * @return integer
     */
    public function getSubscribersCount($cond = null)
    {
        if (class_exists('\wdmg\subscribers\models\Subscribers')) {
            if ($cond) {
                $list = new \wdmg\subscribers\models\Subscribers();
                return $list::find()->select('COUNT(*) AS count')->where($cond)->count();
            } else {
                $list = new \wdmg\subscribers\models\Subscribers();
                return $list::find()->select('COUNT(*) AS count')->count();
            }

        } else {
            return null;
        }
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getSubscribersList($cond = null, $select = ['id', 'title'], $asArray = false)
    {
        if (class_exists('\wdmg\subscribers\models\SubscribersList')) {
            if ($cond) {

                $list = new \wdmg\subscribers\models\SubscribersList();

                if ($asArray)
                    return $list::find()->select($select)->where($cond)->asArray()->indexBy('id')->all();
                else
                    return $list::find()->select($select)->where($cond)->all();

            } else {

                $list = new \wdmg\subscribers\models\SubscribersList();

                if ($asArray)
                    return $list::find()->select($select)->asArray()->indexBy('id')->all();
                else
                    return $list::find()->select($select)->all();
            }

        } else {
            return null;
        }
    }

    /**
     * @return array or null
     */
    public function getTemplateLayouts()
    {
        $layouts = null;
        if (!is_null($this->layouts)) {
            if (is_dir(FileHelper::normalizePath(Yii::getAlias($this->layouts)))) {

                if (file_exists(FileHelper::normalizePath(Yii::getAlias($this->layouts.'/html.php'))))
                    $layouts['html'] = FileHelper::normalizePath($this->layouts . '/html');

                if (file_exists(FileHelper::normalizePath(Yii::getAlias($this->layouts.'/text.php'))))
                    $layouts['text'] = FileHelper::normalizePath($this->layouts . '/text');
            }
        }

        return $layouts;
    }

    /**
     * @return array or null
     */
    public function getTemplateViews()
    {
        $views = null;
        if (!is_null(FileHelper::normalizePath(Yii::getAlias($this->views)))) {

            if (file_exists(FileHelper::normalizePath(Yii::getAlias($this->views.'-html.php'))))
                $views['html'] = FileHelper::normalizePath($this->views.'-html');
            elseif (file_exists(FileHelper::normalizePath(Yii::getAlias($this->views.'/html.php'))))
                $views['html'] = FileHelper::normalizePath($this->views.'/html');

            if (file_exists(FileHelper::normalizePath(Yii::getAlias($this->views.'-text.php'))))
                $views['text'] = FileHelper::normalizePath($this->views.'-text');
            elseif (file_exists(FileHelper::normalizePath(Yii::getAlias($this->views.'/text.php'))))
                $views['text'] = FileHelper::normalizePath($this->views.'/text');

        }

        return $views;
    }


    public function getWorkflow()
    {
        if (is_string($this->workflow) || !is_array($this->workflow)) {
            if (!($this->workflow = Json::decode($this->workflow)))
                $this->workflow = [];
        }
        return $this->workflow;
    }

    public function updateWorkflow($key, $param)
    {
        $workflow = $this->getWorkflow();
        if (is_array($workflow)) {
            if (is_array($param)) {
                foreach ($param as $name => $value) {
                    $workflow[$key][$name] = $value;
                }
            } else {
                $workflow[$key] = $param;
            }
            $this->updateAttributes(['workflow' => Json::encode($workflow)]);
        }
    }


    /**
     * @return
     */
    public function getProgress()
    {
        if (is_array($this->workflow)) {

            if (isset($this->workflow['recipients']) && isset($this->workflow['count'])) {
                if ((count($this->workflow['recipients']) >= 0) && intval($this->workflow['count']) > 0)
                    return (count($this->workflow['recipients']) / intval($this->workflow['count']) * 100);
            }

        }
        return 0;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if (class_exists('\wdmg\users\models\Users'))
            return $this->hasOne(\wdmg\users\models\Users::class, ['id' => 'created_by']);
        else
            return null;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        if (class_exists('\wdmg\users\models\Users'))
            return $this->hasMany(\wdmg\users\models\Users::class, ['id' => 'created_by']);
        else
            return null;
    }
}
