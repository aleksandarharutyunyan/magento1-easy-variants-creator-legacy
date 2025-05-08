<?php
class Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("easyvariantscreator_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("easyvariantscreator")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("easyvariantscreator")->__("Item Information"),
				"title" => Mage::helper("easyvariantscreator")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("easyvariantscreator/adminhtml_easyvariantscreator_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
