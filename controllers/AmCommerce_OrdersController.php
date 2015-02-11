<?php
namespace Craft;

/**
 * AmCommerce - Orders controller
 */
class AmCommerce_OrdersController extends BaseController
{
    /**
     * Orders index.
     */
    public function actionIndex()
    {
        $variables = array();
        $this->renderTemplate('amCommerce/orders/_index', $variables);
    }

    /**
     * Create or edit an order.
     *
     * @param array $variables
     */
    public function actionEditOrder(array $variables = array())
    {
        // Get order if available
        if (empty($variables['order'])) {
            if (! empty($variables['orderId'])) {
                $variables['order'] = craft()->amCommerce_orders->getOrderById($variables['orderId']);

                if (! $variables['order']) {
                    throw new HttpException(404);
                }
            }
            else {
                $variables['order'] = new AmCommerce_OrderModel();
            }
        }

        // Whether this is a new order
        $variables['isNewOrder'] = empty($variables['orderId']);

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'amcommerce/orders/edit/{id}';

        $this->renderTemplate('amCommerce/orders/_edit', $variables);
    }

    /**
     * Saves an order.
     */
    public function actionSaveOrder()
    {
        $this->requirePostRequest();

        // Get order if available
        $orderId = craft()->request->getPost('orderId');
        if ($orderId) {
            $order = craft()->amCommerce_orders->getOrderById($orderId);

            if (! $order) {
                throw new Exception(Craft::t('No order exists with the ID “{id}”.', array('id' => $orderId)));
            }
        }
        else {
            $order = new AmCommerce_OrderModel();
        }

        // Set order attributes
        $order->status              = craft()->request->getPost('status', $order->status);
        $order->orderNumber         = craft()->request->getPost('orderNumber', $order->orderNumber);
        $order->totalPrice          = craft()->request->getPost('totalPrice', $order->totalPrice);
        $order->deliveryType        = craft()->request->getPost('deliveryType', $order->deliveryType);
        $order->deliveryCompanyName = craft()->request->getPost('deliveryCompanyName', $order->deliveryCompanyName);
        $order->deliveryFirstName   = craft()->request->getPost('deliveryFirstName', $order->deliveryFirstName);
        $order->deliveryLastName    = craft()->request->getPost('deliveryLastName', $order->deliveryLastName);
        $order->deliveryAddress     = craft()->request->getPost('deliveryAddress', $order->deliveryAddress);
        $order->deliveryZipCode     = craft()->request->getPost('deliveryZipCode', $order->deliveryZipCode);
        $order->deliveryCity        = craft()->request->getPost('deliveryCity', $order->deliveryCity);
        $order->deliveryCountry     = craft()->request->getPost('deliveryCountry', $order->deliveryCountry);
        $order->invoiceCompanyName  = craft()->request->getPost('invoiceCompanyName', $order->invoiceCompanyName);
        $order->invoiceFirstName    = craft()->request->getPost('invoiceFirstName', $order->invoiceFirstName);
        $order->invoiceLastName     = craft()->request->getPost('invoiceLastName', $order->invoiceLastName);
        $order->invoiceAddress      = craft()->request->getPost('invoiceAddress', $order->invoiceAddress);
        $order->invoiceZipCode      = craft()->request->getPost('invoiceZipCode', $order->invoiceZipCode);
        $order->invoiceCity         = craft()->request->getPost('invoiceCity', $order->invoiceCity);
        $order->invoiceCountry      = craft()->request->getPost('invoiceCountry', $order->invoiceCountry);
        $order->telephoneNumber     = craft()->request->getPost('telephoneNumber', $order->telephoneNumber);
        $order->mobileNumber        = craft()->request->getPost('mobileNumber', $order->mobileNumber);
        $order->emailAddress        = craft()->request->getPost('emailAddress', $order->emailAddress);

        // Set custom fields in fieldLayout
        $order->setContentFromPost('fields');

        // Save order
        if (craft()->amCommerce_orders->saveOrder($order)) {
            craft()->userSession->setNotice(Craft::t('Order saved.'));
            $this->redirectToPostedUrl($order);
        }
        else {
            craft()->userSession->setError(Craft::t('Couldn’t save order.'));

            // Send the order back to the template
            craft()->urlManager->setRouteVariables(array(
                'order' => $order
            ));
        }
    }
}