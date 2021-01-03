<?php

require_once(dirname(__FILE__) . '/../../../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../../../init.php');

switch (Tools::getValue('method')) {
  case 'myMethod' :

  $data = Tools::getValue('data');

  $sql = 'SELECT p.id_product, p.active, pl.name, GROUP_CONCAT(DISTINCT(cl.name) SEPARATOR ",") as categories, p.price, p.id_tax_rules_group, p.wholesale_price, p.reference, p.supplier_reference, p.id_supplier, p.id_manufacturer, p.upc, p.ecotax, p.weight, p.quantity, pl.description_short, pl.description, pl.meta_title, pl.meta_keywords, pl.meta_description, pl.link_rewrite, pl.available_now, pl.available_later, p.available_for_order, p.date_add, p.show_price, p.online_only, p.condition, p.id_shop_default
		FROM ps_product p
		LEFT JOIN ps_product_lang pl ON (p.id_product = pl.id_product)
		LEFT JOIN ps_category_product cp ON (p.id_product = cp.id_product)
		LEFT JOIN ps_category_lang cl ON (cp.id_category = cl.id_category)
		LEFT JOIN ps_category c ON (cp.id_category = c.id_category)
		LEFT JOIN ps_product_tag pt ON (p.id_product = pt.id_product)
		WHERE pl.id_lang = 1
		AND cl.id_lang = 1
		AND p.id_shop_default = 1
		AND c.id_shop_default = 1
		AND p.reference = '.$data.'
		GROUP BY p.id_product';

  	$results = Db::getInstance()->ExecuteS($sql);

    die(Tools::jsonEncode( array('result'=>$results)));
    break;
  default:
    exit;
}
