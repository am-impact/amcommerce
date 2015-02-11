<?php
namespace Craft;

use Omnipay\Omnipay;

/**
 * AmCommerce - Install service
 */
class AmCommerce_InstallService extends BaseApplicationComponent
{
    /**
     * Install essential information.
     */
    public function install()
    {
        $this->_installPaymentGateways();
    }

    /**
     * Install payment gateways.
     */
    private function _installPaymentGateways()
    {
        $paymentGateways = array(
            'Buckaroo_Ideal',
            'Mollie',
            'PayPal_Pro',
            'Rabobank'
        );
        foreach ($paymentGateways as $name => $paymentGateway) {
            // Get gateway parameters
            $gateway = Omnipay::create($paymentGateway);
            $defaultParameters = $gateway->getDefaultParameters();

            // Set setting record
            $settingRecord = new AmCommerce_SettingRecord();
            $settingRecord->type = AmCommerceModel::SettingPaymentGateway;
            $settingRecord->name = $gateway->getName();
            $settingRecord->handle = $paymentGateway;
            $settingRecord->settings = $defaultParameters;

            // Save payment gateway!
            $settingRecord->save();
        }
    }
}