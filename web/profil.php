<?php
/* 
  Web Uygulamalar� B�t�nl�k Kontrol Uygulamas� (WUBKU),  web uygulama kaynak kodu �zerinden b�t�nl�k kontrol� yaparak, uygulaman�n b�t�nl���n�n korunmas�n� sa�lamaktad�r.
  Copyright (C) <2015>  <Durmu� AYDO�DU>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>

*/
?>

<?php include 'baslik.php' ?>
	<title>WUBKU - profil </title>
	 
</head>

<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php'; 
if ( $kulRol == 1 )
	include 'ustMenuYonetici.php';
else if ( $kulRol == 2 )
	include 'ustMenuYazilimci.php';
else 
	include 'ustMenuIzleyici.php';



$profilGuncelle=isset($_REQUEST["profilGuncelle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["profilGuncelle"])) : 'null';

$sonuc="null";
 
if($profilGuncelle != "null") { 
		
	$ad=trim(mysql_real_escape_string(strip_tags($_POST["ad"])));
	$soyad=trim(mysql_real_escape_string(strip_tags($_POST["soyad"])));
	$kulAd=trim(mysql_real_escape_string(strip_tags($_POST["kulAd"])));
	$sifre=mysql_real_escape_string(strip_tags($_POST["sifre"]));
	$sifreT=mysql_real_escape_string(strip_tags($_POST["sifreT"]));
	$sifreK=mysql_real_escape_string(strip_tags($_POST["sifreK"]));
	$ePosta=trim(mysql_real_escape_string(strip_tags($_POST["eposta"])));

	if($sifre <> $sifreT) {
	
		$sonuc = "Girilen �ifreler ayn� de�il.";

    }
	else {	
		if ($ad=="" ||  $soyad=="" ||  $kulAd=="" ||  $ePosta=="" ) {
				
			$sonuc="��lem yap�l�rken hata olu�tu. Tekrar Deneyiniz.";
		}
		else{
		
			if ($sifreK==$sifre)
				$sifre=$sifre;
			else
				$sifre=md5($sifre);

				
			if(mysql_query("update tblKullanici set kulAd='$kulAd', ad='$ad', soyad='$soyad', eposta='$ePosta', sifre='$sifre' where kullaniciId='$kulId'")) { 
				$sonuc = "G�ncelleme yap�ld�.";
			}
			else{
				$sonuc="��lem yap�l�rken hata olu�tu. Tekrar Deneyiniz.";
			}
		}
	} 
}

?> 

 

<body>
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Kullan�c� Bilgileri</h2>
        <p>Bilgilerinizi buradan d�zenleyebilirsiniz.</p>
      </div>
	  	
		 
						
		
<?php 

$kullaniciBilgileriSorgu = mysql_query("select kulAd, ad,soyad, sifre, eposta from tblKullanici where kullaniciId='$kulId'");

if ($kullaniciBilgileri = mysql_fetch_assoc($kullaniciBilgileriSorgu)) {
 
	$kulAd=$kullaniciBilgileri["kulAd"];
	$ad=$kullaniciBilgileri["ad"];
	$soyad=$kullaniciBilgileri["soyad"];
	$eposta=$kullaniciBilgileri["eposta"];
	$sifre=$kullaniciBilgileri["sifre"];
}	
?>	
 
 <form class="form-signin" action="profil.php" method="post">
 
        <input type="text" class="form-control" value="<?php echo  $kulAd; ?>" autofocus name="kulAd" required>
		<input type="text" class="form-control" value="<?php echo  $ad; ?>"  name="ad" required>
		<input type="text" class="form-control" value="<?php echo  $soyad; ?>"  name="soyad" required>
		<input type="password" class="form-control" value="<?php echo  $sifre; ?>" name="sifre" required>
		<input type="password" class="form-control" value="<?php echo  $sifre; ?>" name="sifreT" required>
		<input type="mail" class="form-control" value="<?php echo  $eposta; ?>" name="eposta" required>
 
 
		<button class="btn btn-primary btn-block" type="submit" name="profilGuncelle" value="profilGuncelle">G�ncelle</button>
		<input type="hidden" id="sifreK" name="sifreK" value="<?php echo $sifre ?>">
		
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
      </form> 
	  
	  
	
    </div>
 
</body>
</html>