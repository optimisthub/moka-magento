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
class Moka_Payment_Model_Quote_Item_Abstract extends Mage_Sales_Model_Quote_Item
{

    /**
     * Calculate item row total price
     *
     * @return Mage_Sales_Model_Quote_Item
     */
    public function getCalculationPrice2()
    {
		$price = $old_price = $this->_getData('calculation_price');

		$this->setData('calculation_price', $price);
        if (is_null($price)) {
            if ($this->hasOriginalCustomPrice()) {
                $price = $this->getOriginalCustomPrice();
            } else {
                $price = $this->getOriginalPrice();
            }
            $this->setData('calculation_price', $price);
        }
		
		$session=Mage::getSingleton('checkout/session');
		$moka = Mage::getModel('Moka_Payment_Model_Moka');
		$price=$moka->taksit_hesapla_urun($price);
		$_SESSION["moka"]["hesaplanan"]["taksit"]=$session->getTaksit();
		$_SESSION["moka"]["hesaplanan"]["banka"]="moka-".$session->getBanka();

		$this->setData('calculation_price', $price);

		return $price;
    }

    public function getCalculationPrice()
    {
		$price = $old_price = $this->_getData('calculation_price');

        if (is_null($price)) {
			$price = $old_price = $this->getOriginalPrice();
			$this->setData('calculation_price', $price);
		}
		
		$session=Mage::getSingleton('checkout/session');

		if (@$_SESSION["moka"]["hesaplanan"]["taksit"] != $session->getTaksit() or @$_SESSION["moka"]["hesaplanan"]["banka"] != $session->getBanka()) 
		{
			$moka = Mage::getModel('Moka_Payment_Model_Moka');
			$price=$moka->taksit_hesapla_urun($price);
			$_SESSION["moka"]["hesaplanan"]["taksit"]=$session->getTaksit();
			$_SESSION["moka"]["hesaplanan"]["banka"]=$session->getBanka();

			$this->setData('calculation_price', $price);

		} // if sonu
		
		return $price;
    }

	public function calcRowTotal()
    {
		$qty        = $this->getTotalQty();
        $total      = $this->getCalculationPrice2()*$qty;
        $baseTotal  = $this->getBaseCalculationPrice()*$qty;

		$this->setRowTotal($total);
        $this->setBaseRowTotal($baseTotal);
		//$this->setShippingAmount('33');
		return $this;
    }

}