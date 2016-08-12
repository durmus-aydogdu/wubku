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
include 'vtBaglanti.php';
include 'fonksiyonlar.php'; 

$islem=isset($_POST["islem"]) ? mysql_real_escape_string(strip_tags($_POST["islem"])) : 'null';

if ($islem == "dosyaAktar") {
	
	$dosyaAdi = $_FILES['dosya']['name'];
	$dosyaGeciciAd  = $_FILES['dosya']['tmp_name'];
	$uygulamaIdPost=isset($_POST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaId"])) : 'null';
	$FKDizinId=isset($_POST["uygulamaDizinler"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaDizinler"])) : 'null';
	$FKKullaniciId=isset($_POST["kullaniciId"]) ? mysql_real_escape_string(strip_tags($_POST["kullaniciId"])) : 'null';
	
	$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
	$uygulamaId = mysql_fetch_row(mysql_query("select uygulamaId, uygulamaKokDizin from tblUygulamalar where uygulamaId ='$uygulamaIdPost'")) or die(mysql_error());
	$dizinYolu = mysql_fetch_row(mysql_query("select dizinYol  from tblDizinler where dizinId='$FKDizinId' and FKUygulamaId='$uygulamaIdPost'")) or die(mysql_error());
	
	if ( $dizinYolu[0] == "/"  )
		$dizinYolu[0]="";
		
	$dosyaYolu=	$sunucuKokDizin[0]."".$uygulamaId[1]."".$dizinYolu[0];
	$dosyaOzetDegeri=shell_exec('sha512sum '.$dosyaGeciciAd.' | cut -d\' \' -f1');
	$dosya= addslashes(fread(fopen($dosyaGeciciAd, "rb"),filesize($dosyaGeciciAd)));

	if (mysql_query("INSERT INTO tblDosyalar (dosyaAd,FKDizinId,dosyaOzetDeger,dosyaDurum,dosya,FKKullaniciId,FKUygulamaId) values('$dosyaAdi', '$FKDizinId', '$dosyaOzetDegeri',1,'$dosya',$FKKullaniciId,'$uygulamaIdPost')")) { 
		
		if (move_uploaded_file($dosyaGeciciAd,$dosyaYolu."".$dosyaAdi)) {

			uygulamaninOzetDegeriniHesapla( $dosyaYolu, $uygulamaIdPost );
			$sonuc = "Dosya yüklendi.";
		}
		else
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";	
	}
	else{
		$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
	
	}
	
}
else if ($islem == "dizinAktar") {
	
	$dizinYolu=isset($_POST["dizinYolu"]) ? mysql_real_escape_string(strip_tags($_POST["dizinYolu"])) : 'null'; 
	$uygulamaIdP=isset($_POST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaId"])) : 'null'; 

	
	if(mysql_query("insert into tblDizinler (dizinYol,FKUygulamaId) values('$dizinYolu','$uygulamaIdP')")) {

		$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
		$uygulamaId = mysql_fetch_row(mysql_query("select uygulamaId, uygulamaKokDizin from tblUygulamalar where uygulamaId='$uygulamaIdP' ")) or die(mysql_error());

		dizinOlustur( $sunucuKokDizin[0]."".$uygulamaId[1]."".$dizinYolu, $sunucuKokDizin[0]."".$uygulamaId[1], $uygulamaId[0]  );
			
	}
}
else 
	echo "Herhangi bir iþlem seçilmedi."

?>
</body>
</html>
 
