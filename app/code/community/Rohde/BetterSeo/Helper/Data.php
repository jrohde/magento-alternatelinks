<?php

class Rohde_BetterSeo_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function rewrittenProductUrl($productId, $categoryId, $storeId) {
        $coreUrl = Mage::getModel('core/url_rewrite');
        $idPath = sprintf('product/%d', $productId);
        if ($categoryId) {
            $idPath = sprintf('%s/%d', $idPath, $categoryId);
        }
        $coreUrl->setStoreId($storeId);
        $coreUrl->loadByIdPath($idPath);
        return $coreUrl->getRequestPath();
    }

    public function rewrittenCategoryUrl($categoryId, $storeId) {
        $coreUrl = Mage::getModel('core/url_rewrite');
        $idPath = sprintf('category/%d', $categoryId);
        $coreUrl->setStoreId($storeId);
        $coreUrl->loadByIdPath($idPath);
        return $coreUrl->getRequestPath();
    }

    public function rewrittenCategoryCmsUrl($identifier, $categoryId, $storeId) {
        $vehicle = strstr($identifier, '/');
        $categoryUrlKey = Mage::getModel('catalog/category')->setStoreId($storeId)->load($categoryId)->getUrlKey();
        return $categoryUrlKey.$vehicle;
    }

    public function rewrittenCmsUrl($urlkey, $storeId) {
        $coreUrl = Mage::getModel('core/url_rewrite');
        $coreUrl->setStoreId($storeId);
        $coreUrl->loadByRequestPath($urlkey);
        return $coreUrl->getTargetPath();
    }

}

?>
