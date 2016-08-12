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


<?php include 'baslik.php' ?>

	<title>WUBKU - giriþ </title>
	<style>
	body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  font-size: 16px;
  height: auto;
  padding: 10px;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="text"] {
  margin-bottom: -1px;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

</style>
</head>

<body>

<?php

include 'vtBaglanti.php';

//Form uzerinden alinan degerler ataniyor ve giris degerleri kontrol ediliyor.
$islem=isset($_REQUEST["islem"]) ? mysql_real_escape_string(strip_tags($_REQUEST["islem"])) : 'null';

if($islem=="giris"){
	$kulAdK=isset($_POST["kulAd"]) ? mysql_real_escape_string(strip_tags($_POST["kulAd"])) : 'null';
	$sifre=isset($_POST["sifre"]) ? mysql_real_escape_string(strip_tags($_POST["sifre"])) : 'null';
	$sifreK=md5($sifre);

    $kullaniciSorgu=mysql_query("select kulAd,kullaniciId, FKUyeRolId from tblKullanici where kulAd='$kulAdK' and sifre='$sifreK' and kullaniciOnay=1");
		//giris basarili olursa uyesayfasina yonlendiriliyor.
    if(mysql_num_rows($kullaniciSorgu)>0) {
		$kullaniciBilgileri = mysql_fetch_row($kullaniciSorgu);
		
		if(!isset($_SESSION))
			session_start();
			
		
		$_SESSION["kulAd"]=$kullaniciBilgileri[0];
		$_SESSION["kulId"]=$kullaniciBilgileri[1];
		$_SESSION["kulRol"]=$kullaniciBilgileri[2];
				 
		if(!isset($_SESSION))
			session_start();
 
		if ($kullaniciBilgileri[2] == 1)
			header('Location:yonetici.php');	
			
		else if ($kullaniciBilgileri[2] == 2)
			header('Location:yazilimci.php');	
			
		else if ($kullaniciBilgileri[2] == 3)
			header('Location:kayitTakip.php');
		else
			header('Location:giris.php?durum=gecersiz');
        } 
		else {				
			
			header('Location:giris.php?durum=gecersiz');
        }	     
}

else if($islem=="cikis") {

    $_SESSION["kulAd"]="";
	$_SESSION["kulId"]="";
	$_SESSION["kulRol"]="";
	 
	unset($_SESSION["kulAd"]);
	unset($_SESSION["kulId"]);
	unset($_SESSION["kulRol"]);
	
	header('Location:giris.php?durum=cikis');
}
else {
}
?>

 <div class="container">

      <form class="form-signin" action="giris.php" method="post">
        <h2 class="form-signin-heading text-center">WUBKU Giriþ</h2>
        <input type="text" class="form-control" placeholder="Kullanýcý Adý" autofocus name="kulAd" required>
		<input type="password" class="form-control" placeholder="Þifre" name="sifre" required>
		<button class=" form-control btn btn-primary" type="submit" name="islem" value="giris">Giriþ</button>
      </form>

    </div> <!-- /container -->
 