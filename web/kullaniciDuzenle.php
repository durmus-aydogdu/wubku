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
	<title>WUBKU - kullanýcý düzenle </title>
	 
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'ustMenuYonetici.php';
 

$kullaniciDuzenle=isset($_REQUEST["kullaniciDuzenle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["kullaniciDuzenle"])) : 'null';
$kullaniciId=isset($_REQUEST["kullaniciId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["kullaniciId"])) : 'null';
$sonuc="null";


if($kullaniciDuzenle != "null") { 
		
	$kullaniciRol=isset($_POST["kullaniciRol"]) ? mysql_real_escape_string(strip_tags($_POST["kullaniciRol"])) : 'null';
	$kullaniciDurum=isset($_POST["kullaniciDurum"]) ? mysql_real_escape_string(strip_tags($_POST["kullaniciDurum"])) : 'null';
	$kullaniciId=isset($_POST["kullaniciId"]) ? mysql_real_escape_string(strip_tags($_POST["kullaniciId"])) : 'null';
	 
	 
	 
	 
	 
	 if(!empty($_POST['uygulamaYetki'])) {
	 
	 mysql_query("delete from tblYetkiler where FKKullaniciId = '$kullaniciId'");
    foreach($_POST['uygulamaYetki'] as $uygulamaId) {
 
	mysql_query("insert into tblYetkiler (FKKullaniciId, FKUygulamaId) values('$kullaniciId','$uygulamaId')");
   
    }
}

 
   
	 if(mysql_query("update tblKullanici set FKUyeRolId='$kullaniciRol',kullaniciOnay='$kullaniciDurum' where kullaniciId='$kullaniciId'")) {

			$sonuc="Kullanýcý Güncellendi.";
		}
		else {
			
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
 
}
$kullaniciBilgileri = mysql_fetch_row(mysql_query("select kullaniciId,kullaniciOnay,rolId,rolAd from tblKullanici,tblKullaniciRol  where FKUyeRolId=rolId and kullaniciId='$kullaniciId'")) or die(mysql_error());
$kullaniciRolleri = mysql_query(" select * from tblKullaniciRol where rolId != '$kullaniciBilgileri[2]'") or die(mysql_error());
$kullaniciYetkileri = mysql_query("select uygulamaId,uygulamaAd from tblUygulamalar where uygulamaId IN (select FKUygulamaId from tblYetkiler where FKKullaniciId='$kullaniciId')") or die(mysql_error());
$kullaniciYetkileri2 = mysql_query("select uygulamaId,uygulamaAd from tblUygulamalar where uygulamaId NOT IN  (select uygulamaId from tblUygulamalar where uygulamaId IN (select FKUygulamaId from tblYetkiler where FKKullaniciId='$kullaniciId'))") or die(mysql_error());

								
?> 
 
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Kullanýcý Düzenle</h2>
        <p>Kullanýcý bilgilerini düzenleme ve uygulamalar üzerinde yetkilendirme iþlemini buradan yapabilirsiniz.</p>
      </div>
 
	  <form class="form-signin" role="form" name="frmUygulamaDuzenle" method="post" action="kullaniciDuzenle.php">
 
		 <div class="form-group">
                <label> Kullanýcý Rolü </label>
				<select id="kullaniciRol" name="kullaniciRol"  required  class="form-control">
					 <option value="<?php echo $kullaniciBilgileri[2] ?>" selected="selected"> <?php echo $kullaniciBilgileri[3] ?> </option>
									<?php
										while ($roller = mysql_fetch_array($kullaniciRolleri)) {
											$rolAd = $roller['rolAd'];
											$rolId = $roller['rolId'];	 
									 
											echo "<option value=\"$rolId\">$rolAd</option>";
										 
										}
										?>
									</select>
									
									
				 
        </div>
		
		 
		
		 <div class="form-group">
                <label> Kullanýcý Durum </label>
				
				<select class="form-control" name="kullaniciDurum" required  >
                    <?php
                        if($kullaniciBilgileri[1]=="1"){
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
		
		<div class="form-group">		
				<label>Yetkili Olduðu Uygulamalar</label>
				<?php
					while ($yetkiBilgileri = mysql_fetch_array($kullaniciYetkileri)) {
							$uygulamaId = $yetkiBilgileri['uygulamaId'];
							$uygulamaAd = $yetkiBilgileri['uygulamaAd'];
							 
                   echo  "<div class=\"checkbox\">
							<label><input type=\"checkbox\" name=\"uygulamaYetki[]\" value=\"$uygulamaId\" checked />$uygulamaAd</label>
						  </div>";
				}	
				
				while ($yetkiBilgileri2 = mysql_fetch_array($kullaniciYetkileri2)) {
							$uygulamaId = $yetkiBilgileri2['uygulamaId'];
							$uygulamaAd = $yetkiBilgileri2['uygulamaAd'];
							 
                   echo  "<div class=\"checkbox\">
							<label><input type=\"checkbox\" name=\"uygulamaYetki[]\" value=\"$uygulamaId\" />$uygulamaAd</label>
						  </div>";
				}	
				
				
					?>
		</div>				
		
        <button type="submit" class="btn btn-success btn-sm form-control" name="kullaniciDuzenle" value="kullaniciDuzenle">Güncelle</button>
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
		<input type="hidden" name="kullaniciId" value="<?php echo $kullaniciBilgileri[0]; ?>" />
      </form>
    </div>
 
</body>
</html>