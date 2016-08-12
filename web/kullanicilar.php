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

	<title>WUBKU - kullan�c�lar </title>

</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'ustMenuYonetici.php';
 

?> 
 
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Kullan�c�lar</h2>
        <p>Uygulama �zerindeki kullan�c�lar� g�r�nt�leyebilir ve haklar�n� d�zenleyebilirsiniz.</p>
      </div>
	  	
		<table class="table table-striped table-hover">
					 
					 <tbody>
					<thead>
					<tr>
						<th>Kullan�c� Ad�</th>
						<th>Ad</th>
						<th>Soyad</th>
						<th>Eposta</th>
						<th>Durumu</th>
						<th>Rol�</th>
						<th>��lem</th>
					</tr>
				</thead>
						
		
<?php 

$kullaniciSorgu = mysql_query("select kullaniciId,kulAd, ad,soyad, eposta,kullaniciOnay,rolAd from tblKullanici,tblKullaniciRol where FKUyeRolId=rolId");

while ($kullaniciBilgileri = mysql_fetch_array($kullaniciSorgu)){
	
	$kullaniciId=$kullaniciBilgileri["kullaniciId"];
	$kulAd=$kullaniciBilgileri["kulAd"];
	$ad=$kullaniciBilgileri["ad"];
	$soyad=$kullaniciBilgileri["soyad"];
	$eposta=$kullaniciBilgileri["eposta"];
	$kullaniciOnay=$kullaniciBilgileri["kullaniciOnay"];
	$rolAd=$kullaniciBilgileri["rolAd"];
 
	if ($kullaniciOnay == 1)
		$kullaniciOnay="Aktif";
	else
		$kullaniciOnay="Pasif";
		
  echo "
	 	<tr> 
			<td>$kulAd</td> 
			<td>$ad</td>
			<td>$soyad</td>
			<td>$eposta</td>
			<td>$kullaniciOnay</td>
			<td>$rolAd</td>
			<td><a href=\"kullaniciDuzenle.php?kullaniciId=$kullaniciId\"> <button class=\"btn btn-primary\">Yetkilendir</button> </a></td>
		</tr>
		";
 }                              
	 
	?>	
	
    </div>
 
</body>
</html>