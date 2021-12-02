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

class Moka_Payment_Block_Moka_Info extends Mage_Payment_Block_Info
{    
	// #######################################################################################################
	protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('moka_payment/cc_info.phtml');
    }

	// #######################################################################################################
	public function getOrderId()
    {

		$order_id = $this->getRequest()->getParam('order_id');

		//echo "curr:".$order_id."-".__LINE__;

		if ($order_id == "" and $this->getQuote()) 
		{
			$order_id = $this->getQuote()->getId();
		} // if sonu

		if ($order_id == "" and Mage::registry('current_order') != "") 
		{
			$order_id=Mage::registry('current_order')->getId();
		} // if sonu
		
		if ($order_id == "" and $this->getQuote()) 
		{
			$order_id = $this->getQuote()->getId();
		} // if sonu

		if ($order_id == "" and $this->getInfo()->getOrder()) 
		{
			$order_id = $this->getInfo()->getOrder()->getId();
		} // if sonu

		if ($order_id == "" and $this->getOrderDetails()) 
		{
			$order_id = $this->getOrderDetails()->getId();
		} // if sonu
		 
		if ($order_id == "") 
		{
			$order_id = Mage::getSingleton('checkout/session')->getLastRealOrderId();
		} // if sonu

		return $order_id;

	}//Function Sonu
    
    
	// #######################################################################################################
    public function toPdf()
    {
        $this->setTemplate('payment/info/pdf/cc.phtml');
        return $this->toHtml();

	}//Function Sonu
    
	// #######################################################################################################
	public function getOrderx()
    {
        return Mage::registry('current_order');
    }

}