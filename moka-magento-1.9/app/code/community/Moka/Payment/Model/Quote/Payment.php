<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/

class Moka_Payment_Model_Quote_Payment extends Mage_Sales_Model_Quote_Payment
{

	public function importData(array $data)
    {

		$data = new Varien_Object($data);
        Mage::dispatchEvent(
            $this->_eventPrefix . '_import_data_before',
            array(
                $this->_eventObject=>$this,
                'input'=>$data,
            )
        );

        $this->setMethod($data->getMethod());
        $method = $this->getMethodInstance();

		$method->assignData($data);

        /**
         * Payment avalability related with quote totals.
         * We have recollect quote totals before checking
         */
		//$this->getQuote()->collectShipping()->collectTotals();
        $this->getQuote()->collectTotals();

        if (!$method->isAvailable($this->getQuote())) {
            Mage::throwException(Mage::helper('sales')->__('The requested Payment Method is not available.'));
        }

        /*
        * validating the payment data
        */
        $method->validate();
        return $this;
    } 

} // class sonu
