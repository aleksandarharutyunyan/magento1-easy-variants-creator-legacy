<?php

$installer = $this;
$installer->startSetup();
$sql = "
DROP TABLE IF EXISTS {$this->getTable('bialsoft_easyvariantscreator_products')};
    CREATE TABLE `{$this->getTable('bialsoft_easyvariantscreator_products')}` (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_data` text,
  `sortorder` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `removeoldproducts` int(11) NOT NULL,
  PRIMARY KEY (`entity_id`)
) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$installer->run($sql);

$installer->endSetup();
