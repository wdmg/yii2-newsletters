<?php

namespace wdmg\newsletters;

/**
 * Yii2 Newsletters
 *
 * @category        Module
 * @version         1.0.6
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-newsletters
 * @copyright       Copyright (c) 2019 - 2020 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use wdmg\base\BaseModule;

/**
 * Newsletters module definition class
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'wdmg\newsletters\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = "list/index";

    /**
     * @var string, the name of module
     */
    public $name = "Newsletters";

    /**
     * @var string, the description of module
     */
    public $description = "Newsletter manager";

    /**
     * @var string, the description of module
     */
    public $newsletterEmail = "no-reply@example.com";

    /**
     * @var string, the flag for unlimit execution time
     */
    public $unlimitExecution = false;

    /**
     * @var string the module version
     */
    private $version = "1.0.6";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 10;

    /**
     * @var array, mail variables
     */
    private $mailVars = [
        '{welcome}' => 'Return the welcome message',
        '{site.name}' => 'Return site name',
        '{site.url}' => 'Return site URL with scheme',
        '{app.name}' => 'Return name of app',
        '{domain}' => 'Return domain name',
        '{date}' => 'Return current date',
        '{time}' => 'Return current time',
        '{datetime}' => 'Return current date and time',
        '{signature}' => 'Return default signature',
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Set version of current module
        $this->setVersion($this->version);

        // Set priority of current module
        $this->setPriority($this->priority);

    }

    /**
     * {@inheritdoc}
     */
    public function dashboardNavItems($createLink = false)
    {
        $items = [
            'label' => $this->name,
            'url' => [$this->routePrefix . '/'. $this->id],
            'icon' => 'fa fa-fw fa-rocket',
            'active' => in_array(\Yii::$app->controller->module->id, [$this->id])
        ];
        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);

        if (isset(Yii::$app->params["newsletters.newsletterEmail"]))
            $this->newsletterEmail = Yii::$app->params["newsletters.newsletterEmail"];

        if (isset(Yii::$app->params["newsletters.unlimitExecution"]))
            $this->unlimitExecution = Yii::$app->params["newsletters.unlimitExecution"];
    }

    /**
     * Return the mail variables
     * @return array, mail variables
     */
    public function getMailVars()
    {
        return $this->mailVars;
    }
}