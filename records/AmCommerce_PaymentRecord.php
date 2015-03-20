<?php
namespace Craft;

class AmCommerce_PaymentRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'amcommerce_payments';
    }

    protected function defineAttributes()
    {
        return array(
            'status'             => array(AttributeType::String, 'required' => true),
            'gateway'            => array(AttributeType::String, 'required' => true),
            'amount'             => array(AttributeType::String, 'decimals' => 2, 'required' => true),
            'transactionId'      => array(AttributeType::String),
            'transactionMessage' => array(AttributeType::String)
        );
    }

    public function defineRelations()
    {
        return array(
            'order' => array(static::BELONGS_TO, 'AmCommerce_OrderRecord', 'orderId', 'required' => true, 'onDelete' => static::CASCADE)
        );
    }

    public function defineIndexes()
    {
        return array(
            array('columns' => array('status'), 'unique' => false),
            array('columns' => array('gateway'), 'unique' => false),
            array('columns' => array('gateway, transactionId'), 'unique' => false)
        );
    }
}