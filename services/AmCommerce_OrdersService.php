<?php
namespace Craft;

/**
 * AmCommerce - Orders service
 */
class AmCommerce_OrdersService extends BaseApplicationComponent
{
    /**
     * Get an order by its ID.
     *
     * @param int $orderId
     *
     * @return AmCommerce_OrderModel|null
     */
    public function getOrderById($orderId)
    {
        return craft()->elements->getElementById($orderId, AmCommerceModel::OrderElementType);
    }

    /**
     * Get all orders.
     *
     * @return array
     */
    public function getAllOrders()
    {
        $criteria = craft()->elements->getCriteria(AmCommerceModel::OrderElementType);
        $criteria->status = null;
        return $criteria->find();
    }

    /**
     * Saves a new or existing order.
     *
     * @param AmCommerce_OrderModel $order
     *
     * @throws Exception
     * @return bool
     */
    public function saveOrder(AmCommerce_OrderModel $order)
    {
        $isNewOrder = ! $order->id;

        // Order data
        if (! $isNewOrder) {
            $orderRecord = AmCommerce_OrderRecord::model()->findById($order->id);

            if (! $orderRecord) {
                throw new Exception(Craft::t('No order exists with the ID “{id}”.', array('id' => $order->id)));
            }
        }
        else {
            $orderRecord = new AmCommerce_OrderRecord();
        }

        // Set attributes
        $orderRecord->status              = $order->status;
        $orderRecord->orderNumber         = $order->orderNumber;
        $orderRecord->totalPrice          = $order->totalPrice;
        $orderRecord->deliveryType        = $order->deliveryType;
        $orderRecord->deliveryCompanyName = $order->deliveryCompanyName;
        $orderRecord->deliveryFirstName   = $order->deliveryFirstName;
        $orderRecord->deliveryLastName    = $order->deliveryLastName;
        $orderRecord->deliveryAddress     = $order->deliveryAddress;
        $orderRecord->deliveryZipCode     = $order->deliveryZipCode;
        $orderRecord->deliveryCity        = $order->deliveryCity;
        $orderRecord->deliveryCountry     = $order->deliveryCountry;
        $orderRecord->invoiceCompanyName  = $order->invoiceCompanyName;
        $orderRecord->invoiceFirstName    = $order->invoiceFirstName;
        $orderRecord->invoiceLastName     = $order->invoiceLastName;
        $orderRecord->invoiceAddress      = $order->invoiceAddress;
        $orderRecord->invoiceZipCode      = $order->invoiceZipCode;
        $orderRecord->invoiceCity         = $order->invoiceCity;
        $orderRecord->invoiceCountry      = $order->invoiceCountry;
        $orderRecord->telephoneNumber     = $order->telephoneNumber;
        $orderRecord->mobileNumber        = $order->mobileNumber;
        $orderRecord->emailAddress        = $order->emailAddress;

        // Validate
        $orderRecord->validate();
        $order->addErrors($orderRecord->getErrors());

        if ($order->hasErrors()) {
            return false;
        }

        $success = false;
        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;

        try {
            // Fire an 'onBeforeSaveOrder' event
            $event = new Event($this, array(
                'order'      => $order,
                'isNewOrder' => $isNewOrder
            ));

            $this->onBeforeSaveOrder($event);

            // Is the event giving us the go-ahead?
            if ($event->performAction) {
                // Save the element
                $success = craft()->elements->saveElement($order);

                // If it didn't work, rollback the transaction in case something changed in onBeforeSaveOrder
                if (! $success) {
                    if ($transaction !== null) {
                        $transaction->rollback();
                    }
                    return false;
                }

                // Now that we have an element ID, save it on the other stuff
                if ($isNewOrder) {
                    $orderRecord->id = $order->id;
                }

                // Save the actual order row
                $orderRecord->save(false); // Skip validation now
            }
            else {
                $success = false;
            }

            // Commit the transaction regardless of whether we saved the order, in case something changed
            // in onBeforeSaveOrder
            if ($transaction !== null) {
                $transaction->commit();
            }
        }
        catch (\Exception $e) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $e;
        }

        if ($success) {
            // Fire an 'onSaveOrder' event
            $this->onSaveOrder(new Event($this, array(
                'order'      => $order,
                'isNewOrder' => $isNewOrder
            )));
        }

        return $success;
    }

    // Events
    // -------------------------------------------------------------------------

    /**
     * Fires an 'onBeforeSaveOrder' event.
     *
     * @param Event $event
     *
     * @return null
     */
    public function onBeforeSaveOrder(Event $event)
    {
        $this->raiseEvent('onBeforeSaveOrder', $event);
    }

    /**
     * Fires an 'onSaveOrder' event.
     *
     * @param Event $event
     *
     * @return null
     */
    public function onSaveOrder(Event $event)
    {
        $this->raiseEvent('onSaveOrder', $event);
    }
}