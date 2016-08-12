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

 
$uygulamaId=isset($_REQUEST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["uygulamaId"])) : 'null';

	$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
	$uygulamaBilgileri = mysql_fetch_row(mysql_query("select uygulamaKokDizin, uygulamaId, uygulamaAd from tblUygulamalar where  uygulamaId='$uygulamaId' ")) or die(mysql_error());
	$uygulamaDizini=$sunucuKokDizin[0]."".$uygulamaBilgileri[0];
if ( mysql_query("delete from tblUygulamalar where uygulamaId='$uygulamaId'") ) {
 
	mysql_query("delete from tblDizinler where FKUygulamaId='$uygulamaId'");
	mysql_query("delete from tblYetkiler where FKUygulamaId='$uygulamaId'");
	dizinSil( $uygulamaDizini, $uygulamaDizini, $uygulamaBilgileri[1] );
	
	$mesaj=$kulId. " numaralý ".$kulAd." yöneticisi ". $uygulamaBilgileri[2]. " adlý uygulamayý sildi.";
 
	$sonuc=islemiKaydet ($mesaj,$uygulamaId);

	if ($sonuc == 0)		
		header('Location:uygulamalar.php?sonuc=uygulamaSilindi');
	else
		header('Location: uygulamalar.php?sonuc=hataOlustu');

} else
	header('Location:uygulamalar.php?sonuc=hataOlustu');


?>