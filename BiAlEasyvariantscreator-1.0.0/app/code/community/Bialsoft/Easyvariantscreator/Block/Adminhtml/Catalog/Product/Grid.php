<?php

class Bialsoft_Easyvariantscreator_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid {

    protected function _prepareMassaction() {

        parent::_prepareMassaction();
        $this->getMassactionBlock()->addItem('massCreateSimpleProducts', array(
            'label' => Mage::helper('catalog')->__('Create variants'),
            'url' => Mage::getUrl('admin_easyvariantscreator/adminhtml_easyvariantscreator/massCreateSimpleProducts'),
            'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));
    }

}
