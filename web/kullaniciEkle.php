<?php
/* 
  Web Uygulamalarý Bütünlük Kontrol Uygulamasý (WUBKU),  web uygulama kaynak kodu üzerinden bütünlük kontrolü yaparak, uygulamanýn bütünlüðünün korunmasýný saðlamaktadýr.
  Copyright (C) <2015>  <Durmuþ AYDOÐDU>

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

<?php 

include 'baslik.php';
include 'vtBaglanti.php';
include 'oturumKontrol.php';

?>

<title>WUBKU - kullanýcý ekle </title>
 
</head>

<?php
include 'ustMenuYonetici.php';
 
$islem=isset($_REQUEST["islem"]) ? mysql_real_escape_string(strip_tags($_REQUEST["islem"])) : 'null';

if($islem=="kayit") {

	$ad=trim(mysql_real_escape_string(strip_tags($_POST["ad"])));
	$soyad=trim(mysql_real_escape_string(strip_tags($_POST["soyad"])));
	$kulAd=trim(mysql_real_escape_string(strip_tags($_POST["kulAd"])));
	$sifreK=md5(mysql_real_escape_string(strip_tags($_POST["sifre"])));
	$sifreKT=md5(mysql_real_escape_string(strip_tags($_POST["sifreT"])));
	$ePosta=trim(mysql_real_escape_string(strip_tags($_POST["eposta"])));
	$kullaniciRol=trim(mysql_real_escape_string(strip_tags($_POST["kullaniciRol"])));

	if($sifreK <> $sifreKT) {
	
		header('Location:kullaniciEkle.php?durum=sifrelerUyumsuz');

    }
	else {
		$rs=mysql_fetch_array(mysql_query("select count(*) as cntAd from tblKullanici where kulAd='".$kulAd."'"));
    	$rsem=mysql_fetch_array(mysql_query("select count(*) as cntPosta from tblKullanici where eposta='".$ePosta."'"));
    
		if($rs['cntAd']>0 || $rsem['cntPosta']>0) {
        
			if($rs['cntAd']>0) {
				header('Location:kullaniciEkle.php?durum=mevcutKullanici');
				 
			}
			
			if($rsem['cntPosta']>0) {
				header('Location:kullaniciEkle.php?durum=mevcutEposta');
			}
		}
		else {	
			if ($ad=="" ||  $soyad=="" ||  $kulAd=="" ||  $ePosta==""  ||  $kullaniciRol=="" ) {
				
				header('Location:kullaniciEkle.php?durum=hata');
			}
			else{
			
				if(mysql_query("insert into tblKullanici (kulAd,ad,soyad,eposta,sifre, FKUyeRolId, kullaniciOnay) values('$kulAd','$ad','$soyad','$ePosta','$sifreK', '$kullaniciRol',0)")) { 
						header('Location:kullaniciEkle.php?durum=kayitTamam');
				}
				else{
					header('Location:kullaniciEkle.php?durum=hata');
				}
			}
		} 
	}
}

?>

	
<body>

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Kullanýcý Ekle</h2>
        <p>Uygulama üzerinde yetkili olacak kullanýcýlarý buradan oluþturabilirsiniz.</p>
      </div>
 

      <form class="form-signin" action="kullaniciEkle.php" method="post">

        <input type="text" class="form-control" placeholder="Kullanýcý Adý" autofocus name="kulAd" required>
		<input type="text" class="form-control" placeholder="Ad"  name="ad" required>
		<input type="text" class="form-control" placeholder="Soyad"  name="soyad" required>
		<input type="mail" class="form-control" placeholder="Eposta"  name="eposta" required>
		
		<input type="password" class="form-control" placeholder="Þifre" name="sifre" required>
		<input type="password" class="form-control" placeholder="Þifre Tekrar" name="sifreT" required>
		<?php $kullaniciRol = mysql_query("select * from tblKullaniciRol") or die(mysql_error()); ?>
		<select id="kullaniciRol" name="kullaniciRol"  class="form-control"> 
		<?php
			while ($sonuc = mysql_fetch_array($kullaniciRol)) {
				$rolId = $sonuc['rolId'];
				$rolAd = $sonuc['rolAd'];
				echo "<option value=\"$rolId\">$rolAd</option>";
				
			}
		?>							 
		</select>						
		<button class="btn btn-primary  form-control " type="submit" name="islem" value="kayit">Kaydet</button>
      </form>

</div> <!-- /container -->

  
</body>
</html>

