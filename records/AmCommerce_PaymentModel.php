<?php
namespace Craft;

class AmCommerce_PaymentModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'id'                 => AttributeType::Number,
            'orderId'            => AttributeType::Number,
            'status'             => AttributeType::String,
            'gateway'            => AttributeType::String,
            'amount'             => AttributeType::String,
            'transactionId'      => AttributeType::String,
            'transactionMessage' => AttributeType::String
        );
    }
}