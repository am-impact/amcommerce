<?php
namespace Craft;

use Omnipay\Omnipay;

/**
 * AmCommerce - Payments service
 */
class AmCommerce_PaymentsService extends BaseApplicationComponent
{
    /**
     * Get all payments.
     *
     * @param int $orderId
     *
     * @return AmCommerce_PaymentModel|null
     */
    public function getPaymentsByOrderId($orderId)
    {
        $paymentRecords = AmCommerce_PaymentRecord::model()->findByAttributes(array('orderId' => $orderId));
        if ($paymentRecords) {
            return AmCommerce_PaymentModel::populateModels($paymentRecords);
        }
        return null;
    }

    /**
     * Get all enabled payment gateways.
     *
     * @return array|null
     */
    public function getAvailablePaymentGateways()
    {
        $gateways = craft()->amCommerce_settings->getAllSettingsByType(AmCommerceModel::SettingPaymentGateway, true);
        return $gateways ?: null;
    }

    /**
     * Start a payment by a given gateway.
     *
     * @param type $gatewayHandle
     *
     * @throws Exception
     */
    public function startPaymentForGateway($handle)
    {
        $gateway = Omnipay::create('Rabobank');
        $gateway->setMerchantId('002020000000001');
        $gateway->setKeyVersion(1);
        $gateway->setSecretKey('002020000000001_KEY1');
        $gateway->setTestMode(true);

        $parameters = array(
            'transactionId' => 'AMCOMMERCE1',
            'currency' => 'EUR',
            'amount' => '10.00',
            'returnUrl' => 'http://localhost'
        );
        $response = $gateway->purchase($parameters)->send();

        if ($response->isSuccessful()) {
            // payment was successful: update database
            print_r($response);
        } elseif ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } else {
            // payment failed: display message to customer
            throw new Exception($response->getMessage());
        }
    }

    /**
     * Saves a payment.
     *
     * @param AmCommerce_PaymentModel $payment
     *
     * @throws Exception
     * @return bool|AmCommerce_PaymentModel
     */
    public function savePayment(AmCommerce_PaymentModel $payment)
    {
        // Payment data
        if ($payment->id) {
            $paymentRecord = AmCommerce_PaymentRecord::model()->findById($payment->id);

            if (! $paymentRecord) {
                throw new Exception(Craft::t('No payment exists with the ID “{id}”.', array('id' => $payment->id)));
            }
        }
        else {
            $paymentRecord = new AmCommerce_PaymentRecord();
        }

        // Set attributes
        $paymentRecord->setAttributes($payment->getAttributes());

        // Validate
        $paymentRecord->validate();
        $payment->addErrors($paymentRecord->getErrors());

        // Save payment
        if (! $payment->hasErrors()) {
            return $paymentRecord->save();
        }
        return false;
    }
}