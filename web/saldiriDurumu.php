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
	<title>WUBKU - kay�t takip </title>
	 
</head>
<body>
 
<?php 
include 'vtBaglanti.php';
include 'ustMenuYonetici.php';
include 'oturumKontrol.php';
 
?>


    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>WUBKU Kay�t Takip</h2>
        <p>Sistemin tespit etti�i sald�r�lar� ve yap�lan i�lemleri buradan takip edebilirsiniz.</p>
      </div>
	  	
	  <div class="row">
	  <div class="col-sm-9">
	  <center> <a href="#saldirilar" data-toggle="tab" class="btn btn-default">Sald�r�lar</a> 
       <a href="#islemler" data-toggle="tab" class="btn btn-default">��lemler</a> </center>
		</div>		
		</div>		
	  <div id="myTabContent" class="tab-content">
		
		<div class="tab-pane fade in" id="saldirilar">
		
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Tarih</th>
						<th>Sald�r�&nbsp;Mesaj</th>
						<th>Eposta&nbsp;G�nderildi&nbsp;Mi</th>
					</tr>
				</thead>
				<tbody>	
		
				<?php 
 
					$saldirilarSorgu = mysql_query("select * from tblSaldirilar order by saldiriTarih desc");
					$saldiriSayisi= mysql_num_rows($saldirilarSorgu);
					echo "<center><h1> Toplam Sald�r� Say�s� :  $saldiriSayisi</h1></center>";
					while ($saldiriBilgileri = mysql_fetch_array($saldirilarSorgu)){
						$saldiriTarih=$saldiriBilgileri["saldiriTarih"];
						$saldiriMesaj=$saldiriBilgileri["saldiriMesaj"];
						$epostaGonderildiMi=$saldiriBilgileri["epostaGonderildiMi"];

						if ( $epostaGonderildiMi == 1)
								$epostaGonderildiMi="Evet";
						else
								$epostaGonderildiMi="Hay�r";
						echo "
						<tr> 
							<td>$saldiriTarih</td> 
							<td>$saldiriMesaj</td>
							<td>$epostaGonderildiMi</td>
						</tr>
						";
					}
				?>
				</tbody>
				</table>
           </div>
		   
		  <div class="tab-pane fade in" id="islemler">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Tarih</th>
						<th>��lem</th>
					</tr>
				</thead>
				<tbody>	
		
				<?php 
 
					$islemSorgu = mysql_query("select * from tblIslemler order by islemTarih desc");
					$islemSayisi= mysql_num_rows($islemSorgu);
					echo "<center><h1> Toplam ��lem Say�s� :  $islemSayisi</h1></center>";
					while ($islemBilgileri = mysql_fetch_array($islemSorgu)){
						$islemTarih=$islemBilgileri["islemTarih"];
						$islemMesaj=$islemBilgileri["islemMesaj"];

						echo "
						<tr> 
							<td>$islemTarih</td> 
							<td>$islemMesaj</td>
						</tr>
						";
					}
				?>
				</tbody>
				</table>
				
           </div>
		</div>		   
    </div>
 
</body>
</html>