<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	 

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/
?><?php
	Mage::getSingleton('checkout/session')->addSuccess(__("Moka Payment v0.1.1 kurulumu başladı..."));

	$kur = $this;
	$kur->startSetup();

	// Tablo yoksa olustur
	if (!$kur->tableExists($kur->getTable('moka_payment_info'))) 
	{
		$sql="
				CREATE TABLE IF NOT EXISTS `".$kur->getTable('moka_payment_info')."` (
				  `trans_id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT 'id',
				  `trans_date` datetime DEFAULT NULL,
				  `trans_type` enum('live','test') DEFAULT 'live',
				  `customer_id` int(11) DEFAULT NULL COMMENT 'musteri id',
				  `guest_id` int(11) DEFAULT NULL COMMENT 'eger guest ise onun id',
				  `order_id` int(11) unsigned zerofill DEFAULT NULL COMMENT 'system order id',
				  `quote_id` int(11) DEFAULT NULL COMMENT 'system quote id',
				  `banka_order_id` varchar(36) DEFAULT NULL COMMENT 'returned order id',
				  `moka_banka` varchar(32) DEFAULT NULL COMMENT 'moka dan donen banka id',
				  `installment` int(3) DEFAULT NULL,
				  `sub_total` float(10,2) DEFAULT NULL,
				  `grand_total` float(10,2) DEFAULT NULL,
				  `moka_total` float(10,2) DEFAULT NULL,
				  `random_hash` varchar(128) DEFAULT NULL,
				  `data_1_send` text,
				  `data_1_response` text,
				  `data_2_send` text,
				  `data_2_response` text,
				  `moka_response_code` varchar(64) DEFAULT NULL,
				  `moka_status` varchar(64) DEFAULT NULL,
				  `moka_message` tinytext,
				  `auth_code` varchar(64) DEFAULT NULL,
				  `customer_message` tinytext,
				  `error_message` tinytext,
				  `customer_ip` varchar(20) DEFAULT NULL,
				  `customer_hostname` varchar(255) DEFAULT NULL,
				  UNIQUE KEY `moka_transindex` (`trans_id`,`order_id`)
				) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8
		";
		
		$kur->run($sql);

		$sql="ALTER TABLE  `".$kur->getTable('moka_payment_info')."` CHANGE  `data_1_send`  `data_1_send` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
				CHANGE  `data_1_response`  `data_1_response` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
				CHANGE  `data_2_send`  `data_2_send` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
				CHANGE  `data_2_response`  `data_2_response` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
	
		$kur->run($sql);
	}

	// kurulum sonu
	Mage::getSingleton('checkout/session')->addSuccess(__("GriNet Moka ilk kurulumu yapıldı..."));
	$kur->endSetup();


?>