<?php

namespace wdmg\newsletters\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%newsletters}}".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $layouts
 * @property string $recipients
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
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ]
        ];

        if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $behaviors['blameable'] = [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ];
        }

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['title', 'content', 'layouts', 'recipients'], 'required'],
            [['title', 'layouts'], 'string', 'max' => 255],
            [['description', 'content', 'recipients', 'workflow', 'params'], 'string'],
            [['status'], 'boolean'],
            [['unique_token', 'created_at', 'updated_at'], 'safe'],
        ];

        if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $rules[] = [['created_by', 'updated_by'], 'required'];
        }

        return $rules;
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
            'content' => Yii::t('app/modules/newsletters', 'Content'),
            'layouts' => Yii::t('app/modules/newsletters', 'Layouts'),
            'recipients' => Yii::t('app/modules/newsletters', 'Recipients'),
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
     * @return object of \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasOne(\wdmg\users\models\Users::className(), ['id' => 'created_by']);
        else
            return null;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasMany(\wdmg\users\models\Users::className(), ['id' => 'created_by']);
        else
            return null;
    }
}
