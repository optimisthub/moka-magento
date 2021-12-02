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
	Mage::getSingleton('checkout/session')->addSuccess(__("GriNet Moka v0.1.3 güncelleme başladı..."));

	$kur = $this;
	$kur->startSetup();

	$sql="REPLACE INTO `".$kur->getTable('sales_order_status')."`			(`status`,`label`) VALUES ( 'canceled_moka',							'Moka Odeme Hatasi');";				$ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status')."`			(`status`,`label`) VALUES ( 'processing_moka',							'Moka Odeme Basarili');";			$ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status')."`			(`status`,`label`) VALUES ( 'payment_review_moka',						'Moka Odeme Inceleniyor');";		$ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status')."`			(`status`,`label`) VALUES ( 'pending_moka',								'Moka Odeme Tamamlanmadi');";		$ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status')."`			(`status`,`label`) VALUES ( 'pending_payment_moka',						'Moka Odeme Tamamlanmadi'); ";		$ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status')."`			(`status`,`label`) VALUES ( 'pending_payment_moka_queue',				'Sipariş Kuyrukta'); ";				$ret = $kur->run($sql);

	$sql="REPLACE INTO `".$kur->getTable('sales_order_status_state')."`		(`status`,`state`,`is_default`) VALUES ( 'canceled_moka',				'canceled',			'0');"; $ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status_state')."`		(`status`,`state`,`is_default`) VALUES ( 'processing_moka',				'processing',		'0');"; $ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status_state')."`		(`status`,`state`,`is_default`) VALUES ( 'payment_review_moka',			'payment_review',	'0');"; $ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status_state')."`		(`status`,`state`,`is_default`) VALUES ( 'pending_moka',				'pending',			'0');"; $ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status_state')."`		(`status`,`state`,`is_default`) VALUES ( 'pending_payment_moka',		'pending_payment',	'0');"; $ret = $kur->run($sql);
	$sql="REPLACE INTO `".$kur->getTable('sales_order_status_state')."`		(`status`,`state`,`is_default`) VALUES ( 'pending_payment_moka_queue',	'pending_payment',	'0');"; $ret = $kur->run($sql);

	// kurulum sonu
	Mage::getSingleton('checkout/session')->addSuccess(__("GriNet Moka 0.1.2 den 0.1.3 ye yükseltme yapıldı..."));
	$kur->endSetup();

?>