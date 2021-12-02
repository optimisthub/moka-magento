<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // Magento için Moka ödeme sistemi entegrasyonu
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Moka Ücretli dağıtılan bir yazılımdır. 

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.


	

*/
?><?php
	Mage::getSingleton('checkout/session')->addSuccess(__("GriNet Moka v0.1.2 güncelleme başladı..."));

	$kur = $this;
	$kur->startSetup();

	$table=$kur->getTable('sales_flat_order_payment');
	if ($kur->tableExists($table)) 
	{
		$kur->getConnection()->addColumn($table,'moka_taksit','int(3) NULL DEFAULT NULL');    
		$kur->getConnection()->addColumn($table,'moka_taksit_adi','varchar(64) NULL DEFAULT NULL');    
	}

	$table=$kur->getTable('sales_flat_quote_payment');
	if ($kur->tableExists($table)) 
	{
		$kur->getConnection()->addColumn($table,'moka_taksit','int(3) NULL DEFAULT NULL');    
		$kur->getConnection()->addColumn($table,'moka_taksit_adi','varchar(64) NULL DEFAULT NULL');    
	}

	if ($kur->tableExists($kur->getTable('moka_payment_info'))) 
	{
		$sql="
			ALTER TABLE `".$kur->getTable('moka_payment_info')."` 
			CHANGE `installment` `moka_taksit` INT(3) NULL, 
			ADD COLUMN `moka_taksit_adi` VARCHAR(64) CHARSET utf8 COLLATE utf8_general_ci NULL AFTER `moka_taksit`; 
		";
		
		$kur->run($sql);
	}

	// kurulum sonu
	Mage::getSingleton('checkout/session')->addSuccess(__("GriNet Moka 0.1.1 den 0.1.2 ye yükseltme yapıldı..."));
	$kur->endSetup();

?>