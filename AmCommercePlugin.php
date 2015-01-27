<?php
/**
 * E-commerce for Craft.
 *
 * @package   Am Commerce
 * @author    Hubert Prein
 */
namespace Craft;

class AmCommercePlugin extends BasePlugin
{
    public function getName()
    {
        $settings = $this->getSettings();
        if ($settings->pluginName) {
            return $settings->pluginName;
        }
        return Craft::t('a&m commerce');
    }

    public function getVersion()
    {
        return '0.1';
    }

    public function getDeveloper()
    {
        return 'a&m impact';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.am-impact.nl';
    }

    /**
     * Plugin has control panel section.
     *
     * @return boolean
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * Plugin has Control Panel routes.
     *
     * @return array
     */
    public function registerCpRoutes()
    {
        return array(
            'amcommerce' => array(
                'action' => 'amCommerce/index'
            ),

            'amcommerce/orders' => array(
                'action' => 'amCommerce/orders/index'
            ),
            'amcommerce/orders/new' => array(
                'action' => 'amCommerce/orders/editOrder'
            ),
            'amcommerce/orders/edit/(?P<orderId>\d+)' => array(
                'action' => 'amCommerce/orders/editOrder'
            ),
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('amcommerce/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    /**
     * Plugin settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'pluginName' => array(AttributeType::String)
        );
    }
}