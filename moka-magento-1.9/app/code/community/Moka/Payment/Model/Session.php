<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/

class Moka_Payment_Model_Session extends Mage_Core_Model_Session_Abstract
{
	//protected $_ticket;

   	public function __construct()
   	{
   		//parent::__construct();
   		$this->init('moka');
   	}
	
	
}