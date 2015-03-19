<?php
namespace Craft;

class AmCommerce_OrderRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'amcommerce_orders';
    }

    protected function defineAttributes()
    {
        return array(
            'status'                => array(AttributeType::String, 'label' => Craft::t('Order Status'), 'required' => true, 'default' => AmCommerceModel::StatusPending),
            'orderNumber'           => array(AttributeType::Number, 'label' => Craft::t('Order Number'), 'required' => true),
            'totalPrice'            => array(AttributeType::Number, 'decimals' => 2, 'label' => Craft::t('Total Price'), 'required' => true),
            'deliveryType'          => array(AttributeType::String, 'label' => Craft::t('Delivery Type'), 'required' => true),
            'deliveryCompanyName'   => array(AttributeType::String),
            'deliveryFirstName'     => array(AttributeType::String, 'label' => Craft::t('First Name'), 'required' => true),
            'deliveryLastName'      => array(AttributeType::String, 'label' => Craft::t('Last Name'), 'required' => true),
            'deliveryAddress'       => array(AttributeType::String, 'label' => Craft::t('Address'), 'required' => true),
            'deliveryZipCode'       => array(AttributeType::String, 'label' => Craft::t('Zip Code'), 'required' => true),
            'deliveryCity'          => array(AttributeType::String, 'label' => Craft::t('City'), 'required' => true),
            'deliveryCountry'       => array(AttributeType::String, 'label' => Craft::t('Country'), 'required' => true),
            'invoiceCompanyName'    => array(AttributeType::String),
            'invoiceFirstName'      => array(AttributeType::String, 'label' => Craft::t('First Name'), 'required' => true),
            'invoiceLastName'       => array(AttributeType::String, 'label' => Craft::t('Last Name'), 'required' => true),
            'invoiceAddress'        => array(AttributeType::String, 'label' => Craft::t('Address'), 'required' => true),
            'invoiceZipCode'        => array(AttributeType::String, 'label' => Craft::t('Zip Code'), 'required' => true),
            'invoiceCity'           => array(AttributeType::String, 'label' => Craft::t('City'), 'required' => true),
            'invoiceCountry'        => array(AttributeType::String, 'label' => Craft::t('Country'), 'required' => true),
            'telephoneNumber'       => array(AttributeType::String),
            'mobileNumber'          => array(AttributeType::String),
            'emailAddress'          => array(AttributeType::String)
        );
    }

    public function defineRelations()
    {
        return array(
            'element' => array(static::BELONGS_TO, 'ElementRecord', 'id', 'required' => true, 'onDelete' => static::CASCADE),
            'customer' => array(static::BELONGS_TO, 'UserRecord', 'required' => true, 'onDelete' => static::CASCADE)
        );
    }
}