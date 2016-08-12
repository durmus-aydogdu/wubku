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
include 'oturumKontrol.php';
include 'fonksiyonlar.php';


$dosyaId=isset($_REQUEST['dosyaId']) ? mysql_real_escape_string(strip_tags($_REQUEST['dosyaId'])) : 'null';

$uygulamaUzerindeYetkiliMi=mysql_num_rows(mysql_query("select yetkiId from tblYetkiler as y, tblDosyalar as d where y.FKUygulamaId=d.FKUygulamaId and d.FKKullaniciId='$kulId' and dosyaId='$dosyaId'"));

 
if( $uygulamaUzerindeYetkiliMi == 1 ){
 
		$dosyaSorgu = mysql_query("SELECT dosyaAd, dosya,uygulamaAd, uygulamaId  FROM tblDosyalar,tblUygulamalar WHERE FKUygulamaId=uygulamaId and dosyaId = '$dosyaId'") or die('Hata oluþtu. Tekrar deneyiniz.');
		$islemBilgileri = mysql_fetch_row($dosyaSorgu);
		$mesaj=$kulId. " numaralý ".$kulAd." kullanýcýsý ". $islemBilgileri[2]. " uygulamasinin ". $islemBilgileri[0] . " isimli dosyasýný indirdi.";
		
		$sonuc=islemiKaydet ($mesaj,$islemBilgileri[3]);
		
		if ($sonuc == 0 ) {
			header("Content-Disposition: attachment; filename=$islemBilgileri[0]");

		echo $islemBilgileri[1];
		}
		else {
			echo "Ýþlem yapýlýrken hata oluþtu. Tekrar deneyiniz.";
			exit();
		}
}
else 
	echo "Bu dosyayý görüntülemeye yetkili deðilsiniz.";
?>
