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

class Moka_Payment_Model_Options_StatusesNew
{
    public function toOptionArray()
    {
    	$options = array(
//						"canceled"			=> __("Canceled"),
//						"closed"			=> __("Closed"),
//						"complete"			=> __("Complete"),
						"new"				=> __("New"),
//						"holded"			=> __("On Hold"),
//						"payment_review"	=> __("Payment Review"),
						"pending_payment"	=> __("Pending Payment"),
//						"processing"		=> __("Processing"),
						);
		return $options;
    }
}
