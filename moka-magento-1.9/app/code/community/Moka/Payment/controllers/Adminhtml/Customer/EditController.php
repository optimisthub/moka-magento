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

class Moka_Payment_Adminhtml_Customer_EditController extends Mage_Adminhtml_Controller_Action
{
    protected function _construct()
    {
        $this->setUsedModuleName('Mage_Customer');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('customer')
            ->_addBreadcrumb($this->__('Customer'), $this->__('Customer'))
            ->_addBreadcrumb($this->__('Edit'), $this->__('Edit'));
        return $this;
    }

    public function historyAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('moka/adminhtml_customer_edit_tab_directpaycustomerhistory')->toHtml()
        );
    }
}