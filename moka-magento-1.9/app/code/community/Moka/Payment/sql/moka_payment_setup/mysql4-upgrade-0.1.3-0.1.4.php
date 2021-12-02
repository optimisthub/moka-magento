<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: GriNet_moka // Magento için Moka ödeme sistemi entegrasyonu
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Moka Ücretli dağıtılan bir yazılımdır. 

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.


	

*/
?><?php
	Mage::getSingleton('checkout/session')->addSuccess(__("GriNet Moka v0.1.4 güncelleme başladı..."));

	$kur = $this;
	$kur->startSetup();

	$sql="ALTER TABLE `".$kur->getTable('sales_order_status')."` ADD `trans_key` VARCHAR(64) NOT NULL AFTER `trans_date`;";	$ret = $kur->run($sql);
	$sql="ALTER TABLE `".$kur->getTable('sales_order_status')."` ADD `moka_order_id` VARCHAR(128) NOT NULL AFTER `banka_order_id`;";	$ret = $kur->run($sql);
	$sql="ALTER TABLE `".$kur->getTable('sales_order_status')."` ADD `data_verify_send` TEXT NOT NULL AFTER `data_2_response`;";	$ret = $kur->run($sql);
	$sql="ALTER TABLE `".$kur->getTable('sales_order_status')."` ADD `data_verify_response` TEXT NOT NULL AFTER `data_verify_send`;";	$ret = $kur->run($sql);
	$sql="ALTER TABLE `".$kur->getTable('sales_order_status')."` ADD `moka_trans_id` VARCHAR(64) NOT NULL AFTER `moka_message`;";	$ret = $kur->run($sql);

	// kurulum sonu
	Mage::getSingleton('checkout/session')->addSuccess(__("GriNet Moka 0.1.3 den 0.1.4 ye yükseltme yapıldı..."));
	$kur->endSetup();

?>