<?php
/**
 * Environment Indicator module for Craft CMS 3.x
 *
 * Displays a label of the current environment, eg. Local, Staging or Production.
 *
 * @link      y7k.com
 * @copyright Copyright (c) 2018 Y7K
 */

namespace Y7K\EnvironmentIndicatorModule\controllers;

use Y7K\EnvironmentIndicatorModule\EnvironmentIndicatorModule;

use Craft;
use craft\web\Controller;

/**
 * FrontendController Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Y7K
 * @package   EnvironmentIndicatorModule
 * @since     1.0.0
 */
class FrontendControllerController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['hide-label'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our module's index action URL,
     * e.g.: actions/environment-indicator-module/frontend-controller
     *
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionHideLabel()
    {
        $this->requireAcceptsJson();

        Craft::$app->session->set('hide_envindicator', true);

        return 'Label hidden.';
    }
}
