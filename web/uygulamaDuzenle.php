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
 
	<title>WUBKU - uygulama d�zenle </title>
	 
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'ustMenuYonetici.php';
include 'fonksiyonlar.php';
 

$uygulamaDuzenle=isset($_REQUEST["uygulamaDuzenle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["uygulamaDuzenle"])) : 'null';
$uygulamaId=isset($_REQUEST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["uygulamaId"])) : 'null';
 
$sonuc="null";
 
if($uygulamaDuzenle != "null") { 

	$uygulamaAdi=isset($_POST["uygulamaAdi"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaAdi"])) : 'null';
	$uygulamaKokDizin=isset($_POST["uygulamaKokDizin"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaKokDizin"])) : 'null';
	$uygulamaDurum=isset($_POST["uygulamaDurum"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaDurum"])) : 'null';
 
	$dizinAdKontrol=substr($uygulamaKokDizin, -1); 
	
	if ( !($dizinAdKontrol == "/") )
			$uygulamaKokDizin = $uygulamaKokDizin."/";
			
	$tarih = date('Y-m-d H:i:s');
	
	$eskiDizinAdi = mysql_fetch_row(mysql_query("select uygulamaKokDizin from tblUygulamalar where uygulamaId='$uygulamaId'")) or die(mysql_error());
	
	if ($uygulamaDurum == 0)
		$uygulamaDurumu="Pasif";
	else	
		$uygulamaDurumu="Aktif";
	
	 if(mysql_query("update tblUygulamalar set uygulamaAd='$uygulamaAdi',uygulamaDurum='$uygulamaDurum',uygulamaKokDizin='$uygulamaKokDizin' where uygulamaId='$uygulamaId'")) {

			$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
			
			dizinDuzenle( $sunucuKokDizin[0]."".$eskiDizinAdi[0], $sunucuKokDizin[0]."".$uygulamaKokDizin, $sunucuKokDizin[0]."".$uygulamaKokDizin,$uygulamaId );
			
			
			
			$mesaj=$kulId. " numaral� ".$kulAd." y�neticisi ". $uygulamaAdi . " uygulama bilgilerini g�ncelledi. Uygulama ad�: ".$uygulamaAdi ." k�k dizini: ".$uygulamaKokDizin." durumu: ".$uygulamaDurumu;
 
			$sonuc=islemiKaydet ($mesaj,$uygulamaId);
			
			if ($sonuc == 0 ) {
				$sonuc="Uygulama D�zenlendi.";
			}
			else 
				$sonuc="��lem yap�l�rken hata olu�tu. Tekrar Deneyiniz.";
				
				
			
			
		}
		else {
			
			$sonuc="��lem yap�l�rken hata olu�tu. Tekrar Deneyiniz.";
		}
 
}

$uygulamaBilgileri = mysql_fetch_row(mysql_query("select uygulamaAd,uygulamaDurum,uygulamaKokDizin, uygulamaId  from tblUygulamalar where uygulamaId='$uygulamaId'")) or die(mysql_error());


?> 
 
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Uygulama D�zenle</h2>
        <p>Uygulamalar� d�zenleme i�lemini buradan yapabilirsiniz.</p>
      </div>
 
	  <form class="form-signin" role="form" name="frmUygulamaDuzenle" method="post" action="uygulamaDuzenle.php">

		 <div class="form-group">
                <label> Uygulama Ad� </label>
				<input type="text" class="form-control" id="uygulamaAdi" value="<?php echo $uygulamaBilgileri[0]; ?>" name="uygulamaAdi" required >
        </div>
		
		 <div class="form-group">
                <label> Uygulama K�k Dizini </label>
				<input type="text" class="form-control" id="uygulamaKokDizin" value="<?php echo $uygulamaBilgileri[2]; ?>" name="uygulamaKokDizin" required autofocus>
        </div>
		
		 <div class="form-group">
                <label> Uygulama Durum </label>
				
				<select class="form-control" name="uygulamaDurum" required  >
                    <?php
                        if($uygulamaBilgileri[1]=="1"){
                            echo "<option value='1' selected='selected' >Aktif</option>";
                            echo "<option value='0' >Pasif</option>";
                        }
                        else {
                            echo "<option value='1' >Aktif</option>";
                            echo "<option value='0' selected='selected' >Pasif</option>";
                        }
                    ?>
				</select>
        </div>
		
		
   
        <button type="submit" class="btn btn-success btn-sm form-control" name="uygulamaDuzenle" value="uygulamaDuzenle">G�ncelle</button>
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
		<input type="hidden" name="uygulamaId" value="<?php echo $uygulamaBilgileri[3]; ?>" />
      </form>
    </div>
 
</body>
</html>