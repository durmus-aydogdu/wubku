<?php

$sunucu       		="localhost";
$kullaniciAdi		="wubkuVeritabaniKullanicisi";
$sifre			="wubkuVeritabaniSifresi";
$veritabaniAdi      	="wubkuVeritabani";


$baglanti=@mysql_connect($sunucu, $kullaniciAdi, $sifre);
        if (!$baglanti) die ("Veritabani Baðlantisi Saðlanamadi.");

mysql_select_db($veritabaniAdi,$baglanti) or die ("Veritabani Baðlantisi Saðlanamadi.");


function ipAdresiBul(){  
	if(isset($_SERVER['HTTP_CLIENT_IP'])){

		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}

	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	else if(isset($_SERVER['HTTP_X_FORWARDED'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED'];
	}

	else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	}

	else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_FORWARDED_FOR'];
	}

	else if(isset($_SERVER['HTTP_FORWARDED'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_FORWARDED'];
	}
	else{
			$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}


$ipAdresi = ipAdresiBul();
$baglantiIPAdresi = "IPAdresi";

if ( ($ipAdresi == "$baglantiIPAdresi" ) ) {

	$id=isset($_GET['id']) ? mysql_real_escape_string(strip_tags($_GET['id'])) : 'null';
	
	if( $id != 'null'){

		$dosyaSorgu=mysql_query("select dosyaAd, dosya from tblDosyalar where dosyaId='$id'");
		while($sonuc=mysql_fetch_array($dosyaSorgu)) {
            $dosya = $sonuc["dosya"];
            $dosyaAd = $sonuc["dosyaAd"];
        }


		header("Content-Disposition: attachment; filename=$dosyaAd");
		echo $dosya;
		exit;
}
}

?>
