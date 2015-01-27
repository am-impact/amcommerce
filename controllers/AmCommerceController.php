<?php
namespace Craft;

/**
 * Commerce controller
 */
class AmCommerceController extends BaseController
{
    public function actionIndex()
    {
        $variables = array();
        $this->renderTemplate('amCommerce/_index', $variables);
    }
}