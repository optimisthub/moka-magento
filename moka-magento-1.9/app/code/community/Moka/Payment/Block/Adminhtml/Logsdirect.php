<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidonet@gmail.com>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/
?><?php

class Moka_Payment_Block_Adminhtml_Logsdirect
    extends Mage_Adminhtml_Block_Template
{

    protected function _construct()
    {
		parent::_construct();
        $this->setTemplate('moka_payment/log_history_direct.phtml');
    }

    public function LogHistoryDirect()
    {
    	$history = Mage::getModel('moka/adminhtml_logs');
    	return $history->LogHistoryDirect();
    }
    
}