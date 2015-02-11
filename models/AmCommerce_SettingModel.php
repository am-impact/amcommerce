<?php
namespace Craft;

class AmCommerce_SettingModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'id'       => AttributeType::Number,
            'type'     => AttributeType::String,
            'name'     => AttributeType::String,
            'handle'   => AttributeType::String,
            'settings' => AttributeType::Mixed
        );
    }
}