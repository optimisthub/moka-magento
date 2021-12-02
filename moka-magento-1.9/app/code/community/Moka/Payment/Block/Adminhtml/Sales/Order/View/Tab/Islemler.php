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

class	Moka_Payment_Block_Adminhtml_Sales_Order_View_Tab_Islemler 
	extends	Mage_Adminhtml_Block_Template  
	implements	Mage_Adminhtml_Block_Widget_Tab_Interface
{
	protected $_order;
	
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('moka_payment/islemler.phtml');
    }

    public function getOrder()
    {
    	if (null === $this->_order) {
    		return Mage::registry('current_order');
    	}
    	return $this->_order;
    }
    
    public function setOrder($order)
    {
    	$this->_order = $order;
    }
    
    public function Islemler()
    {
    	//$items = Mage::getModel('picklist/order_picklist_items');
    	$islemler = Mage::getModel('moka/adminhtml_islemler');
    	return $islemler->Islemler();
    }
    
    public function getTabLabel()
    {
        return Mage::helper('sales')->__('moka.com Ödemeleri');
    }

    public function getTabTitle()
    {
        return Mage::helper('sales')->__('moka.com Ödemeleri');
    }

    public function getTabClass()
    {
        return 'ajax only';
    }

    public function getClass()
    {
        return $this->getTabClass();
    }

    public function getTabUrl()
    {
        return $this->getUrl('adminhtml/moka_payment_sales_order/moka_islemler', array('_current' => true));
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}