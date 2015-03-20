<?php
namespace Craft;

class AmCommerceVariable
{
    /**
     * Get the Plugin's name.
     *
     * @example {{ craft.amCommerce.name }}
     * @return string
     */
    public function getName()
    {
        $plugin = craft()->plugins->getPlugin('amcommerce');
        return $plugin->getName();
    }

    /**
     * Get all enabled payment gateways.
     *
     * @return array
     */
    public function getAvailablePaymentGateways()
    {
        return craft()->amCommerce_payments->getAvailablePaymentGateways();
    }

    /**
     * Get all orders.
     *
     * @return array
     */
    public function getOrders()
    {
        return craft()->amCommerce_orders->getAllOrders();
    }
}