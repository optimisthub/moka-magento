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

class Moka_Payment_Model_Options_SslCipherModes
{
    public function toOptionArray()
    {
    	$options = array(
						"none"					=> __("Kapalı"),
						"TLSv1"					=> __("TLSv1"),
						"SSLv3"					=> __("SSLv3"),
						"rsa_rc4_128_sha"		=> __("rsa_rc4_128_sha"),
						);
		return $options;
    }
}
