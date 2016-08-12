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

    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="yonetici.php">WUBKU</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
		  
		   <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Uygulamalar <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="uygulamalar.php">Uygulamalara G�zat</a></li>
                <li><a href="uygulamaEkle.php">Uygulama Ekle</a></li>
              </ul>
            </li>
			
			<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kullan�c�lar <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="kullanicilar.php">Kullan�c�lara G�zat</a></li>
                <li><a href="kullaniciEkle.php">Kullan�c� Ekle</a></li>
              </ul>
            </li>
 
			<li><a href="saldiriDurumu.php">Sald�r� Durumu</a></li>
			<li><a href="profil.php">Profil</a></li>
			<li><a href="ayarlar.php">Ayarlar</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
			<li> <?php echo $calismaDurumu ?></li>
            <li><a href="giris.php?islem=cikis">��k��</a></li>
			
          </ul>
        </div>
      </div>
    </div>