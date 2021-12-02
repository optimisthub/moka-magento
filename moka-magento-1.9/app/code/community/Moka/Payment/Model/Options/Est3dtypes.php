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
class Moka_Payment_Model_Options_Est3dtypes
{
    public function toOptionArray()
    {
    	$options = array(
						"no-3d"			=> __("3d Secure Yok ( Klasik Tahsilat )"),
						"3d"			=> __("3d Model"),
						"3d_pay"		=> __("3d Pay Modeli"),
						"3d_pay_full"	=> __("3d Pay Modeli (FULL Auth)"),
						"3d_pay_half"	=> __("3d Pay Modeli (HALF Auth)"),
//						"3d_hosting"=>__("3d Ortak Ödeme Sayfası")
						);
		return $options;
    }
}
