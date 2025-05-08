<?php
class Bialsoft_Easyvariantscreator_Model_Mysql4_Easyvariantscreator extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("easyvariantscreator/easyvariantscreator", "entity_id");
    }
}