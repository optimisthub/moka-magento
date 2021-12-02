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

/**
 * Installment Months source model
 */
class Moka_Payment_Model_Options_CcFormTemplates
{
    public function toOptionArray()
    {
    	$options = array(
							"cc_form.phtml"	=>	array(
													"value"=>"cc_form.phtml",
													"label"=>__("Normal CC Form (cc_form.phtml)")),

							"cc_form_onestepcheckout.phtml"	=>	array(
													"value"=>"cc_form_onestepcheckout.phtml",
													"label"=>__("OnestepCheckout için CC Form (cc_form_onestepcheckout.phtml)")),

							"cc_form_bankasiz.phtml"=>	array(
													"value"=>"cc_form_bankasiz.phtml",
													"label"=>__("Bankasız ve Taksitsiz CC Form (cc_form_bankasiz.phtml)")),
						);
		return $options;
    }
}

/*

*/