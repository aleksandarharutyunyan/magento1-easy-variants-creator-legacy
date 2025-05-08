<?php

class Bialsoft_Easyvariantscreator_Model_Mysql4_Easyvariantscreator_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        $this->_init("easyvariantscreator/easyvariantscreator");
    }

    protected function _beforeLoad() {

        $cache4magtable = $this->getTable('easyvariantscreator');
        $this->getSelect()->columns(array('product_name' => 'product_id'));
        $this->getSelect()->columns(array('status_flag' => 'status'));
        $this->getSelect()->columns(array('removeoldproducts_flag' => 'removeoldproducts'));

        return parent::_beforeLoad();
    }

    public function _afterLoad() {



        foreach ($this as $object) {


            $object->setProduct_name(Mage::getModel('catalog/product')->load($object->getProduct_id())->getName());




            if ($object->getStatus()) {
                $object->setStatus_flag(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'adminhtml/default/default/bialsoft_evc/green_marker.png');
            } else {
                $object->setStatus_flag(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'adminhtml/default/default/bialsoft_evc/red_marker.png');
            }


            if ($object->getRemoveoldproducts()) {
                $object->setRemoveoldproducts_flag(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'adminhtml/default/default/bialsoft_evc/green_marker.png');
            } else {
                $object->setRemoveoldproducts_flag(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'adminhtml/default/default/bialsoft_evc/red_marker.png');
            }
        }

        return parent::_afterLoad();
    }

}
