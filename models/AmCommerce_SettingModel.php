<?php
namespace Craft;

class AmCommerce_SettingModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'id'       => AttributeType::Number,
            'enabled'  => AttributeType::Bool,
            'type'     => AttributeType::String,
            'name'     => AttributeType::String,
            'handle'   => AttributeType::String,
            'settings' => AttributeType::Mixed
        );
    }
}