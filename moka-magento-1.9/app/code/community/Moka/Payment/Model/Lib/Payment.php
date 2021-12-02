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

class Moka_Payment_Model_Lib_Moka extends Mage_Payment_Model_Method_Abstract
{

	### Function #############################################################
	public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
	### Function Sonu ###########

	// #######################################################################################################
	public function print_form($form) {

		return;
	} // function sonu #########


} // class sonu
