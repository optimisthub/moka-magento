<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // Magento için Moka ödeme sistemi entegrasyonu
	Geliştiren.......: Hidayet Ok <hidonet@gmail.com>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Moka Ücretli dağıtılan bir yazılımdır. 

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.


	

*/
?><?php
	Mage::getSingleton('checkout/session')->addSuccess(__("Moka Payment v0.1.5 güncelleme başladı..."));

	$kur = $this;
	$kur->startSetup();

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
	Mage::getSingleton('checkout/session')->addSuccess(__("Moka Payment 0.1.5<img src="../../../../../../../../_Saglam/skin/frontend/base/default/images/moka_payment/banka/finans.png" width="100" height="30" border="0" alt="" /> e yükseltme yapıldı..."));
	$kur->endSetup();

?>