<?php
namespace Craft;

class AmCommerce_SettingRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'amcommerce_settings';
    }

    protected function defineAttributes()
    {
        return array(
            'type'     => array(AttributeType::String, 'required' => true),
            'name'     => array(AttributeType::String, 'required' => true),
            'handle'   => array(AttributeType::String, 'required' => true),
            'settings' => array(AttributeType::Mixed)
        );
    }

    public function defineIndexes()
    {
        return array(
            array('columns' => array('type'), 'unique' => false),
            array('columns' => array('handle'), 'unique' => true)
        );
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return array(
            'ordered' => array('order' => 'handle')
        );
    }
}