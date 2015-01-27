<?php
namespace Craft;

class AmCommerce_OrderModel extends BaseElementModel
{
    protected $elementType = AmCommerceModel::OrderElementType;

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * @inheritDoc BaseElementModel::getCpEditUrl()
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        return UrlHelper::getCpUrl('amcommerce/orders/edit/' . $this->id);
    }

    /**
     * Get available statuses.
     *
     * @return array
     */
    public function getStatuses()
    {
        $elementType = craft()->elements->getElementType($this->getElementType());
        return $elementType->getStatuses();
    }

    /**
     * Get default status.
     *
     * @return string
     */
    public function getDefaultStatus()
    {
        return AmCommerceModel::StatusPending;
    }

    /**
     * Set attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'id'                    => AttributeType::Number,
            'status'                => AttributeType::String,
            'orderNumber'           => AttributeType::Number,
            'totalPrice'            => AttributeType::Number,
            'deliveryType'          => AttributeType::String,
            'deliveryCompanyName'   => AttributeType::String,
            'deliveryFirstName'     => AttributeType::String,
            'deliveryLastName'      => AttributeType::String,
            'deliveryAddress'       => AttributeType::String,
            'deliveryZipCode'       => AttributeType::String,
            'deliveryCity'          => AttributeType::String,
            'deliveryCountry'       => AttributeType::String,
            'invoiceCompanyName'    => AttributeType::String,
            'invoiceFirstName'      => AttributeType::String,
            'invoiceLastName'       => AttributeType::String,
            'invoiceAddress'        => AttributeType::String,
            'invoiceZipCode'        => AttributeType::String,
            'invoiceCity'           => AttributeType::String,
            'invoiceCountry'        => AttributeType::String,
            'telephoneNumber'       => AttributeType::String,
            'mobileNumber'          => AttributeType::String,
            'emailAddress'          => AttributeType::String
        ));
    }
}