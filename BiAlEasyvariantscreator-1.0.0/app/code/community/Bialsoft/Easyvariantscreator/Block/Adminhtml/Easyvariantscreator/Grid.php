<?php

class Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {

        parent::__construct();
        $this->setId("easyvariantscreatorGrid");
        $this->setDefaultSort("entity_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {

        $collection = Mage::getModel("easyvariantscreator/easyvariantscreator")->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns() {
        $this->addColumn("entity_id", array(
            "header" => Mage::helper("easyvariantscreator")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "entity_id",
        ));

        $this->addColumn("product_name", array(
            "header" => Mage::helper("easyvariantscreator")->__("Product"),
            "index" => "product_name",
        ));
        $this->addColumn("product_data", array(
            "header" => Mage::helper("easyvariantscreator")->__("Product data"),
            "index" => "product_data",
        ));
        $this->addColumn("sortorder", array(
            "header" => Mage::helper("easyvariantscreator")->__("Sort order"),
            "index" => "sortorder",
        ));
        $this->addColumn("status_flag", array(
            "header" => Mage::helper("easyvariantscreator")->__("Status"),
            "index" => "status_flag",
            'renderer' => 'easyvariantscreator/adminhtml_renderer_image',
        ));
        $this->addColumn("removeoldproducts_flag", array(
            "header" => Mage::helper("easyvariantscreator")->__("Remove old products"),
            "index" => "removeoldproducts_flag",
            'renderer' => 'easyvariantscreator/adminhtml_renderer_image',
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('entity_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_easyvariantscreator', array(
            'label' => Mage::helper('easyvariantscreator')->__('Remove Entries'),
            'url' => $this->getUrl('*/adminhtml_easyvariantscreator/massRemove'),
            'confirm' => Mage::helper('easyvariantscreator')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('massCreateSimpleProducts', array(
            'label' => Mage::helper('catalog')->__('Create variants'),
            'url' => Mage::getUrl('*/*/massCreateSimpleProducts'),
            'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));
        return $this;
    }

    static public function getOptionArrayYesno() {
        $data_array = array();
        $data_array[1] = 'Yes';
        $data_array[0] = 'No';

        return($data_array);
    }

    static public function getValueArrayYesno() {
        $data_array = array();
        foreach (Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Grid::getOptionArrayYesno() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

    static public function getOptionArrayActive() {
        $data_array = array();
        $data_array[1] = 'Enabled';
        $data_array[0] = 'Disabled';

        return($data_array);
    }

    static public function getValueArrayActive() {
        $data_array = array();
        foreach (Bialsoft_Easyvariantscreator_Block_Adminhtml_Easyvariantscreator_Grid::getOptionArrayActive() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

}
