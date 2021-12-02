<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidonet@gmail.com>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/
?><?php


class Moka_Payment_Block_Moka_Cc extends Mage_Payment_Block_Form_Cc
{
	protected function _construct()
	{
		$moka = Mage::getModel('Moka_Payment_Model_Moka');
		$this->setLicense($moka->lisans_durumu());

		switch(Mage::getStoreConfig('moka/settings/connection_type')){
			case	"oos":		
						$this->setTemplate("moka_payment/cc_form_oos.phtml");
						break;
			default :	
						$this->setTemplate("moka_payment/cc_form.phtml");
						break;
		}
	
	}
	
}