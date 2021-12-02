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

class Moka_Payment_Model_Options_MokaCurrencies
{
    public function toOptionArray()
    {
    	$options = array(
							"TRY"	=>	array("value"=>"TRY","label"=>__("TRY - Türk Lirası (Yeni)")),
							"EUR"	=>	array("value"=>"EUR","label"=>__("EUR - Euro")),
							"USD"	=>	array("value"=>"USD","label"=>__("USD - Amerikan Doları")),
						);
		return $options;
    }
}

/*

*/