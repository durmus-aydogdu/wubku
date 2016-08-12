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

 
$dizinId=isset($_REQUEST["dizinId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["dizinId"])) : 'null';
$uygulamaId = mysql_fetch_row(mysql_query("select uygulamaKokDizin, uygulamaId,uygulamaAd  from tblUygulamalar, tblDizinler where FKUygulamaId=uygulamaId and dizinId ='$dizinId' ")) or die(mysql_error());
$dizinYol = mysql_fetch_row(mysql_query("select dizinYol from tblDizinler where dizinId='$dizinId'")) or die(mysql_error());

if (mysql_query("delete from tblDizinler where dizinId='$dizinId'")) {
		
	$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
	
	if ($dizinYol[0] != "/")
	dizinSil( $sunucuKokDizin[0]."".$uygulamaId[0]."".$dizinYol[0], $sunucuKokDizin[0]."".$uygulamaId[0], $uygulamaId[1]  );

	$mesaj=$kulId. " numaralý ".$kulAd." kullanýcýsý ". $uygulamaId[2]. " uygulamasýndaki ". $dizinYol[0] . " adlý dizini sildi.";
 
	$sonuc=islemiKaydet ($mesaj,$uygulamaId[1]);

	if ($sonuc == 0)		
		header('Location: uygulamalar.php?sonuc=dizinSilindi');
	else
		header('Location: uygulamalar.php?sonuc=hataOlustu');
	}
else
	header('Location: uygulamalar.php?sonuc=hataOlustu');

?>