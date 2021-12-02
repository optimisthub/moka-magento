<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/

class Moka_Payment_Adminhtml_LogsController extends Mage_Adminhtml_Controller_action
{

	public function indexAction() {

		$this->loadLayout()
			->_setActiveMenu('moka/directpay-log-history')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Moka Payment'), Mage::helper('adminhtml')->__('Kredi Kartı İşlem Geçmişi'));

		$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('Moka_Payment_Block_Adminhtml_Logs'));

		$this->renderLayout();

	}

}