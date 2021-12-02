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

/**
 * Installment Months source model
 */
class Moka_Payment_Model_Options_RedirectAfterFail
{
    public function toOptionArray()
    {
    	$options = array(
							"cart"		=>	array("value"=>"cart",		"label"=>__("Sepete Yönlendir")),
							"checkout"	=>	array("value"=>"onepage",	"label"=>__("Alışveriş Sonlandırmaya Yönlendir")),
						);
		return $options;
    }
}

/*

*/