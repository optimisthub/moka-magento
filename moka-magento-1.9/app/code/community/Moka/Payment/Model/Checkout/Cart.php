<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.
*/

class Moka_Payment_Model_Checkout_Cart extends Mage_Checkout_Model_Cart 
{

	public function init()
    {
		$session = Mage::getSingleton('checkout/session');
		$session->unsetData('taksit');
		$session->unsetData('banka');
		$session->unsetData('moka_taksit');
		$session->unsetData('moka_banka');

//		print "<pre>";
//		print_r($session->getData());
//		print "</pre>";
	
		parent::init();
	}

}
