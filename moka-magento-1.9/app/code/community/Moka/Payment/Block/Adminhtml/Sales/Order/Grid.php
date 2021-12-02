<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/


/*
class Moka_Payment_Adminhtml_Mokaislemler_Block_Customer_Edit_Tabs extends Mage_Adminhtml_Block_Customer_Edit_Tabs 
*/
class Moka_Payment_Block_Adminhtml_Sales_Order_Grid	extends Mage_Adminhtml_Block_Sales_Order_Grid 
{

	private $_collection2 = "";

	protected function _prepareCollection()
    {
		$_collection2 = Mage::getResourceModel($this->_getCollectionClass());
		$_collection2->addAttributeToSelect('*');
		$_collection2->addFieldToFilter(
			'status', array('in' => 
				array(
						'pending',
						'complete',
						'closed',
						'havale_bekleniyor',
						'processing',
						'canceled',

						'canceled_turkpay',
						'processing_turkpay',
						'provizyon_bekleniyor',
						'pending_turkpay',
						'payment_review_turkpay',
						'pending_payment_turkpay',
						'pending_payment_turkpay_queue',

						'pending_moka',
						'payment_review_moka',
						'pending_payment_moka',
						'pending_payment_moka_queue',

						'pending_ipara',
						'payment_review_ipara',
						'pending_payment_ipara',
						'pending_payment_ipara_queue',
					)
				)
			);	//pending,complete

		$this->setCollection2($_collection2);
        return parent::_prepareCollection();
    }

	public function setCollection($collection)
	{
		parent::setCollection($this->collection2);
	}
}
