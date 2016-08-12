#!/bin/bash
# Web Uygulamalarý Bütünlük Kontrol Uygulamasý (WUBKU),  web uygulama kaynak kodu üzerinden bütünlük kontrolü yaparak, uygulamanýn bütünlüðünün korunmasýný saðlamaktadýr.

# Copyright (C) <2015>  <Durmuþ AYDOÃU>

#   This program is free software: you can redistribute it and/or modify
#   it under the terms of the GNU General Public License as published by
#   the Free Software Foundation, either version 3 of the License, or
#   any later version.

#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.

#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>

clear
echo -e "Veritabani adini giriniz: \c "
read  vtAdi

echo  -e "Veritabani kullanici adinini giriniz: \c "
read vtKullaniciAdi

echo  -e "Veritabani sifresini giriniz: \c " 
read vtSifresi

baglantiKontrol=$(mysql -u $vtKullaniciAdi -p$vtSifresi -e "use $vtAdi" )

if [  $? != 0 ]
then
 	echo "Veritabanina baglanilamadi. Girilen bilgileri kontrol ediniz.";
	exit 1;
fi

echo -e "Uygulama dizinini giriniz: \c "
read  uygulamaDizini

if [ -z "$uygulamaDizini" ]
then
	echo "Uygulama dizini belirtiniz"
	exit 1;
fi

if [ ! -d $uygulamaDizini ]
then
	echo "Uygulama dizini bulunamadi. Gecerli bir diizn yolu belirtiniz";
	exit 1;
fi

echo -e "Uygulama iceriginin aktarilacagi uygulama adini giriniz: \c "
read  uygulamaAdi

if [ -z "$uygulamaAdi" ]
then
	echo "Uygulama adini giriniz bilgisini belirtiniz."
	exit 1;
fi

uygulamaIdS=$(mysql  -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -e "select uygulamaId from tblUygulamalar where uygulamaAd='$uygulamaAdi'");

uygulamaIdDK='^[0-9]+$'
if ! [[ $uygulamaIdS =~ $uygulamaIdDK ]]
then
	echo "Uygulama adi bulunamadi. Uygulama adini kontrol ediniz.";
	exit 1;
fi

echo -e "Uygulama uzerindeki yetkili kullanici adini giriniz: \c "
read  kullaniciAdi

kullaniciKontrolS=$(mysql  -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -e "select kullaniciId from tblKullanici where kulAd='$kullaniciAdi'");

kullaniciKontrolK='^[0-9]+$'
if ! [[ $kullaniciKontrolS =~ $kullaniciKontrolK ]]
then
        echo "Kullanici bulunamadi. Kullanici adini kontrol ediniz.";
        exit 1;
fi

echo -e "WUBKU GOC ile birlikte gelen wubkuGoc.php dosyasina erisim adresini giriniz. Orn: http://192.168.32.129/html/wubku/wubkuGoc.php: \c "
read  erisimAdresi

mevcutDizinleriSil=$(mysql  -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -e "delete from tblDizinler where FKUygulamaId='$uygulamaIdS'");

if [  $? != 0 ]
then
       echo "Mevcut dizinler silinemedi. Veritabanina bilgilerini kontrol ediniz.";
        exit 1;
else
	echo "Mevcut dizin bilgileri silindi.";
fi

echo "Dizinler WUBKU ya aktariliyor.";

for dizinBilgisi in $( find $uygulamaDizini -type d)
do

	dizin=$(echo $dizinBilgisi )
	dosyaAdi=${dizin##*.}
	dizin1=$(echo $dosyaAdi | cut -d'/' -f2-)"/"
	curl -F dizinYolu="$dizin1" -F uygulamaId="$uygulamaIdS" -F islem="dizinAktar" -F kullaniciId="$kullaniciKontrolS" $erisimAdresi -o /dev/null
	echo "Aktarilan dizin:" $dizin1;	
	echo "Uygulama Id:" $uygulamaIdS;
done
echo "Dizinlerin aktarim islemi bitti.";

echo "Dosyalarin aktarim islemi basliyor";
for dosyaBilgisi in $( find $uygulamaDizini -type f)
do

	ydosyaYolu=$( echo "$dosyaBilgisi"   | cut -d'/' -f2- | rev | cut -d'/' -f2- | rev)"/";
	dosyaAdi=$(echo $dosyaBilgisi | rev | cut -d '/' -f1 |  rev )"/";

	if [ "$dosyaAdi" == "$ydosyaYolu" ]
	then
        	ydosyaYolu="/";
	fi

	dizinId=$(mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -e "select dizinId from tblDizinler where dizinYol='$ydosyaYolu' and FKUygulamaId='$uygulamaIdS'");

	curl -F dosya=@$dosyaBilgisi -F uygulamaDizinler="$dizinId" -F uygulamaId="$uygulamaIdS" -F islem="dosyaAktar" -F kullaniciId="$kullaniciKontrolS" $erisimAdresi -o /dev/null

	echo "Aktarilan dosya:" $dosyaBilgisi;
done
echo "Uygulamanin WUBKU ya aktarimi basarili olarak yapildi.";
