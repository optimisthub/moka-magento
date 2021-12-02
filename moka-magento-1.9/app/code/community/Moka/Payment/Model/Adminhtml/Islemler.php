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

class Moka_Payment_Model_Adminhtml_Islemler
{
	protected $_order;
	
	public function setOrder($order)
	{
		$this->_order = $order;
	}
	
	public function getOrder()
	{
		return $this->_order;
	}
	
	public function Islemler()
    {
        $order_id	= Mage::app()->getRequest()->getParam('order_id');
        $order		= Mage::getModel('sales/order')->load($order_id);
		$quote_id	= $order->getQuoteId();
		$customer_id = $order->getCustomerId();

		$res		= Mage::getSingleton('core/resource');
		$db			= $res->getConnection('core_write');
		$table		= $res->getTableName('moka_payment_info');

		$sql		= "SET NAMES utf8";
		$res1		= $db->query($sql);

		$sql		= "SELECT * FROM ".$table." WHERE quote_id = '".$quote_id."' ORDER BY trans_id DESC";
		$res1		= $db->query($sql);

		// Bu siparişteki İŞLEMLER //////////////////////////////////////////////////
		$items=array();
		while ($data=$res1->fetch()) 
		{
			$items[]=$data;
		} // while sonu

		// Eski İşlemler ///////////////////////////////////////////////////////////
		$sql		= "SELECT * FROM ".$table." WHERE customer_id = '".$customer_id."' AND quote_id <> '".$quote_id."' ORDER BY trans_id DESC LIMIT 20";
		$res2		= $db->query($sql);

		$old_items=array();
		while ($data=$res2->fetch()) 
		{
			$oid=intval(trim($data["order_id"]));
			if ($oid > 0) 
			{
				$orderx = Mage::getModel('sales/order')->loadByAttribute('increment_id', $oid);
				$data["real_order_id"]=$orderx->getId();
				$data["real_increment_id"]=$orderx->getIncrementId();

				$old_items[]=$data;
			} // if sonu
			
		} // while sonu

		return array($items,$old_items);

	}
    
}