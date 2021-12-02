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

class Moka_Payment_MokaController extends Mage_Core_Controller_Front_Action
{

	// #######################################################################################################
	public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	private function prepare_init_data() {
	
		$moka			= Mage::getSingleton('Moka_Payment_Model_Moka');
		$moka->banka_id	= 'moka';

		$order_model	= Mage::getModel('sales/order');
      	$session		= Mage::getSingleton('checkout/session');
      	$resource		= Mage::getSingleton('core/resource');

		$this->form = [];

		if (Mage::getStoreConfig($moka->banka_id.'/settings/api_test_mode') == 1)
		{
			$this->form["api_url"]	= 'https://service.testmoka.com';
			$this->trans_type		= "test";
		}
		else
		{
			$this->form["api_url"]	= 'https://service.moka.com';
			$this->trans_type		= "live";
		}

		if (Mage::getSingleton('customer/session')->isLoggedIn())
		{
			$this->customer		= Mage::getSingleton('customer/session')->getCustomer();
			$this->customer_id	= Mage::getSingleton('customer/session')->getCustomerId();
		} // if sonu	
		else
		{
			$this->customer_id = "-1";
		} // else sonu

		if (empty($session->getLastQuoteId()))
		{
			$url=Mage::getUrl("/");
			$moka->yonlendir($url);
			die();
		} // if sonu
				
		$this->is_3d = false;
		if (Mage::getStoreConfig('moka/settings/mode3d') == '3d') 
		{
			$this->is_3d = true;
		} // if sonu

		$this->quote = Mage::getModel('sales/quote')->load($session->getLastQuoteId());

		$this->customer_id	= Mage::getSingleton('customer/session')->getCustomerId();

		$this->order_id=$this->getCheckout()->getLastRealOrderId();
		$this->order = $order_model->loadByIncrementId($this->order_id);

		if ($this->order->getId() < 1)
		{
			$session->addError(__("Hata : Son işlemden sonra çok uzun süre geçtiği için işlem yeniden yapılmalıdır.").__LINE__);
			$moka->resetLogId(); // Log id silinir
			$this->yonlendir(Mage::getUrl('checkout/cart',array('_secure'=>true)));
			return;
		} // if sonu

		$this->bank_order_id = $moka->get_bank_order_id($this->order_id);
		
		$this->form["dealer_code"]		= Mage::getStoreConfig('moka/settings/dealer_code');
		$this->form["dealer_user"]		= Mage::getStoreConfig('moka/settings/dealer_user');
		$this->form["dealer_pass"]		= Mage::getStoreConfig('moka/settings/dealer_password');

		$this->form["amount"]		= str_replace(",","",number_format(strval($this->quote->getGrandTotal()),2,".",""));
		$this->customer_ip			= $moka->get_ip();

		// Adres Bilgileri
		$a = $this->quote->getBillingAddress();		// Fatura Adresi Bilgileri

		$this->fbill = array(
			'email'					=> $a->getEmail(),
            //'company'				=> $a->getCompany(),
            'first_name'			=> $a->getFirstname(),
            'last_name'				=> $a->getLastname(),
            'tel'					=> $a->getTelephone(),
            //'fax'					=> $a->getFax(),
            'address1'				=> $a->getStreet(1),
            'address2'				=> $a->getStreet(2),
            'city'					=> $a->getCity(),
            'state'					=> $a->getRegion(),
            'country'				=> $a->getCountry(),
            'zip'					=> $a->getPostcode(),
        );

		$toplam=0;

		$this->form["taksit"]			= $session->getData('moka_taksit');
		$this->form["taksit_adi"]		= $session->getData('moka_taksit_adi');
		$this->form["cc_owner"]			= $session->getData('ccOwner');
		$this->form["cc_no"]			= $session->getData('ccNumber');
		$this->form["cc_cvc"]			= $session->getData('cv2');
		$this->form["cc_expire_month"]	= str_repeat("0",2-strlen($session->getData('expMonth'))).$session->getData('expMonth');
		$this->form["cc_expire_year"]	= $session->getData('expYear');
		$this->form["cc_expire"]		= $this->form["cc_expire_year"]."-".$this->form["cc_expire_month"]."-01";

		if (intval($this->form['taksit']) < 2) 
		{
			$this->form['taksit'] = 1;
		} // if sonu

		// Taksit bilgileri siparise ve quote'a kaydedilsin
		$this->order->getPayment()->setData('moka_taksit',$this->form["taksit"]);
		$this->quote->getPayment()->setData('moka_taksit',$this->form["taksit"]);

		$this->order->getPayment()->setData('moka_taksit_adi',$this->form["taksit_adi"]);
		$this->quote->getPayment()->setData('moka_taksit_adi',$this->form["taksit_adi"]);

		$this->order->save();
		$this->quote->save();

		if ($this->fbill["email"] == "") {$this->fbill["email"]=$this->customer->getEmail();} // if sonu

		$timestamp = Mage::getModel('core/date')->timestamp(time());
		$this->tarih = date("Y-m-d H:i:s", $timestamp);

		$this->check_key_text = $this->form['dealer_code'] . 'MK' . $this->form['dealer_user'] . 'PD' . $this->form['dealer_pass'];

		$this->check_key = hash('sha256',$this->check_key_text);
		
		// Log kayit islemi
		/// $moka->resetLogId();

		$log_arr=array(
						"trans_date"				=> date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
						"customer_id"				=> $this->customer_id,
						"order_id"					=> $this->order->getId(),
						"quote_id"					=> $this->quote->getId(),
						"banka_order_id"			=> $this->bank_order_id,
						"moka_banka" 				=> "",
						"moka_taksit"				=> $this->form["taksit"],
						"moka_taksit_adi"			=> $this->form["taksit_adi"],
						"sub_total"					=> $this->order->getBaseSubtotal(),
						"grand_total"				=> $this->order->getTotalDue(),
						"moka_total" 				=> $this->order->getTotalDue(),
						);

		$moka->logUpdate($log_arr);

		$log_id						= $moka->getLogId();
		$log_protect_key			= $moka->getLogProtectKey();
		$url_params					= array(
											'_secure'	=> true,
											'_nosid'	=> true,
											'_current'	=> true,
											'tp'		=> $log_protect_key,
											'tid'		=> $log_id, 
											);

		$this->form["ok_url"]		= Mage::getUrl('grmoka/moka/moka_return', $url_params);	
		$this->form["fail_url"]		= $this->form["ok_url"];		

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function moka_paymentAction() {

		$this->show_lutfen_bekleyiniz();
		
		$moka = Mage::getSingleton('Moka_Payment_Model_Moka');

		$this->prepare_init_data();

		$data_to_send = array(
		   'PaymentDealerAuthentication' => array(
				'DealerCode'	=> $this->form['dealer_code'],
				'Username'		=> $this->form['dealer_user'],
				'Password'		=> $this->form['dealer_pass'],
				'CheckKey'		=> $this->check_key,
			),
		   'PaymentDealerRequest' => array(
				'CardHolderFullName' => $this->form["cc_owner"],
				'CardNumber' => $this->form["cc_no"],
				'ExpMonth' => $this->form["cc_expire_month"],
				'ExpYear' => $this->form["cc_expire_year"],
				'CvcNumber' => $this->form["cc_cvc"],
				'Amount' => $this->form["amount"],
				'Currency' => 'TL',
				'InstallmentNumber' => $this->form['taksit'],
				'ClientIP' => $moka->get_ip(),
				'OtherTrxCode' => $this->bank_order_id,
				'IsPoolPayment' => 0,
				'IntegratorId' => '',
				'Software' => 'Magento',
				'Description' => $this->order->getIncrementId().' -- '.html_entity_decode($this->fbill["first_name"].' '.$this->fbill["last_name"], ENT_QUOTES, 'UTF-8'),
				'BuyerInformation' =>
				array(
					'BuyerFullName' => html_entity_decode($this->fbill["first_name"].' '.$this->fbill["last_name"], ENT_QUOTES, 'UTF-8'),
					'BuyerEmail' => $this->fbill['email'],
					'BuyerGsmNumber' => $this->fbill['phone'],
					'BuyerAddress' => $this->fbill["address1"]." ".$this->fbill["address2"]." ".$this->fbill["zip"]." ".$this->fbill["city"]." ".$this->fbill["state"]." ".$this->fbill["country"],
				),
			),
		);

		if ($this->is_3d === true) 
		{
			$data_to_send['PaymentDealerRequest']['RedirectUrl'] = $this->form['ok_url'];
			$data_to_send['PaymentDealerRequest']['RedirectType'] = 1;
		} // if sonu

		//// LOG ICIN FORMDAKI KRITIK VERILERI TEMIZLEME //////////////////////////////////////////////////////////////////
		$data_to_send_log = $data_to_send;
		$data_to_send_log['PaymentDealerAuthentication']['Password'] = 'XXXXXXXXXXXXXXX';
		$data_to_send_log['PaymentDealerRequest']['CardNumber'] = substr($this->form["cc_no"],0,6)."XXXXXX".substr($this->form["cc_no"],-4,4);
		$data_to_send_log['PaymentDealerRequest']['CvcNumber'] = 'XXX';

		$this->form_log	= var_export($data_to_send_log,true);

		$detected_country=$moka->ip_to_country($this->customer_ip);

		// Log kayit islemi
		$log_arr=array(
						"trans_date"				=> date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
						"data_1_send"				=> $this->form_log,
						);

		$moka->logUpdate($log_arr);

		/// Veri gönderilsin...

		$post_data = json_encode($data_to_send);

		$api_url = trim($this->form["api_url"].'/PaymentDealer/DoDirectPayment');			
		if ($this->is_3d === true) 
		{
			$api_url = trim($this->form["api_url"].'/PaymentDealer/DoDirectPaymentThreeD');			
		} // if sonu

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		
		// Exec
		$response = curl_exec($ch);
		$curlerrcode	= curl_errno($ch);
		$curlerr		= curl_error($ch);
		$curl_info		= curl_getinfo($ch);

		curl_close($ch);

		$hata=false;
		//unset($log_arr);
		
		if (!empty($curlerr) && !empty($curlerrcode)) {
			$log_arr=array(
							"data_1_response"		=> "İletişim sorunu nedeniyle işlem tamamlanamadı. \n Hata : ".$curlerr,
							);
			$moka->logUpdate($log_arr);

			$moka->resetLogId(); // Log id silinir
			$this->yonlendir(Mage::getUrl('checkout/cart',array('_secure'=>true)));
			return;
		}

		$moka_return = json_decode($response ,true);

		$log_arr=array(
						"data_1_response" => "\n\nRETURNED JSON: ".addslashes($response)."\n\nRETURNED ARR: ".var_export($moka_return,true)."\n\nCONNECT STATS:".addslashes(var_export($curl_info,true)),
						);

		$moka->logUpdate($log_arr);

		$moka_status = 0;

		if (@$moka_return["Data"]["IsSuccessful"] == 1) 
		{
			$moka_status = 1;
		} // if sonu

		unset($log_arr);

		$moka_message = @$moka_return["Data"]["ResultMessage"];
		if ($moka_message == '') 
		{
			$moka_message = @$moka_return["ResultMessage"];
		} // if sonu

		if ($moka_message == '') 
		{
			$moka_message = @$moka_return["ResultCode"];
		} // if sonu
		
		$log_arr=array(
						"moka_response_code"	=> @$moka_return["Data"]["ResultCode"],
						"moka_status"			=> $moka_status,
						"moka_message"			=> $moka_message,
						"moka_total"			=> $this->form["amount"],
						"moka_order_id"			=> @$moka_return["Data"]["VirtualPosOrderId"],
					);

		$moka->logUpdate($log_arr);
		
		// Log kayit islemi

		if ($this->is_3d === true)
		{
			if (@$moka_return["ResultCode"] == "Success" and !is_array(@$moka_return["Data"]) and substr(@$moka_return["Data"],0,8) == 'https://')
			{
				$this->yonlendir($moka_return["Data"]);
			} // if sonu
			else 
			{
				$this->order_fail_payment($moka_return);
			} // else sonu
		}
		else 
		{
			if ($moka_status == '1')
			{
				$this->order_success();
			}
			else
			{
				$this->order_fail_payment($moka_return);
			}
		} // else sonu


		return;

	}// function sonu #######################################################################################

	// #######################################################################################################
	public function order_fail_payment($moka_return) {

		$moka	= Mage::getSingleton('Moka_Payment_Model_Moka');

		$banka_id="moka";

		$lines=array();

		//	$except=$moka->error_code_exceptions();
		$err_codes_file	= Mage::getModuleDir('controllers', 'Moka_Payment')."/error_codes_moka.php";

		include_once($err_codes_file);

		$moka_message = @$moka_return["Data"]["ResultMessage"];
		if ($moka_message == '') 
		{
			$moka_message = @$moka_return["ResultMessage"];
		} // if sonu

		if ($moka_message == '') 
		{
			$moka_message = @$moka_return["ResultCode"];
		} // if sonu
		
		$log_arr=array(
						"moka_message" => $moka_message,
					);
		$moka->logUpdate($log_arr);

		$this->customer_error_msg = @$generr['sistem_hatasi_2'];

		$result_code_1 = @$moka_return["ResultCode"];

		if (substr($result_code_1,0,strlen('PaymentDealer.')) == 'PaymentDealer.' or $result_code_1 == 'EX') 
		{
			$error_msg			= @$error_codes[$result_code_1][0];
			$this->customer_error_msg	= @$error_codes[$result_code_1][1];
		} // if sonu
		
		$result_code_2 = @$moka_return["Data"]["ResultCode"];

		if (strlen($result_code_2) == 3 and is_numeric($result_code_2)) 
		{
			$this->customer_error_msg	= @$error_codes_3_digit[$result_code_2][1];
			$error_msg			= @$moka_return["Data"]["ResultMessage"];
		} // else sonu

		if ($this->customer_error_msg != "" and $this->customer_error_msg != __($this->customer_error_msg))
		{
			$this->customer_error_msg=__($this->customer_error_msg);
		} // else sonu

		if ($this->customer_error_msg == "")
		{
			$this->customer_error_msg=__('Kredi kartınızdan tahsilat yapılırken bir sorun oluştu. Ancak bu sorunun detayına şu an ulaşılamıyor. Bu sorunla ilgili detayları öğrenmek için lütfen site yönetimi ile irtibata geçiniz.');
		} // if sonu

		// Log islemi
		$log_arr=array(
						"customer_message"		=> $this->customer_error_msg,
						"error_message"			=> $error_msg,
						);
		$moka->logUpdate($log_arr);

		// Siparise mesaj ekleniyor
		$this->getCheckout()->setLastSuccessQuoteId(null);

		if (isset($this->quote) and is_object($this->quote)) 
		{
			$this->quote->setIsActive(true)->save();
		} // if sonu
	
		// Siparis iptal ediliyor
		if (isset($this->order) and is_object($this->order)) 
		{
			$this->order->cancel()->save();

			if (Mage::getStoreConfig('payment/moka_payment/use_custom_order_statuses') == 1)
			{
				$moka_status_ek = "_moka";
			} // if sonu
			else
			{
				$moka_status_ek = "";
			} // else sonu

			$this->order->setStatus('canceled'.$moka_status_ek)->save();

		} // if sonu
		
		//$moka->deleteOrder($this->order_id);
		$moka->resetLogId(); // Log id silinir...
		
		Mage::getSingleton('checkout/session')->addError(__("Tahsilat işlemi yapılamadı. Oluşan hata : ").$this->customer_error_msg);

		if (Mage::getStoreConfig('payment/moka_payment/redirect_after_fail') == "checkout")
		{
			$this->yonlendir(Mage::getUrl('checkout/onepage/',array('_secure'=>true)));
		} // if sonu
		else
		{
			$this->yonlendir(Mage::getUrl('checkout/cart',array('_secure'=>true)));
		} // else sonu

		exit();

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function order_success() {
		
		$comment = null;

		$banka_id="moka";

		$session	= Mage::getSingleton('checkout/session');

		$moka	= Mage::getSingleton('Moka_Payment_Model_Moka');

		if (isset($this->quote) and is_object($this->quote)) 
		{
			$this->quote_id	= $this->quote->getId();
		} // if sonu
	
		// Siparis iptal ediliyor
		if (isset($this->order) and is_object($this->order)) 
		{
			$this->order_id	= $this->order->getId();
			$this->order->place();

			/// Fatura oluşturulacak
			if ($this->order->canInvoice())
			{
				$invoice = $this->order->prepareInvoice();
				$invoice->register()->capture();
				Mage::getModel('core/resource_transaction')
					->addObject($invoice)
					->addObject($invoice->getOrder())
					->save();
				$comment = __('Invoice #%s created', $invoice->getIncrementId());
			}
			$this->orderStatus = Mage::getStoreConfig('moka/settings/order_status_after_payment');

			//if ($this->orderStatus == "") {$this->orderStatus=Mage_Sales_Model_Order::STATE_PROCESSING;} // if sonu
			if ($this->orderStatus == "") {$this->orderStatus="processing";} // if sonu

			$this->order->setState($this->orderStatus, $this->orderStatus, __('Received payment')."-".__LINE__, $notified = true);

			if (Mage::getStoreConfig('payment/moka_payment/use_custom_order_statuses') == 1)
			{
				$moka_status_ek = "_moka";
			} // if sonu
			else
			{
				$moka_status_ek = "";
			} // else sonu
			
			$this->order->setStatus($this->orderStatus.$moka_status_ek)->save();
			$this->order->sendNewOrderEmail();
			$this->order->save();

		}
		
		$session->setLastQuoteId($this->quote_id)
				->setLastOrderId($this->order_id)
				->setLastSuccessQuoteId($this->quote_id)
				->setLastRealOrderId($this->order_id);

		if (isset($this->quote) and is_object($this->quote)) 
		{
			$this->quote->setIsActive(false)->save();
		} // if sonu

		$moka->resetLogId(); // Log id silinir...

		// Yonlendirme
		Mage::getSingleton('checkout/session')->addSuccess(__("Kredi kartınızdan tahsilat yapıldı. Teşekkür ederiz..."));

		$this->yonlendir(Mage::getUrl('checkout/onepage/success',array('_secure'=>true)));

		exit();

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function yonlendir($url) {

		?>
			<script language="javascript">
				window.location.replace("<?php echo $url;?>");
			</script>
		<?php

		ob_end_flush();

		return true;

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function show_lutfen_bekleyiniz() {

		ob_start();

		$wait_html	= Mage::getStoreConfig('payment/moka_payment/wait_html');

		if ($wait_html != "")
		{
			echo $wait_html;
		} // if sonu
		else
		{
			echo "<h4 style='font-family:verdana;'>".__('Kartinizdan tahsilat yapilirken lutfen bekleyiniz...')."</h4>";
			echo "<h5 style='font-family:verdana;'>".__('Islem yapilirken bu pencereyi kapatmayiniz veya herhangi bir tusa basmayiniz, sayfayi yenilemeyiniz.')."</h5>";
			echo "<img src='".Mage::getDesign()->getSkinUrl('images/moka_payment/loading1.gif')."' border='0'/>";
		} // else sonu

		flush();
		return true;

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function moka_returnAction() {
		
		$this->show_lutfen_bekleyiniz();

		$moka = Mage::getSingleton('Moka_Payment_Model_Moka');

		$req			= Mage::app()->getRequest();
		$trans_key_ret	= $req->getParam('tp');
		$log_id_ret		= $req->getParam('tid');
		$log_id_sess	= $moka->getLogId();
		$log_id_hash	= $moka->getLogIdByHash($trans_key_ret);

		$session	= Mage::getSingleton('checkout/session');
		
		$this->order_id	= $session->getLastOrderId();
		$this->quote_id = $session->getLastQuoteId();

		try{
			$this->order = Mage::getModel('sales/order')->load($this->order_id);
			$this->quote = Mage::getModel('sales/quote')->load($this->quote_id);
		}
		catch (Exception $e)
		{
			$this->order_fail_payment($_POST,null,null);
		}

		if (!($log_id_hash == $log_id_ret and $log_id_ret == $log_id_sess)) 
		{
			$this->order_fail_payment($_POST);
		} // if sonu
		
		$log_id = $log_id_sess;

		$log_data = $moka->logDetail($log_id);

		if (!is_array($log_data) or count($log_data) < 2) 
		{
			$this->order_fail_payment($_POST);
		} // if sonu

		if (intval($this->quote_id) < 1)
		{
			$moka->resetLogId(); // Log id silinir

			// Mesaj ekle ve sayfaya yonlendir
			Mage::getSingleton('checkout/session')->addError(__("Hata : Son işlemden sonra çok uzun süre geçtiği için işlem yeniden yapılmalıdır.").__LINE__);
			$this->yonlendir(Mage::getUrl('checkout/cart',array('_secure'=>true)));
			return;
		} // if sonu

		// Log islemi
		unset($log_arr);

		$moka_status = 0;
		if (@$_POST["IsSuccessful"] == True) 
		{
			$moka_status = 1;
		} // if sonu

		$moka_message = @$_POST["ResultMessage"];
		if ($moka_message == '') 
		{
			$moka_message = @$moka_return["ResultCode"];
		} // if sonu
		
		$log_arr=array(
						"moka_status"			=> $moka_status,
						"moka_response_code"	=> @$_POST["ResultCode"],
						"moka_message"			=> @$_POST["ResultMessage"],
						"moka_order_id"			=> @$_POST["trxCode"],
					);

		$moka->logUpdate($log_arr);

		/// Moka Func
		if (@$_POST['isSuccessful'] === 'True')
		{
			$this->order_success();
		}
		else
		{
			$this->order_fail_payment($_POST);
		}

		return;

	}// function sonu #######################################################################################

} // CLASS sonu

