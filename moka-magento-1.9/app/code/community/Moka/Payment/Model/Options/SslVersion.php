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
class Moka_Payment_Model_Options_SslVersion
{
    public function toOptionArray()
    {
    	$options = array(
						""					=> __("Kapalı"),
						"0"					=> __("DEFAULT	-- Varsayılan SSL versiyonu."),
						"1"					=> __("TLSv1 -- TLSv1.x"),
						"2"					=> __("SSLv2 -- SSLv2"),
						"3"					=> __("SSLv3 -- SSLv3"),
						"4"					=> __("TLSv1_0	-- TLSv1.0 (v7.34.0 ile eklendi)"),
						"5"					=> __("TLSv1_1	-- TLSv1.1 (Added in 7.34.0 ile eklendi)"),
						"6"					=> __("TLSv1_2	-- TLSv1.2 (Added in 7.34.0 ile eklendi)"),
						);
		return $options;
    }
}
