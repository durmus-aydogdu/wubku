#!/bin/bash
# Web Uygulamalarý Bütünlük Kontrol Uygulamasý (WUBKU),  web uygulama kaynak kodu üzerinden bütünlük kontrolü yaparak, uygulamanýn bütünlüðünün korunmasýný saðlamaktadýr.

# Copyright (C) <2015>  <Durmuþ AYDOÐDU>

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

saldirilarLogDosyasi="/var/log/WUBKU.log";

vtSifresi=$(cat $1  | grep vtSifresi | cut -d'=' -f2-);
vtAdi=$(cat $1   | grep vtAdi | cut -d'=' -f2-);
vtKullaniciAdi=$( cat $1  | grep vtKullaniciAdi | cut -d'=' -f2-);
uygulamaSunucuKokDizin="";

baglantiKontrol=$(mysql -u $vtKullaniciAdi -p$vtSifresi -e "use $vtAdi" )

if [  $? != 0 ]
then
	clear
        echo "Veritabanina baglanilamadi. Girilen bilgileri kontrol ediniz.";
        exit 1;
fi

function ayarlarOku () {
	uygulamaSunucuKokDizin=$(mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -N -e "select sunucuKokDizin  from tblAyarlar")
	epostaBildirim=$(mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -N -e "select bildirimEposta  from tblAyarlar")
	wubkuBildirim=$(mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -N -e "select bildirimWUBKU  from tblAyarlar")

	if [ ! -d $uygulamaSunucuKokDizin ]
	then
		tarih=$(date '+%Y-%m-%d %H:%M:%S');
		echo "$tarih Uygulamalarin bulundugu kok dizin uzerinde degisiklik tespit edildi. Kok dizin yeniden olusturuyor." >> $saldirilarLogDosyasi;
		saldiriTespitVeritabaninaYaz "Uygulamalarin bulundugu kok dizin uzerinde degisiklik tespit edildi. Kok dizin yeniden olusturuluyor." 0 "$tarih"
		mkdir -p $uygulamaSunucuKokDizin
		chown -R www-data $uygulamaSunucuKokDizin
	fi

	if [ ! -f $saldirilarLogDosyasi ]
        then
                touch $saldirilarLogDosyasi;
        fi
}



function butunUygulamalarinOzetDegerininiAl () {
        butunUygulamalarinOzetDegeriSorgu=$(mysql -u $vtKullaniciAdi -p$vtSifresi  -D $vtAdi -s -N -e "select uygulamaOzetDeger from tblUygulamaBilgileri")
}


function butunUygulamalarinOzetDegerininiHesapla () {
	butunUygulamalarDosyaOzetDegeri=$(find $uygulamaSunucuKokDizin -type f  -print0 | xargs -0 sha512sum | sha512sum | cut -d' ' -f1);
	butunUygulamalarDizinOzetDegeri=$(find $uygulamaSunucuKokDizin  -type d -print0 | sha512sum | cut -d' ' -f1);
	butunUygulamalarinOzetDegeriHesaplanan=$(echo $butunUygulamalarDosyaOzetDegeri $butunUygulamalarDizinOzetDegeri |sha512sum |  cut -d' ' -f1);
}

function saldiriTespitVeritabaninaYaz () {
	
	if [ $epostaBildirim == 1 ]
	then
		$(mysql -u $vtKullaniciAdi -p$vtSifresi  -D $vtAdi -s -N -e "insert into tblSaldirilar (saldiriTarih, epostaGonderildiMi,saldiriMesaj,FKUygulamaId) values('$3',0,'$1','$2')")
	fi
}


function eklenenDosyalariSil () {
	
	Array1=();
        Array2=();
        Array3=();
	kokDizin="/"; 
	
	if [ ! -d "$2" ]
	then
		 degisenDizinleriDuzelt $1 $2 $3
	fi 
 
	while read -r dosyaBilgileri
                do
                        Array1+=("$dosyaBilgileri")
        done < <(find $2 -type f | sort)

        while read -r dosyaBilgileri
                do

		        dosyaAdi=$(echo $dosyaBilgileri |cut -d ' ' -f1);
	                dosyaYolu=$(echo $dosyaBilgileri | cut -d ' ' -f2 | cut -d'\' -f1);

			if [ $dosyaYolu == "$kokDizin" ]
			then
				dosyaYolu="";
			fi

                        Array2+=("$2$dosyaYolu$dosyaAdi")

                done < <(mysql -u $vtKullaniciAdi -p$vtSifresi   -D $vtAdi  -s -e " select dosyaAd, dizinYol from tblDosyalar as dos, tblDizinler as diz  where dos.FKUygulamaId=diz.FKUygulamaId and dos.FKUygulamaId='$1' and FKDizinId=dizinId ")

        #yeni olusturulan dosyalari tespit islemi
        for i in "${Array1[@]}"
	do
		dosyaKontrol=0;
		for j in "${Array2[@]}" 
		do
			if [ $i == $j ]
			then
				dosyaKontrol=1;
				break;
			fi
		done
             
		if [ $dosyaKontrol == 0 ]
		then

			tarih=$(date '+%Y-%m-%d %H:%M:%S');
                        saldiriTespitVeritabaninaYaz "$3 uygulamasina ait $2 yolunda $i adli dosya tespit edildi." $1 "$tarih"
                        echo "$tarih $3 uygulamasina ait $2 yolunda $i adli dosya tespit edildi." >> $saldirilarLogDosyasi;

			tarih=$(date '+%Y-%m-%d %H:%M:%S');
			rm -rf $i
			saldiriTespitVeritabaninaYaz "$3 uygulamasina ait $2 yolunda $i adli dosya silindi." $1 "$tarih"
                        echo "$tarih $3 uygulamasina ait $2 yolunda $i adli dosya silindi." >> $saldirilarLogDosyasi;
		fi
        done
}

function degisenDizinleriDuzelt () {
	Array1=();
        Array2=();
        Array3=();
	Array4=();
        kokDizin="/";
	
	if [ ! -d $2 ]
	then

		tarih=$(date '+%Y-%m-%d %H:%M:%S');
		saldiriTespitVeritabaninaYaz "$3 adli uygulama kok dizinin silindigi tespit edildi." $1 "$tarih"
                echo "$tarih $3 adli uygulama kok dizinin silindigi tespit edildi." >> $saldirilarLogDosyasi;

		mkdir -p $2
		
		chown -R www-data $2
		saldiriTespitVeritabaninaYaz "$3 adli uygulama kok dizini yeniden olusturuldu." $1 "$tarih"
                echo "$tarih $3 adli uygulama kok dizini yeniden olusturuldu." >> $saldirilarLogDosyasi;
	fi

	while read -r dizinBilgileriBulunan
                do
			if [ ! "$dizinBilgileriBulunan" == "$2" ]
			then
				dizinBilgileriBulunan=$dizinBilgileriBulunan"/";
				if [ !  $dizinBilgileriBulunan == $2 ]
				then
					Array1+=("$dizinBilgileriBulunan")
				fi
			fi
                done < <(find $2 -type d | sort)

        while read -r dizinBilgileriSorgu
                do
                        if [ ! "$dizinBilgileriSorgu" == "$kokDizin" ]
                        then
				Array2+=("$2$dizinBilgileriSorgu")
			fi
                done < <(mysql -u $vtKullaniciAdi -p$vtSifresi   -D $vtAdi  -s -e "select dizinYol from tblDizinler where FKUygulamaId='$1' order by dizinYol asc")


	for j in "${Array2[@]}"
         do


                if [ ! -d $j ]
                then

			tarih=$(date '+%Y-%m-%d %H:%M:%S');
                        saldiriTespitVeritabaninaYaz "$3 adli uygulamaya ait $j dizininin silindigi tespit edildi." $1 "$tarih"
                        echo "$tarih $3 adli uygulamaya ait $2$j dizininin silindigi tespit edildi." >> $saldirilarLogDosyasi;

                        mkdir -p $j
                        chown -R www-data $j
			tarih=$(date '+%Y-%m-%d %H:%M:%S');
                        saldiriTespitVeritabaninaYaz "$3 adli uygulamaya ait $j dizini yeniden olusturuldu." $1 "$tarih"
                        echo "$tarih $3 adli uygulamaya ait $2$j dizini yeniden olusturuldu." >> $saldirilarLogDosyasi;
                fi

        done

        for i in "${Array1[@]}"
	do
		eklenenDizinKontrol=0;

		for j in "${Array2[@]}"
		do
			if [ $i == $j ]
			then
				eklenenDizinKontrol=1;
			fi
		done

		if [ $eklenenDizinKontrol == 0 ]
		then
			tarih=$(date '+%Y-%m-%d %H:%M:%S');
					                 
			saldiriTespitVeritabaninaYaz "$3 uygulamasinda $i isimli bir dizin tespit edildi." $1 "$tarih"
                	echo "$tarih $3 uygulamasinda $i isimli bir dizin tespit edildi." >> $saldirilarLogDosyasi;
	
			rm -rf $i
	
			saldiriTespitVeritabaninaYaz "$3 uygulamasinda tespit edilen $i dizini silindi." $1 "$tarih"
                       	echo "$tarih $3  uygulamasinda tespit edilen $i dizini silindi." >> $saldirilarLogDosyasi;
		fi
	done

}

function degisenDosyalariDuzelt () {

	wubkuErisimAdresi=$(mysql -u $vtKullaniciAdi -p$vtSifresi  -D $vtAdi  -s -e "select wubkuErisimAdresi from tblAyarlar ");
	
	eklenenDosyalariSil $1 $2 $3
	dosyaKokDizin="/";	
	while read -r uygulamaDosyaBilgileri
        do
                myarray+=("$uygulamaDosyaBilgileri");
                dosyaAdi=$(echo $uygulamaDosyaBilgileri |cut -d ' ' -f1);
                dosyaOzetDegeri=$(echo $uygulamaDosyaBilgileri | cut -d ' ' -f2 | cut -d'\' -f1);
                dizinId=$(echo $uygulamaDosyaBilgileri |cut -d ' ' -f3);
                dosyaId=$(echo $uygulamaDosyaBilgileri |cut -d ' ' -f4);
		dosyaYolu=$(mysql -u $vtKullaniciAdi -p$vtSifresi  -D $vtAdi  -s -e "select dizinYol from tblDizinler where dizinId='$dizinId' and FKUygulamaId='$1'");
                mDosyaOzetDeger="";
		
		if [  "$dosyaYolu" == "$dosyaKokDizin" ]
		then
			dosyaYolu="";
		fi

 		if [ ! -f "$2$dosyaYolu$dosyaAdi" ];
                then
                        tarih=$(date '+%Y-%m-%d %H:%M:%S');
                        echo "$tarih $uygulamaAdi uygulamasinin $2$dosyaYolu yolundaki $dosyaAdi dosyasi bulunamadi." >> $saldirilarLogDosyasi;
                        saldiriTespitVeritabaninaYaz "$uygulamaAdi uygulamasinin $2$dosyaYolu yolundaki $dosyaAdi dosyasi bulunamadi." $1 "$tarih"

                        rm -rf   $2$dosyaYolu$dosyaAdi
                        wget -O $2$dosyaYolu$dosyaAdi  $wubkuErisimAdresi""dosyaIndir.php?id=$dosyaId -o /dev/null

                        tarih=$(date '+%Y-%m-%d %H:%M:%S');
                        saldiriTespitVeritabaninaYaz "$uygulamaAdi uygulamasinin $2$dosyaYolu yolundaki $dosyaAdi adli dosyasi duzeltildi." $1 "$tarih"
                        echo "$tarih $uygulamaAdi uygulamasinin $2$dosyaYolu yolundaki $dosyaAdi adli dosyasi duzeltildi." >> $saldirilarLogDosyasi;
                        chown www-data $2$dosyaYolu$dosyaAdi;
                fi

                
		if [ -f "$2$dosyaYolu$dosyaAdi" ];
                then
                        mDosyaOzetDeger=$(sha512sum $2$dosyaYolu$dosyaAdi | cut -d' ' -f1 );
                fi

                if [ "$dosyaOzetDegeri" !=  "$mDosyaOzetDeger" ]
                then
			tarih=$(date '+%Y-%m-%d %H:%M:%S');	
			saldiriTespitVeritabaninaYaz "$uygulamaAdi uygulamasinin $2$dosyaYolu dizinindeki $dosyaAdi adli dosyasinin degistigi tespit edildi." $1 "$tarih"
			echo "$tarih $uygulamaAdi uygulamasinin $2$dosyaYolu dizinindeki $dosyaAdi adli dosyasinin  degistigi tespit edildi." >> $saldirilarLogDosyasi;


                        while read -r dosyaBilgileriDegisen
                        do
                                rm -rf $2$dosyaYolu$dosyaAdi
                                
				if [ -d $2$dosyaYolu ]
                                then
                                        wget -O $2$dosyaYolu$dosyaAdi $wubkuErisimAdresi""dosyaIndir.php?id=$dosyaId -o /dev/null
                                
					tarih=$(date '+%Y-%m-%d %H:%M:%S');	
					saldiriTespitVeritabaninaYaz "$uygulamaAdi uygulamasinin $2$dosyaYolu dizinindeki $dosyaAdi adli dosyasi duzeltildi." $1 "$tarih"
                        		echo "$tarih $uygulamaAdi uygulamasinin $2$dosyaYolu dizinindeki $dosyaAdi adli dosyasi duzeltildi." >> $saldirilarLogDosyasi;
					chown www-data $2$dosyaYolu$dosyaAdi;	
				fi


                        done < <(mysql -u $vtKullaniciAdi -p$vtSifresi  -D $vtAdi -s -N -e "select dosya from tblDosyalar where dosyaAd='$dosyaAdi' and FKDizinId='$dizinId' and FKUygulamaId='$1'")
                fi

		if [ ! -d "$2$dosyaYolu" ]
		then
			degisenDizinleriDuzelt  $1 $2 $3
		fi
                
        done < <(mysql -u $vtKullaniciAdi -p$vtSifresi  -D $vtAdi  -s -e "select dosyaAd, dosyaOzetDeger, FKDizinId, dosyaId from tblDosyalar, tblUygulamalar where uygulamaId=FKUygulamaId and uygulamaId='$1'") 

}


function degisenUygulamayiBul () {
	
	while read -r uygulamaBilgileri
        do
		uygulamaId=$(echo $uygulamaBilgileri |cut -d ' ' -f1);	
	    	uygulamaAdi=$(echo $uygulamaBilgileri |cut -d ' ' -f2);
            	uygulamaKokDizin=$(echo $uygulamaBilgileri |cut -d ' ' -f3);
            	uygulamaDizinOzetDegeri=$(echo $uygulamaBilgileri |cut -d ' ' -f4);
		uygulamaDosyaOzetDegeri=$(echo $uygulamaBilgileri |cut -d ' ' -f5);
		uygulamaYolu=$uygulamaSunucuKokDizin$uygulamaKokDizin;
		
		if [ -d $uygulamaYolu ]
                then
			hUygulamaDizinOzetDegeri=$(find $uygulamaYolu -type d -print0 | sha512sum | cut -d' ' -f1);
			hUygulamaDosyaOzetDeger=$(find $uygulamaYolu -type f  -print0 | xargs -0 sha512sum | sha512sum | cut -d' ' -f1);
		fi
		
		if [ "$hUygulamaDizinOzetDegeri" != "$uygulamaDizinOzetDegeri" ]
		then
			degisenDizinleriDuzelt $uygulamaId $uygulamaYolu $uygulamaAdi
		fi
		
		if [ "$hUygulamaDosyaOzetDeger" !=  "$uygulamaDosyaOzetDegeri" ]
		then
			degisenDosyalariDuzelt $uygulamaId $uygulamaYolu $uygulamaAdi
		fi	

	done < <(mysql -u $vtKullaniciAdi -p$vtSifresi  -D $vtAdi  -s -e "select uygulamaId, uygulamaAd, uygulamaKokDizin, uygulamaDizinOzetDeger, uygulamaDosyaOzetDeger  from tblUygulamalar")

}

function kokDiziniKontrolEt () {
        Array10=();
        Array11=();
        Array12=();

        while read -r uygulamaKokDizinBilgileri
                do
			if [ ! $uygulamaKokDizinBilgileri == $uygulamaSunucuKokDizin ]
                        then
				uygulamaKokDizinBilgileri=$uygulamaKokDizinBilgileri"/";
				Array10+=("$uygulamaKokDizinBilgileri")
			fi

                done < <(find $uygulamaSunucuKokDizin  -maxdepth 1 | sort)


        while read -r sorguUygulamaKokDizinBilgileri
                do
                        Array11+=("$uygulamaSunucuKokDizin$sorguUygulamaKokDizinBilgileri")
                done < <(mysql -u $vtKullaniciAdi -p$vtSifresi   -D $vtAdi  -s -e "select uygulamaKokDizin from tblUygulamalar  order by uygulamaKokDizin asc")

        #yeni olusturulan dosyalari tespit islemi
        for i in "${Array10[@]}"
	do
		kokDizinKontrol=0;

		for j in "${Array11[@]}" 
		do
			if [ $i == $j ]
			then
				kokDizinKontrol=1
			fi
		done
			if [ $kokDizinKontrol == 0 ]
			then
				tarih=$(date '+%Y-%m-%d %H:%M:%S');	
				saldiriTespitVeritabaninaYaz "Uygulamalarin bulundugu kok dizin uzerinde olusturulmus dosya/dizin tespit edildi." 0 "$tarih"
         		        echo "$tarih Uygulamalarin bulundugu kok dizin uzerinde olusturulmus dosya/dizin tespit edildi." >> $saldirilarLogDosyasi;
                		
				rm -rf $i;
				
                                saldiriTespitVeritabaninaYaz "Uygulamalarin bulundugu kok dizin uzerinde olusturulmus $i adli dosya/dizin silindi." $1 "$tarih"
                                echo "$tarih Uygulamalarin bulundugu kok dizin uzerinde olusturulmus $i adli dosya/dizin silindi." >> $saldirilarLogDosyasi;
			fi

        done
}
while :
do
	ayarlarOku
	kokDiziniKontrolEt		
        butunUygulamalarinOzetDegerininiAl
        butunUygulamalarinOzetDegerininiHesapla
		
	if [ ! "$butunUygulamalarinOzetDegeriSorgu" ==  "$butunUygulamalarinOzetDegeriHesaplanan" ]; then
	      	degisenUygulamayiBul
        fi
	
done

