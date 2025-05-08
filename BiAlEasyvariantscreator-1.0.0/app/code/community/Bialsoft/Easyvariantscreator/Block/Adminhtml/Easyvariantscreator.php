<?php

class Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {

        $this->_controller = "adminhtml_easyvariantscreator";
        $this->_blockGroup = "easyvariantscreator";
        $this->_headerText = Mage::helper("easyvariantscreator")->__("Easyvariantscreator Manager");
        $this->_addButtonLabel = Mage::helper("easyvariantscreator")->__("Add New Item");
        parent::__construct();
        $this->_removeButton('add');
    }

}
