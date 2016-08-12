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

if(!isset($_SESSION))
	session_start();
 	
$kulAd=isset($_SESSION["kulAd"]) ? $_SESSION["kulAd"]:'';
$kulId=isset($_SESSION["kulId"]) ? $_SESSION["kulId"]:'';
$kulRol=isset($_SESSION["kulRol"]) ? $_SESSION["kulRol"]:'';
 
if ($kulAd == "" || $kulId == "" || $kulRol == "") {

	$_SESSION["kulAd"]="";
	$_SESSION["kulId"]="";
	$_SESSION["kulRol"]="";
	
	unset($_SESSION["kulAd"]);
	unset($_SESSION["kulId"]);
	unset($_SESSION["kulRol"]);
	
	session_destroy();
	header('Location:giris.php??durum=bilgilerGecersiz');	
}

?>
