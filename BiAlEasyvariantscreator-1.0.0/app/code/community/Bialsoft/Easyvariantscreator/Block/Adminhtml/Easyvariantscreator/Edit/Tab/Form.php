<?php

class Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("easyvariantscreator_form", array("legend" => Mage::helper("easyvariantscreator")->__("Item information")));


        $fieldset->addField("sortorder", "text", array(
            "label" => Mage::helper("easyvariantscreator")->__("Sort order"),
            "name" => "sortorder",
        ));

        $fieldset->addField("status", "select", array(
            "label" => Mage::helper("easyvariantscreator")->__("Status"),
            'values' => Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Grid::getValueArrayActive(),
            "name" => "status",
        ));

        $fieldset->addField("removeoldproducts", "select", array(
            "label" => Mage::helper("easyvariantscreator")->__("Remove old products"),
            'values' => Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Grid::getValueArrayYesno(),
            "name" => "removeoldproducts",
        ));


        if (Mage::getSingleton("adminhtml/session")->getEasyvariantscreatorData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getEasyvariantscreatorData());
            Mage::getSingleton("adminhtml/session")->setEasyvariantscreatorData(null);
        } elseif (Mage::registry("easyvariantscreator_data")) {
            $form->setValues(Mage::registry("easyvariantscreator_data")->getData());
        }
        return parent::_prepareForm();
    }

}
