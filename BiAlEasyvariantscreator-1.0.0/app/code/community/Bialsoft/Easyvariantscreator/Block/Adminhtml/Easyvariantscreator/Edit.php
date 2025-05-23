<?php

class Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "entity_id";
        $this->_blockGroup = "easyvariantscreator";
        $this->_controller = "adminhtml_easyvariantscreator";
        $this->_updateButton("save", "label", Mage::helper("easyvariantscreator")->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("easyvariantscreator")->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("easyvariantscreator")->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);



        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    public function getHeaderText() {
        if (Mage::registry("easyvariantscreator_data") && Mage::registry("easyvariantscreator_data")->getId()) {

            return Mage::helper("easyvariantscreator")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("easyvariantscreator_data")->getId()));
        } else {

            return Mage::helper("easyvariantscreator")->__("Add Item");
        }
    }

}
