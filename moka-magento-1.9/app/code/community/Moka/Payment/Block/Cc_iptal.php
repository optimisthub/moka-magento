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


class Moka_Payment_Block_Cc extends Mage_Payment_Block_Form_Cc
{
	protected function _construct()
	{
		$moka = Mage::getModel('Moka_Payment_Model_Grinet');
		$this->setLicense($moka->lisans_durumu());

		$this->setTemplate("moka_payment/cc_form.phtml");
	}
}