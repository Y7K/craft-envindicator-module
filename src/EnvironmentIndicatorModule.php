<?php
/**
 * Environment Indicator module for Craft CMS 3.x
 *
 * Displays a label of the current environment, eg. Local, Staging or Production.
 *
 * @link      y7k.com
 * @copyright Copyright (c) 2018 Y7K
 */

namespace y7k\environmentindicatormodule;

use y7k\environmentindicatormodule\assetbundles\environmentindicatormodule\EnvironmentIndicatorModuleAssetCP;
use y7k\environmentindicatormodule\assetbundles\environmentindicatormodule\EnvironmentIndicatorModuleAssetFront;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Y7K
 * @package   EnvironmentIndicatorModule
 * @since     1.0.0
 *
 */
class EnvironmentIndicatorModule extends Module
{
    // Static Properties
    // =========================================================================
    public static $instance;

    // Public Methods
    // =========================================================================


    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('Y7K/EnvironmentIndicatorModule', $this->getBasePath());
        $this->controllerNamespace = 'y7k\environmentindicatormodule\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id . '*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'de',
                'basePath' => 'Y7K/EnvironmentIndicatorModule/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Base template directory
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function (RegisterTemplateRootsEvent $e) {
            if (is_dir($baseDir = $this->getBasePath() . DIRECTORY_SEPARATOR . 'templates')) {
                $e->roots[$this->id] = $baseDir;
            }
        });

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * Set our $instance static property to this class so that it can be accessed via
     * EnvironmentIndicatorModule::$instance
     *
     * Called after the module class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

        // Set Text
        $appEnv = Craft::$app->config->general->appEnv;
        $envText = '';

        switch ($appEnv) {
            case 'local':
                $envText = Craft::t('environment-indicator-module', 'Local') . ' 🐐';
                break;
            case 'develop':
                $envText = Craft::t('environment-indicator-module', 'Development') . ' 🏡';
                break;
            case 'staging':
                $envText = Craft::t('environment-indicator-module', 'Test-Environment') . ' 🍕';
                break;
            case 'production':
                $envText = Craft::t('environment-indicator-module', 'Live') . ' 🔥';
                break;
        }


        // Load our AssetBundle
        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (TemplateEvent $event) use ($appEnv, $envText) {
                try {

                    if (
                        in_array($appEnv, ['local', 'develop', 'staging', 'production'])
                        && Craft::$app->getRequest()->getIsCpRequest()
                    ) {

                        Craft::$app->getView()->registerJs('window.envText = "'.$envText.'"; window.appEnv = "'.$appEnv.'";', 3);
                        Craft::$app->getView()->registerAssetBundle(EnvironmentIndicatorModuleAssetCP::class);

                    } elseif (
                        in_array($appEnv, ['local', 'develop', 'staging'])
                        && Craft::$app->getRequest()->getIsSiteRequest()
                        && !Craft::$app->getSession()->has('hide_envindicator')
                        && !Craft::$app->getRequest()->getIsAjax()
                    ) {

                        Craft::$app->getView()->registerJs('window.envText = "'.$envText.'"; window.appEnv = "'.$appEnv.'";', 3);
                        Craft::$app->getView()->registerAssetBundle(EnvironmentIndicatorModuleAssetFront::class);
                    }

                } catch (InvalidConfigException $e) {
                    Craft::error(
                        'Error registering AssetBundle - ' . $e->getMessage(),
                        __METHOD__
                    );
                }
            }
        );

        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'y7k/environment-indicator-module/frontend-controller';
            }
        );

        // Register our CP routes
        // Event::on(
        //     UrlManager::class,
        //     UrlManager::EVENT_REGISTER_CP_URL_RULES,
        //     function (RegisterUrlRulesEvent $event) {
        //         $event->rules['cpActionTrigger1'] = 'modules/environment-indicator-module/frontend-controller/do-something';
        //     }
        // );

        /**
         * Logging in Craft involves using one of the following methods:
         *
         * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
         * Craft::info(): record a message that conveys some useful information.
         * Craft::warning(): record a warning message that indicates something unexpected has happened.
         * Craft::error(): record a fatal error that should be investigated as soon as possible.
         *
         * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
         *
         * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
         * the category to the method (prefixed with the fully qualified class name) where the constant appears.
         *
         * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
         * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
         *
         * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
         */

        Craft::info(
            Craft::t(
                'environment-indicator-module',
                '{name} module loaded',
                ['name' => 'Environment Indicator']
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================
}
