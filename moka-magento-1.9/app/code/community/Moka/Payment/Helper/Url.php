<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidonet@gmail.com>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/


class Moka_Payment_Helper_Url extends Mage_Core_Helper_Url
{
    /**
     * Retrieve current url
     *
     * @return string
     */
    public function getHomeUrl()
    {
        return Mage::getBaseUrl();
    }

}
