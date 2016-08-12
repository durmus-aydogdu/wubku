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
	<title>WUBKU - ayarlar </title>
	 
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'ustMenuYonetici.php';

$guncelle=isset($_REQUEST["guncelle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["guncelle"])) : 'null';
$sonuc="null";
 
if($guncelle != "null") { 

	$sunucuKokDizin=isset($_POST["sunucuKokDizin"]) ? mysql_real_escape_string(strip_tags($_POST["sunucuKokDizin"])) : 'null';
	$wubkuBildirim=isset($_POST["wubkuBildirim"]) ? mysql_real_escape_string(strip_tags($_POST["wubkuBildirim"])) : 'null';
	$epostaBildirim=isset($_POST["epostaBildirim"]) ? mysql_real_escape_string(strip_tags($_POST["epostaBildirim"])) : 'null';
	$wubkuErisimAdresi=isset($_POST["wubkuErisimAdresi"]) ? mysql_real_escape_string(strip_tags($_POST["wubkuErisimAdresi"])) : 'null';
	$bildirimMesaj=isset($_POST["bildirimMesaj"]) ? mysql_real_escape_string(strip_tags($_POST["bildirimMesaj"])) : 'null';
 
	$ayarlarDurum=mysql_num_rows(mysql_query("select * from tblAyarlar"));
	
	$sunucuKokDizinKontrol=substr($sunucuKokDizin, -1); 

	$wubkuErisimAdresiKontrol=substr($wubkuErisimAdresi, -1); 
	
	
	if ( !($sunucuKokDizinKontrol == "/") )
		$sunucuKokDizin = $sunucuKokDizin."/";
	
	if ( !($wubkuErisimAdresiKontrol == "/") )
		$wubkuErisimAdresi = $wubkuErisimAdresi."/";
			
 	
	if($ayarlarDurum==1){
 
		if(mysql_query("update tblAyarlar set bildirimEposta='$epostaBildirim',bildirimWUBKU='$wubkuBildirim',sunucuKokDizin='$sunucuKokDizin', wubkuErisimAdresi='$wubkuErisimAdresi', bildirimMesaj='$bildirimMesaj'")) {

			$sonuc="Güncelleme Yapýldý.";
		}
		else {
			$sonuc="Güncelleme yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
	 
	}

	else {

		if(mysql_query("insert into tblAyarlar (bildirimEposta,bildirimWUBKU,sunucuKokDizin, wubkuErisimAdresi,bildirimMesaj) values('$epostaBildirim','$guvenliWebBildirim','$sunucuKokDizin','$wubkuErisimAdresi','$bildirimMesaj')")) {

			$sonuc="Güncelleme Yapýldý.";
		}
		else {
			
			$sonuc="Güncelleme yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
		
		
	}
}

$ayarlar = mysql_fetch_row(mysql_query("select bildirimEposta,bildirimWUBKU,sunucuKokDizin, wubkuErisimAdresi, bildirimMesaj  from tblAyarlar")) or die(mysql_error());
 	
?> 
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Ayarlar</h2>
        <p>Uygulamalarýn temel yönetimine ait düzenlemeleri buradan yapabilirsiniz.</p>
      </div>
 
	  <form class="form-signin" role="form" name="frmAyarlar" method="post" action="ayarlar.php">
 
		 <div class="form-group">
                <label> Web Uygulamalarý Kök Dizi Yolu </label>
				<input type="text" class="form-control" id="sunucuKokDizin" value="<?php echo $ayarlar[2] ?>" name="sunucuKokDizin" required autofocus>
        </div>
					
		<div class="form-group">
				<label> WUBKU Eriþim Adresi </label>
				<input type="text" class="form-control" id="wubkuErisimAdresi" value="<?php echo $ayarlar[3] ?>" name="wubkuErisimAdresi" required autofocus>
        </div>
 
		<div class="form-group">		
				<label>Saldýrý tespitinini bildirim þekilleri</label>
                    <div class="checkbox">
                        <label><input type="checkbox" name="epostaBildirim" <?php if ( $ayarlar[0] == 1 ) echo "checked value=\"1\""; else echo "value=1"; ?> />E-posta</label>
                    </div>
 
                    <div class="checkbox">
                        <label><input type="checkbox" name="wubkuBildirim" <?php if ( $ayarlar[1] == 1 ) echo "checked value=\"1\""; else echo "value=\"1\""; ?> />Güvenli Web Saldýrý Takip Arayüzü</label>
                    </div>
					
					<div class="checkbox">
                        <label><input type="checkbox" name="bildirimMesaj" <?php if ( $ayarlar[4] == 1 ) echo "checked value=\"1\""; else echo "value=\"1\""; ?> />Kýsa Mesaj (SMS)</label>
                    </div>
					
		</div>				  
        <button type="submit" class="btn btn-success btn-sm form-control"  name="guncelle" value="guvenliWebAyarlar">Kaydet</button>
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
      </form>
    </div>
 
</body>
</html>