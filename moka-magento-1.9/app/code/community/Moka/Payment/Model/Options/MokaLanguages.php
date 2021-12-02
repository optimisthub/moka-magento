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

class Moka_Payment_Model_Options_MokaLanguages
{
    public function toOptionArray()
    {
		$options = array(
							"TR"	=>	array("value"=>"TR","label"=>__("Türkçe")),
							"EN"	=>	array("value"=>"EN","label"=>__("İngilizce")),
							"RO"	=>	array("value"=>"RO","label"=>__("Romence")),
							"HU"	=>	array("value"=>"HU","label"=>__("Macarca")),
							"RU"	=>	array("value"=>"RU","label"=>__("Rusça")),
							"DE"	=>	array("value"=>"DE","label"=>__("Almanca")),
							"FR"	=>	array("value"=>"FR","label"=>__("Fransızca")),
							"IT"	=>	array("value"=>"IT","label"=>__("İtalyanca")),
							"ES"	=>	array("value"=>"ES","label"=>__("İspanyolca")),
						);
		return $options;
    }
}

/*

*/