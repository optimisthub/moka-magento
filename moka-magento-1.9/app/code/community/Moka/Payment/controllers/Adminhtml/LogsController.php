<?php
/**

	Uretici..........: Moka 
	Yaz�l�m�n Ad�....: Moka_Payment // moka.com i�in Magento Tahsilat Mod�l�
	Geli�tiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyar� /////////////////////////////////////////////////////////////////////////

	T�m haklar� MOKA �DEME KURULU�U A.�.'ne aittir.

*/

class Moka_Payment_Adminhtml_LogsController extends Mage_Adminhtml_Controller_action
{

	public function indexAction() {

		$this->loadLayout()
			->_setActiveMenu('moka/directpay-log-history')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Moka Payment'), Mage::helper('adminhtml')->__('Kredi Kart� ��lem Ge�mi�i'));

		$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('Moka_Payment_Block_Adminhtml_Logs'));

		$this->renderLayout();

	}

}