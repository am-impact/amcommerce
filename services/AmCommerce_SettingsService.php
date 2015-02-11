<?php
namespace Craft;

/**
 * AmCommerce - Settings service
 */
class AmCommerce_SettingsService extends BaseApplicationComponent
{
    /**
     * Get all settings by their type.
     *
     * @param string $type
     *
     * @return AmCommerce_SettingModel
     */
    public function getAllSettingsByType($type)
    {
        $settingRecords = AmCommerce_SettingRecord::model()->ordered()->findAllByAttributes(array('type' => $type));
        return AmCommerce_SettingModel::populateModels($settingRecords, 'id');
    }

    /**
     * Save settings.
     *
     * @param AmCommerce_SettingModel
     *
     * @return bool
     */
    public function saveSettings(AmCommerce_SettingModel $settings)
    {
        if (! $settings->id) {
            return false;
        }

        $settingsRecord = AmCommerce_SettingRecord::model()->findById($settings->id);

        if (! $settingsRecord) {
            throw new Exception(Craft::t('No settings exists with the ID “{id}”.', array('id' => $settings->id)));
        }

        // Set attributes
        $settingsRecord->setAttributes($settings->getAttributes());
        $settingsRecord->setAttribute('settings', json_encode($settings->settings));

        // Validate
        $settingsRecord->validate();
        $settings->addErrors($settingsRecord->getErrors());

        // Save settings
        if (! $settings->hasErrors()) {
            // Save in database
            return $settingsRecord->save();
        }
        return false;
    }
}