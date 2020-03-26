[![Yii2](https://img.shields.io/badge/required-Yii2_v2.0.33-blue.svg)](https://packagist.org/packages/yiisoft/yii2)
[![Github all releases](https://img.shields.io/github/downloads/wdmg/yii2-newsletters/total.svg)](https://GitHub.com/wdmg/yii2-newsletters/releases/)
![Progress](https://img.shields.io/badge/progress-in_development-red.svg)
[![GitHub license](https://img.shields.io/github/license/wdmg/yii2-newsletters.svg)](https://github.com/wdmg/yii2-newsletters/blob/master/LICENSE)
![GitHub release](https://img.shields.io/github/release/wdmg/yii2-newsletters/all.svg)

# Yii2 newsletters
newsletters manager for Yii2

# Requirements 
* PHP 5.6 or higher
* Yii2 v.2.0.33 and newest
* [Yii2 Base](https://github.com/wdmg/yii2-base) module (required)
* [Yii2 Mailer](https://github.com/wdmg/yii2-mailer) module (optionality)
* [Yii2 Options](https://github.com/wdmg/yii2-options) module (optionality)
* [Yii2 Editor](https://github.com/wdmg/yii2-editor) module (required)
* [Yii2 TagsInput](https://github.com/wdmg/yii2-tagsinput) widget (required)
* [Yii2 SelectInput](https://github.com/wdmg/yii2-selectinput) widget (required)
* [Yii2 Validators](https://github.com/wdmg/yii2-validators) widget (required)

# Installation
To install the module, run the following command in the console:

`$ composer require "wdmg/yii2-newsletters"`

After configure db connection, run the following command in the console:

`$ php yii newsletters/init`

And select the operation you want to perform:
  1) Apply all module migrations
  2) Revert all module migrations

# Migrations
In any case, you can execute the migration and create the initial data, run the following command in the console:

`$ php yii migrate --migrationPath=@vendor/wdmg/yii2-newsletters/migrations`

# Configure
To add a module to the project, add the following data in your configuration file:

    'modules' => [
        ...
        'newsletters' => [
            'class' => 'wdmg\newsletters\Module',
            'routePrefix' => 'admin'
        ],
        ...
    ],


# Routing
Use the `Module::dashboardNavItems()` method of the module to generate a navigation items list for NavBar, like this:

    <?php
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
            'label' => 'Modules',
            'items' => [
                Yii::$app->getModule('newsletters')->dashboardNavItems(),
                ...
            ]
        ]);
    ?>

# Status and version [in progress development]
* v.1.0.7 - Up to date dependencies
* v.1.0.6 - Fixed count of emails in views and models
* v.1.0.5 - Refactoring. Migrations bugfix
* v.1.0.4 - Fixed bug with save workflow and recipients list