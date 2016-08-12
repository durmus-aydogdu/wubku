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
 
	<title>WUBKU - uygulama ekle </title>
	 
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'ustMenuYonetici.php';
include 'fonksiyonlar.php';

$uygulamaEkle=isset($_REQUEST["uygulamaEkle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["uygulamaEkle"])) : 'null';
$sonuc="null";
 
if($uygulamaEkle != "null") { 

	$uygulamaAdi=isset($_POST["uygulamaAdi"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaAdi"])) : 'null';
	$uygulamaKokDizin=isset($_POST["uygulamaKokDizin"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaKokDizin"])) : 'null';
	
	$dizinAdKontrol=substr($uygulamaKokDizin, -1); 
	
	if ( !($dizinAdKontrol == "/") )
			$uygulamaKokDizin = $uygulamaKokDizin."/";
 	
	$tarih = date('Y-m-d H:i:s');
	 if(mysql_query("insert into tblUygulamalar (uygulamaAd,uygulamaDurum,eklenmeTarihi,uygulamaOzetDeger,uygulamaKokDizin) values('$uygulamaAdi',1,'$tarih','null','$uygulamaKokDizin')")) {

			$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
			$uygulamaId = mysql_fetch_row(mysql_query("select uygulamaId, uygulamaKokDizin from tblUygulamalar where uygulamaAd='$uygulamaAdi' and uygulamaKokDizin='$uygulamaKokDizin' ")) or die(mysql_error());
 
			dizinOlustur( $sunucuKokDizin[0]."".$uygulamaId[1], $sunucuKokDizin[0]."".$uygulamaId[1], $uygulamaId[0]  );

			mysql_query("insert into tblDizinler (dizinYol, FKUygulamaId) values('/','$uygulamaId[0]')");
			
			$mesaj=$kulId. " numaralý ".$kulAd." yöneticisi ". $uygulamaAdi . " adýnda bir uygulama ekledi.";
 
			$sonuc=islemiKaydet ($mesaj,$uygulamaId[0]);
			
			if ($sonuc == 0 ) {
				$sonuc="Uygualama Eklendi.";
			}
			else 
				$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
				
				

		}
		else {
			
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
 
}

?> 
 
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Uygulama Ekle</h2>
        <p>Yeni uygulama ekleme iþlemini buradan yapabilirsiniz.</p>
      </div>
 
	  <form class="form-signin" role="form" name="frmAyarlar" method="post" action="uygulamaEkle.php">
		
		 <div class="form-group">
 
                <label> Uygulama Adý </label>
				<input type="text" class="form-control" id="uygulamaAdi" value="uygulama" name="uygulamaAdi" required autofocus>
 
        </div>
		
		 <div class="form-group">
 
                <label> Uygulama Kök Dizini </label>
				<input type="text" class="form-control" id="uygulamaKokDizin" value="dizin" name="uygulamaKokDizin" required >
 
        </div>
   
        <button type="submit" class="btn btn-success btn-sm form-control" name="uygulamaEkle" value="uygulamaEkle">Ekle</button>
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
      </form>
    </div>
 
</body>
</html>