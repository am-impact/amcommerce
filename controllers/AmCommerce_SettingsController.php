<?php
namespace Craft;

/**
 * AmCommerce - Settings controller
 */
class AmCommerce_SettingsController extends BaseController
{
    /**
     * Settings index.
     */
    public function actionIndex()
    {
        $variables = array();
        $this->renderTemplate('amCommerce/settings/_index', $variables);
    }

    /**
     * Show payment gateways' settings.
     */
    public function actionPaymentGateways()
    {
        $variables = array(
            'gateways' => craft()->amCommerce_settings->getAllSettingsByType(AmCommerceModel::SettingPaymentGateway)
        );
        $this->renderTemplate('amCommerce/settings/_gateways', $variables);
    }

    /**
     * Saves payment gateways settings.
     */
    public function actionSavePaymentGateways()
    {
        $this->requirePostRequest();

        $success = true;

        // Get all payment gateways
        $paymentGateways = craft()->amCommerce_settings->getAllSettingsByType(AmCommerceModel::SettingPaymentGateway);

        // Save settings for each payment gateway
        foreach ($paymentGateways as $paymentGateway) {
            // Find new settings
            $newSettings = craft()->request->getPost($paymentGateway->handle . '_settings', false);
            if ($newSettings) {
                $paymentGateway->settings = $newSettings;
                $paymentGateway->enabled = craft()->request->getPost($paymentGateway->handle . '_enabled', false);
                if(! craft()->amCommerce_settings->saveSettings($paymentGateway)) {
                    $success = false;
                }
            }
        }

        if ($success) {
            craft()->userSession->setNotice(Craft::t('Settings saved.'));
        }
        else {
            craft()->userSession->setError(Craft::t('Couldn’t save settings.'));
        }
    }
}