<?php

class Bialsoft_Easyvariantscreator_Helper_Data extends Mage_Core_Helper_Abstract {

    public function combine($r, $c = '') {

        if ($s = (array) array_shift($r))
            foreach ($s as $p)
                foreach ($this->combine($r, $c) as $t)
                    $o[] = $p . "," . $t;
        else
            return (array) $c;
        return $o;
    }

    public function massCreateSimpleProducts() {



        Mage::log("massCreateSimpleProducts start");
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $table_name = Mage::getSingleton('core/resource')->getTableName('bialsoft_easyvariantscreator_products');
        if (isset($_REQUEST['product_id'])) {
            $query = 'SELECT *   FROM  ' . $table_name . " WHERE product_id=" . $_REQUEST['product_id'] . ' AND status=1';
        } elseif (isset($_REQUEST['entity_ids'])) {
            $query = 'SELECT *   FROM  ' . $table_name . " WHERE entity_id IN (" . $_REQUEST['entity_ids'] . ') AND status=1';
        } elseif (isset($_REQUEST['product'])) {
            $query = 'SELECT *   FROM  ' . $table_name . " WHERE product_id IN (" . $_REQUEST['product'] . ') AND status=1';
        } else {
            $query = 'SELECT *   FROM  ' . $table_name . ' WHERE status=1 ORDER BY sortorder ASC';
        }


        $rs = $write->fetchAll($query);



        foreach ($rs as $key => $value) {
            $newids = array();
            $keys = array();

            $params_relation = array();
            $params = explode(";", $value['product_data']);
            for ($i = 0; $i < count($params); $i++) {
                $params[$i] = explode(":", $params[$i]);
                $params[$i][1] = explode(",", $params[$i][1]);
                if ($params[$i][0]) {
                    $keys[] = $params[$i][0];
                    $params_relation[$params[$i][0]] = $params[$i][1];
                }
            }

            $combinations_temp = $this->combine($params_relation);
            $combinations = array();

            foreach ($combinations_temp as $key => $combination) {
                $combinations_temp[$key] = explode(",", $combination);

                array_pop($combinations_temp[$key]);

                foreach ($combinations_temp[$key] as $k => $v) {
                    $combinations[$key][$keys[$k]] = $v;
                }
            }


            $removeoldproducts = $rs[0]['removeoldproducts'];



            $product = Mage::getModel('catalog/product')
                    ->load($value['product_id']);

            $skus = array();
            $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);

            foreach ($childProducts as $cp)
                try {
                    if ($removeoldproducts)
                        $cp->delete();

                    $skus[] = $cp->getSku();
                } catch (Exception $e) {
                    Mage::log("massCreateSimpleProducts " . $e);
                }








            sort($skus, SORT_NUMERIC);
            $skus = array_reverse($skus, false);
//print_r($skus);
            $addedsku = substr($skus[0], strlen($skus[0]) - 4);



            if ($removeoldproducts)
                $addedsku = str_pad("0", 4, "0", STR_PAD_LEFT);


            /* @var $configurableProduct Mage_Catalog_Model_Product */
            $configurableProduct = Mage::getModel('catalog/product')
                    ->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)
                    ->load($value['product_id']);

            if (!$configurableProduct->isConfigurable()) {
                continue;
            }


            foreach ($combinations as $combination) {
                $old_unused_prod = false;

                if (!$removeoldproducts) {
                    $name = " -";

                    foreach ($configurableProduct->getTypeInstance()->getConfigurableAttributes() as $attribute) {
                        $attr = Mage::getModel('eav/config')->getAttribute('catalog_product', $attribute->getProductAttribute()->getId());
                        foreach ($attr->getSource()->getAllOptions(true, true) as $option) {
                            if ($option['value'] == $combination[$attribute->getProductAttribute()->getId()]) {


                                $name.= " " . $option['label'];
                            }
                        }
                    }




                    $__product = Mage::getModel('catalog/product')->loadByAttribute('name', $configurableProduct->getName() . $name);
                    if (!$__product) {
                        $addedsku = (int) $addedsku + 1;
                        $addedsku = str_pad($addedsku, 4, "0", STR_PAD_LEFT);
                    }
                } else {
                    $addedsku = (int) $addedsku + 1;
                    $addedsku = str_pad($addedsku, 4, "0", STR_PAD_LEFT);


                    $__product = Mage::getModel('catalog/product')->loadByAttribute('sku', $configurableProduct->getSku() . $addedsku);
                }

                if ($__product) {
                    foreach ($configurableProduct->getTypeInstance()->getConfigurableAttributes() as $attribute) {

                        if (!$__product->getAttributeText($attribute->getProductAttribute()->getAttributeCode())) {

                            try {
                                $__product->delete();
                                $old_unused_prod = true;
                            } catch (Exception $e) {
                                Mage::log("massCreateSimpleProducts " . $e);
                            }
                        }
                    }
                }
                if ($old_unused_prod) {
                    $__product = Mage::getModel('catalog/product')->loadByAttribute('sku', $configurableProduct->getSku() . $addedsku);
                }



                if (!$__product) {

                    $result = array();

                    /* @var $product Mage_Catalog_Model_Product */

                    $product = Mage::getModel('catalog/product')
                            ->setStoreId(0)
                            ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
                            ->setAttributeSetId($configurableProduct->getAttributeSetId());


                    foreach ($product->getTypeInstance()->getEditableAttributes() as $attribute) {
                        if ($attribute->getIsUnique() || $attribute->getAttributeCode() == 'url_key' || $attribute->getAttributeCode() == 'ean' || $attribute->getFrontend()->getInputType() == 'gallery' || $attribute->getFrontend()->getInputType() == 'media_image' || !$attribute->getIsVisible()) {
                            continue;
                        }

                        $product->setData(
                                $attribute->getAttributeCode(), $configurableProduct->getData($attribute->getAttributeCode())
                        );
                    }

                    //$product->addData($this->getRequest()->getParam('simple_product', array()));
                    $product->setWebsiteIds($configurableProduct->getWebsiteIds());

                    $autogenerateOptions = array();
                    $result['attributes'] = array();

                    $name = " -";

                    foreach ($configurableProduct->getTypeInstance()->getConfigurableAttributes() as $attribute) {
                        $value = $product->getAttributeText($attribute->getProductAttribute()->getAttributeCode());
                        $autogenerateOptions[] = $value;
                        $result['attributes'][] = array(
                            'label' => $value,
                            'value_index' => $product->getData($attribute->getProductAttribute()->getAttributeCode()),
                            'attribute_id' => $attribute->getProductAttribute()->getId()
                        );

                        $attribute->getProductAttribute()->getId();
                        //echo $combination[$attribute->getProductAttribute()->getId()];
                        $attr = Mage::getModel('eav/config')->getAttribute('catalog_product', $attribute->getProductAttribute()->getId());
                        foreach ($attr->getSource()->getAllOptions(true, true) as $option) {
                            if ($option['value'] == $combination[$attribute->getProductAttribute()->getId()]) {

                                $product->setData($attribute->getProductAttribute()->getAttributeCode(), $option['value']);
                                $name.= " " . $option['label'];
                            }
                        }
                    }


                    $product->setName($configurableProduct->getName() . $name);



                    $product->setSku($configurableProduct->getSku() . $addedsku);



                    //echo $product->getName()."<br/>";

                    $product->setVisibility("1");

                    $stockData['qty'] = 10000;
                    $stockData['is_in_stock'] = 1;
                    $product->setStockData($stockData);



                    try {
                        $product->validate();
                        $product->save();
                        $result['product_id'] = $product->getId();
                        $newids[$result['product_id']] = 1;
                    } catch (Mage_Core_Exception $e) {
                        $result['error'] = array(
                            'message' => $e->getMessage(),
                            'fields' => array(
                                'sku' => $product->getSku()
                            )
                        );
                    } catch (Exception $e) {
                        Mage::logException($e);
                        $result['error'] = array(
                            'message' => $this->__('An error occurred while saving the product. ') . $e->getMessage()
                        );
                    }


                    Mage::getSingleton('core/session')->addSuccess("Subproduct added: " . $product->getId());
                } else {
                    $newids[$__product->getId()] = 1;
                    Mage::getSingleton('core/session')->addSuccess("Subproduct updated: " . $__product->getId());
                }
            }


            $loader = Mage::getResourceModel('catalog/product_type_configurable')->load($configurableProduct->getId());




            $loader->saveProducts($configurableProduct, array_keys($newids));
            //$cache = Mage::getSingleton('core/cache');


            $write->query("DELETE   FROM  " . $table_name . " WHERE product_id=" . $configurableProduct->getId());
        }
        Mage::app()->getResponse()->setRedirect(Mage::getUrl('*/*/'));
    }

}
