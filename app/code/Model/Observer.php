<?php

class Rohde_Seo_Model_Observer
{

    public function alternateLinks()
    {
        //Stores Magento head structural block as a variable
        $headBlock = Mage::app()->getLayout()->getBlock('head');

        //Gets all stores for the website
        $stores = Mage::app()->getWebsite()->getStores();

        //Gets current page URL
        //$currentUrl = Mage::helper('core/url')->getCurrentUrl();

        //Gets current store Id
        $currentStoreId = Mage::app()->getStore()->getStoreId();

        //Test to see if the module is disabled
        if(Mage::getStoreConfig("web/rohdeseo/enable_module") != false) {

            //Test to make sure the native head structural block is exists
            if($headBlock) {

                //Loop through the stores
                foreach ($stores as $store) {

                    //gets the store language and region in native xx_XX format
                    $lang = $store->getConfig('general/locale/code');

                    //Strips parameters from the core URL which are not present in the 'actual' URL and stores in a new variable
                    $cleanUrl = preg_replace('/\?.*/', '', $store->getCurrentUrl());

                    //Tests config setting in admin for the hreflang format
                    if(Mage::getStoreConfig("web/rohdeseo/hreflang_value") == 'language') {

                        // Strips locale string down to th first 2 characters - matching the ISO 639-1 format
                        $lang = substr($lang, 0, 2);
                    } elseif(Mage::getStoreConfig("web/rohdeseo/hreflang_value") == 'language-region') {

                        // Replaces the native Magento '_' with a '-' to match the ISO 3166-1 Alpha 2 format
                        $lang = preg_replace("/[\s_]/", "-", $lang);
                    }

                    //Output the rel link using the native addLinkRel() method
                    $headBlock->addLinkRel('alternate"' . ' hreflang="' . $lang, $cleanUrl);
                }
            }
            return $this;
        }
    }
}