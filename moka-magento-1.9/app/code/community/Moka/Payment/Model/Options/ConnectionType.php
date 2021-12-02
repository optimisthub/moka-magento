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
class Moka_Payment_Model_Options_ConnectionType
{
    public function toOptionArray()
    {
    	$options = array(
						"xml"	=> __("XML"),
						"oos"	=> __("Ortak Ödeme Sayfası"),
						);
		return $options;
    }
}
