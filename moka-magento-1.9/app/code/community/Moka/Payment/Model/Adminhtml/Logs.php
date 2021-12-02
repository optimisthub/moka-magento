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

class Moka_Payment_Model_Adminhtml_Logs
{
	public function LogHistory()
    {
		$cust_model	= Mage::getModel('customer/customer');
		$res		= Mage::getSingleton('core/resource');
		$db			= $res->getConnection('core_write');
		$table		= $res->getTableName('Moka_Payment_info');

		$sql		= "SELECT * FROM ".$table." WHERE trans_date IS NOT NULL and payment_type='siparis' ORDER BY trans_id DESC";
		$res1		= $db->query($sql);

		// Müşterinin Tüm işlemleri //////////////////////////////////////////////////
		$items=array();
		while ($data=$res1->fetch()) 
		{

			if ($data['customer_id'] < 1) 
			{
				$cus	=	$cust_model->load($data['customer_id']);			
				$data["customer_name"] = $cus->getName();
			} // if sonu
			else 
			{
				$data["customer_name"] = __("Guest");
			} // else sonu
			
			$items[]=$data;
		} // while sonu

		return array("items" => $items);
	}
        
	public function LogHistoryDirect()
    {
		$cust_model	= Mage::getModel('customer/customer');
		$res		= Mage::getSingleton('core/resource');
		$db			= $res->getConnection('core_write');
		$table		= $res->getTableName('Moka_Payment_info');

		$sql		= "SELECT * FROM ".$table." WHERE trans_date IS NOT NULL and payment_type='cari' ORDER BY trans_id DESC";
		$res1		= $db->query($sql);

		// Müşterinin Tüm işlemleri //////////////////////////////////////////////////
		$items=array();
		while ($data=$res1->fetch()) 
		{

			if ($data['customer_id'] < 1) 
			{
				$cus	=	$cust_model->load($data['customer_id']);			
				$data["customer_name"] = $cus->getName();
			} // if sonu
			else 
			{
				$data["customer_name"] = __("Guest");
			} // else sonu
			
			$items[]=$data;
		} // while sonu

		return array("items" => $items);
	}
        
}