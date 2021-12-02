<?php
/**

	Uretici..........: Moka 
	Yazılımın Adı....: Moka_Payment // moka.com için Magento Tahsilat Modülü
	Geliştiren.......: Hidayet Ok <hidonet@gmail.com>
	Web..............: https://www.moka.com/

	/// Yasal Uyarı /////////////////////////////////////////////////////////////////////////

	Tüm hakları MOKA ÖDEME KURULUŞU A.Ş.'ne aittir.

*/

/*
"*/
?><?php

	$generr	=array(
					"islem_basarili"					=> __("Tahsilat işlemi başarıyla tamamlandı."),

					"sifre_limiti"						=> __("Kredi Kartınızın şifre deneme limiti doldu. Lütfen başka bir kartla ödeme yapmayı deneyiniz veya aynı kartla ödeme yapacaksanız yarın ödeme yapınız."),
					"bakiye_limiti"						=> __("Bu sipariş için Kartınızın Bakiyesi (veya Limiti) Yetersiz. Lütfen bakiye ve limit olarak yeterli bir kartla ödeme yapmayı deneyiniz."),
					"bakiye_alt_limiti"					=> __("Bu siparişin toplamı 1 TL'nin altında. Lütfen sipariş toplamını 1 TL'nin üzerine çıkararak tekrar deneyiniz."),
					"kullanim_limiti"					=> __("Kredi kartınızın günlük kullanım limiti dolduğundan işlem yapılamıyor. Lütfen başka bir kartla ödeme yapmayı deneyin veya bu kartla ödeme yapacaksanız yarın ( veye bu gece 00:00 dan sonra ) ödeme yapın."),
					"kullanim_limiti_bayi"				=> __("Kredi kartınızın günlük kullanım limiti dolduğundan işlem yapılamıyor. Lütfen başka bir kartla ödeme yapmayı deneyin veya bu kartla ödeme yapacaksanız yarın ( veye bu gece 00:00 dan sonra ) ödeme yapın."),
					"internet_limiti"					=> __("Ödeme yapmaya çalıştığınız kredi kartından tahsilat yapılamıyor. Kartınızın internet kullanım limitini açtırmak için bankanızı arayınız veya başka bir kredi kartı ile ödeme yapınız."),
					"kart_bilgisi"						=> __("Kredi kartı bilgilerinden biri eksik veya hatalı. Lütfen kredi kartı bilgilerinizi kontrol ederek tekrar deneyiniz."),
					"kart_bilgisi_cvv"					=> __("Kredi kartı güvenlik kodu bilgisi eksik veya hatalı. Lütfen kontrol ederek tekrar deneyiniz."),
					"kart_bilgisi_exp"					=> __("Kredi kartı son kullanma tarihi hatalı. Lütfen kontrol ederek tekrar deneyiniz."),
					"onay_vermiyor"						=> __("Bankanız şu an kartınız için onay vermiyor. Lütfen bankanızla irtibata geçin veya alışverişe başka bir kartla devam edin."),
					"suresi_dolmus"						=> __("Kartınızın kullanım süresi dolmuş. Lütfen başka bir kartla alışverişi tamamlayınız."),
					"taksit_yapilamaz"					=> __("Kullandığınız kredi kartı ile bu seçili bankadan taksit yapılamıyor."),
					"taksit_yapilamaz_ad"				=> __("Kullandığınız kredi kartı için istediğiniz taksit yapılamıyor."),
					"taksit_yapilamaz_yp"				=> __("TL dışındaki para birimlerine taksit yapılamaz."),
					"3d_destegi_yok"					=> __("Kartınız 3D Secure protokolünü desteklemiyor."),
					"3d_onay_alinamadi"					=> __("Kartınız için güvenlik onayı alınamadı."),
					"3d_onay_alindi_para_cekilemedi"	=> __("Kartınız için güvenlik onayı alınamadı."),

					"sistem_hatasi_genel"				=> __("Sistem Hatası. Kartınızdan tahsilat yapılırken bir sorun oluştu. Lütfen Site Yönetimi ile irtibata geçiniz."),
					"sistem_hatasi_1"					=> __("Sistem Hatası. Şu an iptal işlemi yapılamıyor. Lütfen bankanızla irtibata geçiniz."),
					"sistem_hatasi_2"					=> __("Sistem Hatası. Şu an kartınızdan tahsilat yapılamıyor. Lütfen kısa bir süre sonra tekrar deneyiniz. Sorun devam ederse site yönetimi ile irtibata geçiniz."),
					"sistem_hatasi_3"					=> __(""),
					"sistem_hatasi_4"					=> __("Sistem Hatası. Şu an işlem yapılamıyor. Lütfen site yönetimi ile irtibata geçiniz."),
					"sistem_hatasi_5"					=> __("Sistem Hatası. Şu an kartınızdan tahsilat yapılamıyor. Lütfen kısa bir süre sonra tekrar deneyiniz. Sitemizdeki başka bir ödeme yöntemini kullanabilirsiniz veya site yönetimi ile irtibata geçebilirsiniz."),

				);

	$error_codes=array(

		"PaymentDealer.CheckPaymentDealerAuthentication.InvalidRequest"							=> 	array("Hatalı hash bilgisi",$generr['sistem_hatasi_genel']),
		"PaymentDealer.CheckPaymentDealerAuthentication.InvalidAccount"							=> 	array("Böyle bir bayi bulunamadı",$generr['sistem_hatasi_genel']),
		"PaymentDealer.CheckPaymentDealerAuthentication.VirtualPosNotFound"						=> 	array("Bu bayi için sanal pos tanımı yapılmamış",$generr['sistem_hatasi_genel']),
		"PaymentDealer.CheckDealerPaymentLimits.DailyDealerLimitExceeded"						=> 	array("Bayi için tanımlı günlük limitlerden herhangi biri aşıldı",$generr['sistem_hatasi_genel']),
		"PaymentDealer.CheckDealerPaymentLimits.DailyCardLimitExceeded"							=> 	array("Gün içinde bu kart kullanılarak daha fazla işlem yapılamaz",$generr['sistem_hatasi_genel']),
		"PaymentDealer.CheckCardInfo.InvalidCardInfo"											=>	array("Kart bilgilerinde hata var",$generr['kart_bilgisi']),
		"PaymentDealer.DoDirectPayment.ThreeDRequired"											=> 	array("3d zorunlu",$generr['sistem_hatasi_genel']),
		"PaymentDealer.DoDirectPayment.InstallmentNotAvailableForForeignCurrencyTransaction" 	=> 	array("Yabancı para ile taksit yapılamaz",$generr['taksit_yapilamaz_yp']),
		"PaymentDealer.DoDirectPayment.ThisInstallmentNumberNotAvailableForDealer"				=> 	array("Bu taksit sayısı bu bayi için yapılamaz",$generr['taksit_yapilamaz_ad']),
		"PaymentDealer.DoDirectPayment.InvalidInstallmentNumber"								=> 	array("Taksit sayısı 2 ile 9 arasıdır",$generr['sistem_hatasi_genel']),
		"PaymentDealer.DoDirectPayment.ThisInstallmentNumberNotAvailableForVirtualPos" 			=> 	array("Sanal Pos bu taksit sayısına izin vermiyor",$generr['taksit_yapilamaz']),
		"PaymentDealer.DoDirectPayment.InvalidCurrencyCode" 									=> 	array("Kabul edilmeyen para birimi",$generr['sistem_hatasi_genel']),
		"PaymentDealer.Fraud.MinToMaxControl" 													=> 	array("Bayi için ödeme alt-üst limitleri ile ilgili bir sorun oluştu. Moka'yı arayınız",$generr['sistem_hatasi_genel']),
		"EX" 																					=> 	array("Beklenmeyen bir hata oluştu",$generr['sistem_hatasi_genel']),
	);		
	
	$error_codes_3_digit = array(
			'000' => array('Genel Hata',										$generr['sistem_hatasi_genel']),
			'001' => array('Kart Sahibi Onayı Alınamadı',						$generr['onay_vermiyor']),
			'002' => array('Limit Yetersiz',									$generr['bakiye_limiti']),
			'003' => array('Kredi Kartı Numarası Geçerli Formatta Değil',		$generr['kart_bilgisi']),
			'004' => array('Genel Red',											$generr['onay_vermiyor']),
			'005' => array('Kart Sahibine Açık Olmayan İşlem',					$generr['onay_vermiyor']),
			'006' => array('Kartın Son Kullanma Tarihi Hatali',					$generr['kart_bilgisi_exp']),
			'007' => array('Geçersiz İşlem',									$generr['onay_vermiyor']),
			'008' => array('Bankaya Bağlanılamadı',								$generr['sistem_hatasi_genel']),
			'009' => array('Tanımsız Hata Kodu',								$generr['sistem_hatasi_genel']),
			'010' => array('Banka SSL Hatası',									$generr['sistem_hatasi_genel']),
			'011' => array('Manual Onay İçin Bankayı Arayınız',					$generr['sistem_hatasi_1']),
			'012' => array('Kart Bilgileri Hatalı - Kart No veya CVV2',			$generr['kart_bilgisi']),
			'013' => array('Visa MC Dışındaki Kartlar 3D Secure Desteklemiyor',	$generr['3d_destegi_yok']),
			'014' => array('Geçersiz Hesap Numarası',							$generr['sistem_hatasi_genel']),
			'015' => array('Geçersiz CVV',										$generr['kart_bilgisi_cvv']),
			'016' => array('Onay Mekanizması Mevcut Değil',						$generr['sistem_hatasi_genel']),
			'017' => array('Sistem Hatası',										$generr['sistem_hatasi_genel']),
			'018' => array('Çalıntı Kart',										$generr['onay_vermiyor']),
			'019' => array('Kayıp Kart',										$generr['onay_vermiyor']),
			'020' => array('Kısıtlı Kart',										$generr['onay_vermiyor']),
			'021' => array('Zaman Aşımı',										$generr['sistem_hatasi_genel']),
			'022' => array('Geçersiz İşyeri',									$generr['sistem_hatasi_genel']),
			'023' => array('Sahte Onay',										$generr['onay_vermiyor']),
			'024' => array('3D Onayı Alındı Ancak Para Karttan Çekilemedi',		$generr['sistem_hatasi_genel']),
			'025' => array('3D Onay Alma Hatası',								$generr['3d_onay_alinamadi']),
			'026' => array('Kart Sahibi Banka veya Kart 3D-Secure Üyesi Değil',	$generr['3d_destegi_yok']),
	);



?>