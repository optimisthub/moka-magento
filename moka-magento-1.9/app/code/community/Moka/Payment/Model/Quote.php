<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidayet@grinet.com.tr>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/


class Moka_Payment_Model_Quote extends Mage_Sales_Model_Quote
{

	public function collectTotals()
    {
		
		/**
         * Protect double totals collection
         */
        if ($this->getTotalsCollectedFlag()) {
            return $this;
        }
        Mage::dispatchEvent(
            $this->_eventPrefix . '_collect_totals_before',
            array(
                $this->_eventObject=>$this
            )
        );

        $this->setSubtotal(0);
        $this->setBaseSubtotal(0);

        $this->setSubtotalWithDiscount(0);
        $this->setBaseSubtotalWithDiscount(0);

        $this->setGrandTotal(0);
        $this->setBaseGrandTotal(0);

        foreach ($this->getAllAddresses() as $address) {
            $address->setSubtotal(0);
            $address->setBaseSubtotal(0);

            $address->setGrandTotal(0);
            $address->setBaseGrandTotal(0);

            $address->collectTotals();

            $this->setSubtotal((float) $this->getSubtotal()+$address->getSubtotal());
            $this->setBaseSubtotal((float) $this->getBaseSubtotal()+$address->getBaseSubtotal());

            $this->setSubtotalWithDiscount((float) $this->getSubtotalWithDiscount()+$address->getSubtotalWithDiscount());
            $this->setBaseSubtotalWithDiscount((float) $this->getBaseSubtotalWithDiscount()+$address->getBaseSubtotalWithDiscount());

            $this->setGrandTotal((float) $this->getGrandTotal()+$address->getGrandTotal());
            $this->setBaseGrandTotal((float) $this->getBaseGrandTotal()+$address->getBaseGrandTotal());
        }

        Mage::helper('sales')->checkQuoteAmount($this, $this->getGrandTotal());
        Mage::helper('sales')->checkQuoteAmount($this, $this->getBaseGrandTotal());

        $this->setItemsCount(0);
        $this->setItemsQty(0);
        $this->setVirtualItemsQty(0);

        foreach ($this->getAllVisibleItems() as $item) {
            if ($item->getParentItem()) {
                continue;
            }

            if (($children = $item->getChildren()) && $item->isShipSeparately()) {
                foreach ($children as $child) {
                    if ($child->getProduct()->getIsVirtual()) {
                        $this->setVirtualItemsQty($this->getVirtualItemsQty() + $child->getQty()*$item->getQty());
                    }
                }
            }

            if ($item->getProduct()->getIsVirtual()) {
                $this->setVirtualItemsQty($this->getVirtualItemsQty() + $item->getQty());
            }
            $this->setItemsCount($this->getItemsCount()+1);
            $this->setItemsQty((float) $this->getItemsQty()+$item->getQty());
        }

        $this->setData('trigger_recollect', 0);
        $this->_validateCouponCode();

        Mage::dispatchEvent(
            $this->_eventPrefix . '_collect_totals_after',
            array(
                $this->_eventObject=>$this
            )
        );

        $this->setTotalsCollectedFlag(true);
        return $this;
    }

	// #######################################################################################################
	public function collectShipping() {
	
		$address = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
		$tutar=strval($address->getBaseShippingAmount());
		
		//Mage::log(basename(__FILE__)."-".__LINE__."-".__FUNCTION__." // Ship:$tutar");
		
		if ($tutar > 0) 
		{
			$moka = Mage::getModel('Moka_Payment_Model_Moka');
			$tutar=$moka->taksit_hesapla_urun($tutar);
		} // if sonu

		$address->setShippingAmount($tutar);
        return;
		
	} // function sonu #######################################################################################
	
} // class sonu
