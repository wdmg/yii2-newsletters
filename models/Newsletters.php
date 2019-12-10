<?php

namespace wdmg\newsletters\models;

use wdmg\helpers\ArrayHelper;
use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\validators\EmailValidator;
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
        $error = false;
        $validator = new EmailsValidator();
        $validator->allowName = true;

        $data = \yii\helpers\Json::decode($this->recipients);
        if ($emails = array_values($data)) {
            $validator->validate($emails);


            /*foreach ($data as $key => $item) {
                if (preg_match('/email_id:(\d)/', $key)) {
                    preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $item, $matches);
                    if ($email = $matches[0]) {
                        if (!$error && !$validator->validate(trim($email))) {
                            $error = true;
                        }
                    }
                } else if (preg_match('/list_id:(\d)/', $key, $match)) {
                    if ($list = $this->getSubscribersFromList(['list_id' => intval($match[1])])) {
                        foreach ($list as $key => $item) {
                            if (!$error && !$validator->validate(trim($item['email']))) {
                                $error = true;
                            }
                        }
                    }
                } else {
                    preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $item, $matches);
                    if ($email = $matches[0]) {
                        if (!$error && !$validator->validate(trim($email))) {
                            $error = true;
                        }
                    }
                }
            }*/
        }
/*
        if ($error) {
            $this->addError($attribute, Yii::t('app/modules/newsletters', 'One or more recipients are not a valid email address.'));
        }*/
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
                    $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias($alias . '/mail/layouts'), ['only' => ['html.php', 'text.php'], 'recursive' => true]);
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
                    $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias($alias . '/mail'), ['only' => ['*-html.php', '*-text.php'], 'recursive' => true]);
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
    public function getSubscribersFromList($cond = null)
    {
        if (class_exists('\wdmg\subscribers\models\Subscribers')) {
            if ($cond) {
                $list = new \wdmg\subscribers\models\Subscribers();
                return $list::find()->select(['id', 'email'])->where($cond)->asArray()->indexBy('id')->all();
            } else {
                $list = new \wdmg\subscribers\models\Subscribers();
                return $list::find()->select(['id', 'email'])->asArray()->indexBy('id')->all();
            }
        } else {
            return null;
        }
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getRecipients()
    {
        $emails = [];
        $validator = new EmailValidator();
        $data = \yii\helpers\Json::decode($this->recipients);
        $validator->allowName = true;
        foreach ($data as $key => $item) {
            if (preg_match('/email_id:(\d)/', $key)) {
                preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $item, $matches);
                if ($email = $matches[0]) {
                    if ($validator->validate($email)) {
                        $emails[] = $email;
                    }
                }
            } else if (preg_match('/list_id:(\d)/', $key, $match)) {
                if ($list = $this->getSubscribersFromList(['list_id' => intval($match[1])])) {
                    foreach ($list as $key => $item) {
                        if ($validator->validate($item['email'])) {
                            $emails[] = $item['email'];
                        }
                    }
                }
            } else {
                preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $item, $matches);
                if ($email = $matches[0]) {
                    if ($validator->validate($email)) {
                        $emails[] = $email;
                    }
                }
            }
        }

        return $emails;
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
