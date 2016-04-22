<?php

class Rohde_BetterSeo_Model_System_Config_Source_Dropdown_Locales
{
    public function toOptionArray() {
        $locales = array(
            array(
                'value' => 'nouse',
                'label' => 'Don\'t use x-default (not recommended)'
            )
        );
        $storeId = $groupId = $websiteId = [];
        if ($code = Mage::getSingleton('adminhtml/config_data')->getStore()) { // store level
            $storeGroupId = Mage::getModel('core/store')->load($code)->getGroupId();
            $stores = Mage::getModel('core/store')->getCollection()->addFieldToFilter('group_id', $storeGroupId);
        } else if ($code = Mage::getSingleton('adminhtml/config_data')->getWebsite()) { // website level
            $website_id = Mage::getModel('core/website')->load($code)->getId();
            $stores = Mage::app()->getWebsite($website_id)->getStores();
        } else {
            $stores = Mage::app()->getStores();
        }
        $languages = Mage::app()->getLocale()->getOptionLocales();
        $options = [];
        foreach ($stores as $store) {
            $locale = $store->getConfig('general/locale/code');
            if(!isset($options[$locale])) {
                $key = (int)array_search($locale, array_column($languages, 'value'));
                $locales[] = [
                    'value' => substr($locale, 0, 2),
                    'label' => $languages[$key]['label']
                ];
                $options[$locale] = true;
            }
        }
        return $locales;
    }
}

?>