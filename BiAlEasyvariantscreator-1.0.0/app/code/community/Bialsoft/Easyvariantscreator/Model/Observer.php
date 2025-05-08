<?php

class Bialsoft_Easyvariantscreator_Model_Observer {

    static protected $_singletonFlag = false;

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest() {
        return Mage::app()->getRequest();
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct() {
        return Mage::registry('product');
    }

    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer) {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;

            if ($this->_getRequest()->getPost('is_needed_save')) {

                $write = Mage::getSingleton('core/resource')->getConnection('core_write');

                $table_name = Mage::getSingleton('core/resource')->getTableName('bialsoft_easyvariantscreator_products');


                $product = $observer->getEvent()->getProduct();

                try {
                    /**
                     * Perform any actions you want here
                     *
                     */
                    $query = 'SELECT product_id   FROM  ' . $table_name . ' WHERE product_id=' . $product->getId();

                    $rs = $write->fetchAll($query);

                    $error_in_setup = false;
                    $params = explode(",", $this->_getRequest()->getPost('multi_create_parameters'));
                    $customFieldValue = '';
                    $isfirst = true;
                    foreach ($params as $key => $v) {
                        $param_v = $this->_getRequest()->getPost('options_' . $v);
                        $param = '';
                        if (!count($param_v)) {
                            $error_in_setup = true;
                        }
                        for ($i = 0; $i < count($param_v); $i++)
                            if ($i == 0)
                                $param.=$param_v[$i];
                            else
                                $param.="," . $param_v[$i];
                        if ($isfirst) {
                            $customFieldValue.=$v . ":" . $param;
                            $isfirst = false;
                        } else
                            $customFieldValue.=";" . $v . ":" . $param;
                    }


                    if (!$error_in_setup) {
                        $removeoldproducts = $this->_getRequest()->getPost('removeoldproducts');
                        $vcstate = $this->_getRequest()->getPost('vcstate');
                        $sortorder = $this->_getRequest()->getPost('sortorder');


                        if ($rs) {
                            $write->query("UPDATE " . $table_name . " SET product_data = '" . $customFieldValue . "', removeoldproducts=" . $removeoldproducts . ", status=" . $vcstate . ", sortorder=" . $sortorder . " WHERE product_id = " . $product->getId());
                        } else {
                            $write->query("INSERT INTO " . $table_name . " VALUES (''," . $product->getId() . ", '" . $customFieldValue . "', " . $sortorder . "," . $vcstate . "," . $removeoldproducts . ")");
                        }
                    } else {
                        Mage::getSingleton('adminhtml/session')->addError("Please select at least one option for each attribute!");
                    }

                    /**
                     * Uncomment the line below to save the product
                     *
                     */
                    //$product->save();
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        }
    }

}
