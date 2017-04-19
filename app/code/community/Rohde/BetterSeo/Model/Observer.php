<?php

class Rohde_BetterSeo_Model_Observer
{

    public function alternateLinks(Varien_Event_Observer $observer) {

        $block = $observer->getBlock();
        if ($block instanceof Mage_Page_Block_Html_Head && Mage::getStoreConfig("betterseo/alternatelinks/enable_module") != false) {
            $transport = $observer->getTransport(); /* @var Varien_Object $transport */
            $html = $transport->getHtml();
            $storeGroupId = Mage::app()->getStore()->getGroupId();
            $stores = Mage::getModel('core/store')->getCollection()->addFieldToFilter('group_id', $storeGroupId);
            $prod = Mage::registry('current_product');
            $categ = Mage::registry('current_category');
            $cms = Mage::getSingleton('cms/page')->getIdentifier();
            $xdefaults = [];
            $code = '';
            foreach ($stores as $store) {
                if($store->getIsActive()) {
                    if ($prod) {
                        $categ ? $categId = $categ->getId() : $categId = null;
                        $url = $store->getBaseUrl() . Mage::helper('betterseo')->rewrittenProductUrl($prod->getId(), $categId, $store->getId());
                    } elseif ($categ) {
                        $url = $store->getBaseUrl() . Mage::helper('betterseo')->rewrittenCategoryUrl($categ->getId(), $store->getId());
                    } elseif ($cms) {
                        $url = $store->getBaseUrl() . Mage::helper('betterseo')->rewrittenCmsUrl($cms, $store->getId());
                    } else {
                        $url = $store->getCurrentUrl();
                    }
                    // create hreflang uri
                    $urlExplode = explode('?', $url, 2);
                    if ($store->getCode() == 'default') {
                        $urlPart = $urlExplode[0];
                        $del = '?';
                    } else {
                        $urlPart = $urlExplode[0].'?___store='.$store->getCode();
                        $del = '&';
                    }
                    // add existing parameters again when present
                    if (isset($urlExplode[1]) && !empty($urlExplode[1])) {
                        $urlPart .= $del.$urlExplode[1];
                    }
                    $lang = $store->getConfig('general/locale/code');
                    $xdefaults[substr($lang, 0, 2)] = $urlPart;
                    if(Mage::getStoreConfig("betterseo/alternatelinks/hreflang_value") == 'language') {
                        $lang = substr($lang, 0, 2);// ISO 639-1 format
                    } elseif(Mage::getStoreConfig("betterseo/alternatelinks/hreflang_value") == 'language-region') {
                        $lang = preg_replace("/[\s_]/", "-", $lang); // ISO 3166-1 Alpha 2 format
                    }
                    $code .= '<link rel="alternate" hreflang="'.$lang.'" href="'.$urlPart.'" />';
                }
            }
            $xdefault = Mage::getStoreConfig("betterseo/alternatelinks/xdefault_value");
            if($xdefault !== 'nouse' && isset($xdefaults[$xdefault])) {
                $code .= '<link rel="alternate" hreflang="x-default" href="'.$xdefaults[$xdefault].'" />';
            }
            $transport->setHtml($html.$code);
        }
        return $this;
    }
}
