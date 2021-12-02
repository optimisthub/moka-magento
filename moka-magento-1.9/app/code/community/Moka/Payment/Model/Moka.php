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

class Moka_Payment_Model_Moka extends Mage_Payment_Model_Method_Abstract
{

	public $_title				= 'Moka Payment';
	protected $_code			= 'moka_payment';
    protected $_formBlockType	= 'Moka_Payment_Block_Moka_Cc';
	protected $_infoBlockType	= 'Moka_Payment_Block_Moka_Info';
	protected $_adminBlockType	= 'Moka_Payment_Block_Moka_CcAdmin';
	protected $_canAuthorize	= true;	
	protected $_canUseInternal	= true;	
	protected $_canSaveCc		= true;	
	protected $on_before_payment_status = true;   

	// #######################################################################################################
    public function canUseInternal()
    {
        return $this->_canUseInternal;
	}// function sonu #########
	
	// #######################################################################################################
    public function canUseForMultishipping()
    {
        return true;

	}// function sonu #########
	
	// #######################################################################################################
    public function onBeforePaymentStatus()
    {
        return true;

	}// function sonu #########
	
	// #######################################################################################################
    public function onOrderValidate(Mage_Sales_Model_Order_Payment $payment)
    {
       return $this;
	}// function sonu #########
	
	// #######################################################################################################
    public function onInvoiceCreate(Mage_Sales_Model_Invoice_Payment $payment)
    {
	}// function sonu #########
	
	// #######################################################################################################
    public function canCapture()
    {
        return true;

	}// function sonu #########
	
	
	// #######################################################################################################
	public function isInitializeNeeded()
    {
        return true;
	} // function sonu #######################################################################################

	// #######################################################################################################
    public function initialize($paymentAction, $stateObject)
    {

		$session = $this->getCheckout();
		$session->unsetData('taksit');
		$session->unsetData('banka');

		$state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
        $stateObject->setState($state);
        $stateObject->setStatus(Mage::getSingleton('sales/order_config')->getStateDefaultStatus($state));
        $stateObject->setIsNotified(false);

	} // function sonu #######################################################################################

	// #######################################################################################################
    public function getSession()
    {
        return Mage::getSingleton('moka_payment/session');

	}// function sonu #########
	
	// #######################################################################################################
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');

	}// function sonu #########
	
	// #######################################################################################################
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();

	}// function sonu #########
	
	// #######################################################################################################
    public function createFormBlock($name)
    {
        $block = $this->getLayout()
				->createBlock('moka_payment_block_moka_form', $name)
				->setMethod('moka_payment')
				->setPayment($this->getPayment());

        return $block;

	}// function sonu #########
	
	// #######################################################################################################
    public function validate()
    {
        parent::validate();
  
        return $this;

	}// function sonu #########
	
	// #######################################################################################################
	public function assignData($data)
    {
		if (!is_object($data)) /// FireCheckout gibi sistemlerde $data boş olabiliyor
		{
			return;
		} // if sonu

		$gdata = $data->getData();
		
		if (@$gdata['method'] == 'moka_payment' and !isset($gdata['moka_payment_cc_owner']) and isset($_POST['payment']['moka_payment_cc_owner'])) 
		{
			$data = new Varien_Object(@$_POST['payment']);;
		} // if sonu
		
	    parent::assignData($data);
	
		$taksit			= strval($data->getData('moka_payment_taksit_moka'));
		if ($taksit == "") {$taksit=0;} 

		$custom_title=Mage::getStoreConfig('moka/taksit_baslik/taksit_'.$taksit);
		if (trim($custom_title) == "") 
		{
			if ($taksit < 2) 
			{
				$taksit_adi = __('Tek Çekim');
			} // if sonu
			else 
			{
				$taksit_adi = $taksit." ".__('Taksit');
			} // else sonu
		} // if sonu
		else 
		{
			$taksit_adi=$custom_title;
		} // else sonu
		
      	$session = Mage::getSingleton('checkout/session');

		$info = $this->getInfoInstance();

		$info->setTaksit($taksit);
		$info->setBanka("moka");
		$info->setMokaTaksit($taksit);
		$info->setMokaTaksitAdi($taksit_adi);

		$session->setData('banka',				"moka");
		$session->setData('taksit',				$taksit);
		$session->setData('moka_taksit',		$taksit);
		$session->setData('moka_taksit_adi',	$taksit_adi);

		if (Mage::getStoreConfig('moka/settings/connection_type') != 'oos') 
		{
			$cc_owner		= $data->getData('moka_payment_cc_owner');
			$cc_number		= $data->getData('moka_payment_cc_number');
			$cc_cid			= $data->getData('moka_payment_cc_cid');
			$cc_exp_year	= $data->getData('moka_payment_cc_exp_year');
			$cc_exp_month	= $data->getData('moka_payment_cc_exp_month');

			$cc_first		= substr(trim($cc_number),0,1);
			switch($cc_first){
				case "3":	$cc_type="AM";	break;
				case "4":	$cc_type="VI";	break;
				case "5":	$cc_type="MC";	break;
				default :	$cc_type="VI";	break;
			}

			$info->setCcLast4(substr($cc_number, -4));
			$info->setCcOwner($cc_owner);
			$info->setCcNumber($cc_number);
			$info->setCcType($cc_type);
			$info->setCcExpYear($cc_exp_year);
			$info->setCcExpMonth($cc_exp_month);

			$session->setData('ccOwner',			$cc_owner);
			$session->setData('ccNumber',			$cc_number);
			$session->setData('ccType',				$cc_type);
			$session->setData('cv2',				$cc_cid);
			$session->setData('expYear',			$cc_exp_year);
			$session->setData('expMonth',			$cc_exp_month);
		} // if sonu
		
		//$this->logInsert();

		return $this;

	}// function sonu #########
	
	// #######################################################################################################
	public function getOrderPlaceRedirectUrl()
    {
		return Mage::getUrl('grmoka/moka/moka_payment');
	}// function sonu #########
	
	// #######################################################################################################
	public function get_bank_order_id($order_id) {

		$bank_order_id = '';

		if (!isset($_SESSION["order_try_".$order_id]) or $_SESSION["order_try_".$order_id] == "" or $_SESSION["order_try_".$order_id] < 1)
		{$_SESSION["order_try_".$order_id]=1;}
		else
		{$_SESSION["order_try_".$order_id]++;}

		if (strlen($_SESSION["order_try_".$order_id]) < 3)
		{
			$try=str_repeat("0",3-strlen($_SESSION["order_try_".$order_id])).$_SESSION["order_try_".$order_id];
		} // if sonu
		else
		{
			$try=$_SESSION["order_try_".$order_id];
		} // else sonu

		$bank_order_id	= $order_id."_".$try;

		if (Mage::getStoreConfig('moka/settings/order_prefix') != "")
		{
			$bank_order_id = Mage::getStoreConfig('moka/settings/order_prefix')."-".$bank_order_id;
		} // if sonu
		
		return $bank_order_id;

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function get_cart_total()
	{

		$items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
		 
		foreach($items as $item) {
			$total=+$item->getQty() * $item->getPrice();
		}
		
		return $total;

	}// function sonu #########

	// #######################################################################################################
	public function get_ip() {

		$arr=array(
					"HTTP_CLIENT_IP",
					"HTTP_X_FORWARDED_FOR",
					"HTTP_X_FORWARDED",
					"HTTP_FORWARDED_FOR",
					"HTTP_FORWARDED",
					"HTTP_X_REAL_IP",
					);
		
		$ips=array();

		foreach ($arr as $key) 
		{
			$ip=trim(getenv($key));
			if ($ip != "" and (substr($ip,0,7) != "192.168" ) and  $ip != "127.0.0.1") 
			{
				$ips[$ip]=$key;
			} // if sonu
		} // foreach sonu

		$ips=array_flip($ips);

		if (count($ips) > 0) 
		{
			foreach ($ips as $key=>$val) 
			{
				$ip=$val;
			} // foreach sonu
		} // if sonu
		else
		{
			$ip=getenv("REMOTE_ADDR");	
		} // else sonu

		if (strlen($ip) < 7 or strlen($ip) > 15) 
		{
			$ip="11.22.33.44";
		} // if sonu

		return $ip;

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function debugLog($func = null,$line = null,$msg = null) {

		return;

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function getDbConn() {
	
		if (!$this->conn) 
		{
			$this->conn	= Mage::getSingleton('core/resource');
		} // if sonu
		
		if (!$this->db) 
		{
			$this->db	= $this->conn->getConnection('core_write');
		} // if sonu
		
		if (!$this->tbl_moka_payment_info) 
		{
			$this->tbl_moka_payment_info = $this->conn->getTableName('moka_payment_info');
		} // if sonu

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function logInsert() {

		$this->resetLogId();

		$this->getDbConn();

		$last_id = $this->db->query("SELECT trans_id FROM ".$this->tbl_moka_payment_info." ORDER BY trans_id DESC LIMIT 1")->fetch();

		$session = $this->getCheckout();
		$session->setData('moka_log_last_id', $last_id['trans_id']+1);
		$session->setData('moka_log_protect_key', $this->random_password(64,'Y','Y','Y'));

		$sql_arr['trans_id']				= $session->getData('moka_log_last_id');
		$sql_arr['trans_date']				= date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
		$sql_arr['customer_ip']				= $this->get_ip();
		$sql_arr['trans_key']				= $session->getData('moka_log_protect_key');

		$this->db->insert($this->tbl_moka_payment_info,$sql_arr);

	} // function sonu #######################################################################################


	// #######################################################################################################
	public function getLogIdByHash($log_hash = null) {

		if ($log_hash == '' or $log_hash === null or strlen($log_hash) != 64) 
		{
			return false;
		} // if sonu
		
		$this->getDbConn();

		$select = $this->db
				->select()
				->from($this->tbl_moka_payment_info,['trans_id','trans_key'])
				->where('trans_key = ?', $log_hash)
				->limit(1)->query();  
		$data = $select->fetch();
		
		return intval($data['trans_id']);

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function logDetail($log_id = 0) {

		$log_id = intval($log_id);

		if ($log_id < 1) 
		{
			$log_id = $this->getLogId();
		} // if sonu
		
		if ($log_id < 1) 
		{
			return false;
		} // else sonu

		$this->getDbConn();

		$select = $this->db
				->select()
				->from($this->tbl_moka_payment_info)
				->where('trans_id = ?', $log_id)
				->limit(1)->query();  
		
		$ret = $select->fetch();

		return $ret;  

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function logUpdate($array,$type = null) {
	
		if ($this->getLogId() < 1)
		{
			$this->logInsert();	
		} // else sonu

		$this->getDbConn();

		$result = $this->db->update($this->tbl_moka_payment_info,$array, "trans_id ='".$this->getLogId()."'");
		
		return $ret;

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function getLogId() {
		
		$session = $this->getCheckout();
		return $session->getData("moka_log_last_id");

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function getLogProtectKey() {
		
		$session = $this->getCheckout();
		return $session->getData("moka_log_protect_key");

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function resetLogId() {
		
		$session = $this->getCheckout();
		$session->getData("moka_log_last_id",false);
		$session->unsetData("moka_log_last_id");
		return;

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function tr2en($str) {
	
		$trans=array(
						"Ç" => "C",	"ç" => "c",
						"Ğ" => "G",	"ğ" => "g",
						"İ" => "I",	"ı" => "i",
						"Ş" => "S",	"ş" => "s",
						"Ö" => "O",	"ö" => "o",
						"Ü" => "U",	"ü" => "u",

						"À" => "A",	"à" => "a",
						"Á" => "A",	"á" => "a",
						"Â" => "A",	"â" => "a",
						"Ã" => "A",	"ã" => "a",
						"Ä" => "A",	"ä" => "a",
						"Å" => "A",	"å" => "a",
						"Æ" => "AE","æ" => "ae",
						"È" => "E",	"è" => "e",
						"É" => "E",	"é" => "e",
						"Ê" => "E",	"ê" => "e",
						"Ì" => "I",	"ì" => "i",
						"Í" => "I",	"í" => "i",
						"Î" => "I",	"î" => "i",
						"Ï" => "I",	"ï" => "i",
						"Ñ" => "N",	"ñ" => "n",
						"Ò" => "O",	"ò" => "o",
						"Ó" => "O",	"ó" => "o",
						"Ô" => "O",	"ô" => "o",
						"Õ" => "O",	"õ" => "o",
						"Ù" => "U",	"ù" => "u",
						"Ú" => "U",	"ú" => "u",
						"Û" => "U",	"û" => "u",
						"Ü" => "U",	"ü" => "u",
						"ß" => "B",	"ß" => "b",
						"ÿ" => "y",
						"%" => "",
						"?___SID=S" => "",
						);

		$ret=strtr($str,$trans);

		return $ret;

	} // function sonu #######################################################################################

	// #######################################################################################################
	public function mail_gonder_bildirim($email, $name, $order, $log) {
	
        $mailer = Mage::getModel('core/email')
            ->setTemplate('email/moka_payment_siparis_bildirimi.phtml')
            ->setType('html')
            ->setTemplateVar('order', $order)
            ->setTemplateVar('quote', $this->getQuote())
            ->setTemplateVar('name', $name)
            ->setToName($name)
            ->setToEmail($email)
            ->send();		

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function mail_gonder_hata($email,$msg) {

		$emailTemplate  = Mage::getModel('core/email_template')->loadDefault('email/moka_payment_hata_maili.phtml');                                

		$emailTemplateVariables = array();
		$emailTemplateVariables['msg'] = $msg;
		$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);

		if ($email != "") 
		{
			$mailto[]= Mage::getStoreConfig('sales_email/order/copy_to');
			$mailto[]= Mage::getStoreConfig('contacts/email/recipient_email');
			$mailto[]= Mage::getStoreConfig('trans_email/ident_general/email');

			foreach ($mailto as $key=>$val) 
			{
				if (
						$val == ""
						or
						$val == "hello@example.com"
					) 
					{unset($mailto[$key]);} // if sonu
			} // foreach sonu
		} // if sonu
		else 
		{
			$mailto[]= $email;
		} // else sonu
		foreach ($mailto as $mail) 
		{
			$emailTemplate->send($mail,$mail, $emailTemplateVariables);	
		} // foreach sonu

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function limit_text($text,$limit) {
	
		if (strval($limit) < 1) 
		{
			$limit = strlen($text);
		} // if sonu
		else 
		{
			$limit=strval($limit);	
		} // else sonu
		
		return substr($text,0,$limit);

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function clear_text($text) {
	
		$text=$this->tr2en($text);
		// Gönderilen içeriği XML de kabul edilmeyecek karakterlerden temizler
		$harfler=str_split(" -_.,:@0123456789abcdefghijklnopqrstuvwxyzmbcdefghijklnopqrstuvwxyzABCDEFGHIJKLNOPQRSTUVWXYZMBCDEFGHIJKLNOPQRSTUVWXYZ");
		$text=str_split($text);

		$out="";
		foreach ($text as $t) 
		{
			if (in_array($t,$harfler)) 
			{
				$out.=$t;		
			} // if sonu
			else 
			{
				$out.="-";		
			} // else sonu
				
		} // foreach sonu

		for ($i=0;$i<10;$i++) 
		{
			$out=str_replace("--","-",$out);
			$out=str_replace("--","-",$out);
		} // for sonu

		return $out;

	} // function sonu #######################################################################################
	
	// #######################################################################################################
	public function lisans_durumu() {

		return;

	} // function sonu #######################################################################################

	//############ Function ###################################################################
	private function random_password($uzunluk,$buyuk,$kucuk,$rakam) { // v1.0

		$bharf="ABCDEFGHIJKLMNOPRSTUWXVYZ";
		$kharf="abcdefghijklmnoprstuwxvyz";
		$sayi="0123456789";

		if($buyuk=="Y") {$dize.=$bharf;} // if 
		if($kucuk=="Y") {$dize.=$kharf;} // if 
		if($rakam=="Y") {$dize.=$sayi;} // if 

		if($uzunluk > 500) {$uzunluk=500;} // if 

		for($i=1; $i < $uzunluk + 1 ; $i++) {
			$rand=mt_rand(0,strlen($dize)-1);
			$rand_pass.=substr($dize,$rand,1);
		}  ## for sonu

		Return $rand_pass;

	} //---------- End Function ----------

	// #######################################################################################################
	public function ip_to_country($ip) {
	
		$ip_goster = Mage::getStoreConfig('payment/moka_payment/customer_ip_to_country');

		if ($ip_goster == 1) 
		{
			//// Get Country info 
			$country_data=@file("http://api.wipmania.com/".$customer_ip);
			if (strlen($country_data) == 2) 
			{
				$detected_country=trim($country_data[0]);
			} // if sonu
			else 
			{
				$detected_country="--";
			} // else sonu
		} // if sonu
		else 
		{
			$detected_country="--";
		} // else sonu

		return $detected_country;
	
	} // function sonu #######################################################################################
		
	// #######################################################################################################
	function xml_duzelt($xmlString){
	
		/////$this->debugLog(__FUNCTION__,__LINE__,'');
		$outputString = "";
		$previousBitIsCloseTag = false;
		$indentLevel = 0;
		$tab = 4;
		$bits = explode("<", $xmlString);

		foreach($bits as $bit)
		{
			$bit = trim($bit);
			if (!empty($bit))
			{
				if ($bit[0]=="/"){ $isCloseTag = true; }
				else{ $isCloseTag = false; }

				if(strstr($bit, "/>"))
				{
					$prefix = "\n".str_repeat(" ",$indentLevel);
					$previousBitIsSimplifiedTag = true;
				}
				else
				{
					if ( !$previousBitIsCloseTag and $isCloseTag){
						if ($previousBitIsSimplifiedTag)
						{
							$indentLevel=$indentLevel-$tab;
							$prefix = "\n".str_repeat(" ",$indentLevel);
						}
						else 
						{
							$prefix = "";
							$indentLevel=$indentLevel-$tab;
						}
					}
					if ( $previousBitIsCloseTag and !$isCloseTag){$prefix = "\n".str_repeat(" ",$indentLevel); $indentLevel=$indentLevel+$tab;}
					if ( $previousBitIsCloseTag and $isCloseTag){$indentLevel=$indentLevel-$tab;$prefix = "\n".str_repeat(" ",$indentLevel);}
					if ( !$previousBitIsCloseTag and !$isCloseTag){{$prefix = "\n".str_repeat(" ",$indentLevel); $indentLevel=$indentLevel+$tab;}}
					$previousBitIsSimplifiedTag = false;
				}

				$outputString .= $prefix."<".$bit;
				$previousBitIsCloseTag = $isCloseTag;
			}
		}
		/////$this->debugLog(__FUNCTION__,__LINE__,'');
		return $outputString;
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
	public function taksitler($banka , $tutar)
	{
		$tutar=strval(str_replace(",","",$tutar));

		$data =array();
		for($i=0;$i<13;++$i)
		{
			$taksit = trim(Mage::getStoreConfig($banka.'/taksitler/taksit_'.$i));
			if($taksit != "")
			{
				$data[$i] = $taksit; // ay => oran şeklinde...
			}
		}
		return $data;

	}// function sonu #########

	// #######################################################################################################
	public function taksit_hesapla($tutar,$taksit = null)
	{
		
		$tutar=strval(str_replace(",","",$tutar));
		$session = Mage::getSingleton('checkout/session');
		$hesapla =  @is_array($_POST["payment"])?"evet":"hayir";
	//	$hesapla = "evet";
		
		$osc = Mage::getConfig()->getModuleConfig('Idev_OneStepCheckout')->is('active', 'true');
		if ($osc === true) 
		{
			$hesapla = "evet";
		} // if sonu
		
		if ($taksit == null) 
		{
			$taksit = intval($session->gettaksit());
		} // if sonu
		else 
		{
			$taksit = intval($taksit);
		} // else sonu

		$banka	= "moka";
		$oran	= Mage::getStoreConfig($banka.'/taksitler/taksit_'.$taksit);

		if ($banka=="tekcekim") 
		{
			$banka	= Mage::getStoreConfig('tekcekim/settings/tekcekim_banka');
			$oran	= Mage::getStoreConfig('tekcekim/settings/tekcekim_komisyon');
		} // if sonu

		if ($banka == "" or intval($tutar) == 0 or intval($taksit) == 0 or $hesapla == "hayir"  ) 
		{	
			$taksit = 1;
			$oran=0;
		} // if sonu
		

		if ($oran != 0) 
		{
			if ($oran > 0) 
			{
				$son_tutar=$tutar + (($tutar/100) * $oran);
			} // if sonu
			else 
			{
				$son_tutar=$tutar - (($tutar/100) * $oran);
			} // else sonu
		} // if sonu
		else 
		{
			$son_tutar=$tutar;	
		} // else sonu
		
		$aylik_odenecek	= $tutar / $taksit ;

		$ret=array(
					"komisyon_orani"	=> $oran,
					"komisyon_toplami"	=> $son_tutar - $tutar,
					"aylik_odenecek"	=> $aylik_odenecek,
					"toplam_odenecek"	=> $son_tutar,
					);

		return $ret;

	}// function sonu #########
	
	// #######################################################################################################
	public function taksit_hesapla_urun($tutar)
	{
		$session = Mage::getSingleton('checkout/session');

		$taksit = intval($session->gettaksit());
		$banka	= "moka";
		$oran	= Mage::getStoreConfig($banka.'/taksitler/taksit_'.$taksit);

		if ($banka == "" or intval($tutar) == 0) 
		{	
			$taksit = 1;
			$oran=0;
		} // if sonu
		
		if (Mage::app()->getRequest()->getModuleName() == "checkout" and Mage::app()->getRequest()->getControllerName()=="cart") 
		{
			$oran=0;
		} // if sonu
		
		
		if ($oran > 0 or $oran < 0) 
		{
			$son_tutar=$tutar + (($tutar/100) * $oran);
		} // if sonu
		else 
		{
			$son_tutar=$tutar;	
		} // else sonu

		//$tutar=$tutar + (($tutar/100) * $oran);
		Mage::log(__FILE__."-".__LINE__."-".__FUNCTION__." // B:$banka ORAN:$oran MARJ:$marj TAKSIT:$taksit TUTAR:$tutar -- HESAPLANAN:$son_tutar");
		
		return $son_tutar;
		
		//Mage::log("ST:".__LINE__.":banka:".$oran." oran:".$taksit);
		//Mage::log("ST:".__LINE__.":".$tutar);
		//Mage::log("ST:".__LINE__.":".$tutar);
		//Mage::log("QUOTE:".var_export($quote,true));

	}// function sonu #########

	// #######################################################################################################
	public function taksit_hesapla_urun_moka($tutar)
	{
		$session = Mage::getSingleton('checkout/session');

		$taksit = intval($session->gettaksit());
		$banka	= "moka";
		$marj	= strval($session->getmarj());
		$oran	= Mage::getStoreConfig($banka.'/taksitler/taksit_'.$taksit);

		if ($banka=="tekcekim") 
		{
			$banka	= Mage::getStoreConfig('tekcekim/settings/tekcekim_banka');
			$oran	= Mage::getStoreConfig('tekcekim/settings/tekcekim_komisyon');
			$taksit	= 1;
		} // if sonu

		if ($banka == "" or intval($tutar) == 0) 
		{	
			$taksit = 1;
			$oran=0;
		} // if sonu
		
		if (Mage::app()->getRequest()->getModuleName() == "checkout" and Mage::app()->getRequest()->getControllerName()=="cart") 
		{
			$oran=0;
		} // if sonu
		
		
		if ($oran > 0 or $oran < 0) 
		{
			$son_tutar=$tutar + (($tutar/100) * $oran);
		} // if sonu
		else 
		{
			$son_tutar=$tutar;	
		} // else sonu

		if ($marj > 0) 
		{
			$son_tutar=$son_tutar + ( ( $son_tutar/100 ) * $marj);
		} // if sonu

		//$tutar=$tutar + (($tutar/100) * $oran);
		Mage::log(__FILE__."-".__LINE__."-".__FUNCTION__." // B:$banka ORAN:$oran MARJ:$marj TAKSIT:$taksit TUTAR:$tutar -- HESAPLANAN:$son_tutar");
		
		return $son_tutar;
		
		//Mage::log("ST:".__LINE__.":banka:".$oran." oran:".$taksit);
		//Mage::log("ST:".__LINE__.":".$tutar);
		//Mage::log("ST:".__LINE__.":".$tutar);
		//Mage::log("QUOTE:".var_export($quote,true));

	}// function sonu #########

	

} // class sonu 
