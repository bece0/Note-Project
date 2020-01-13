
CREATE TABLE IF NOT EXISTS `kullanici` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `admin` tinyint NOT NULL DEFAULT 0,
 `adi` varchar(30) NOT NULL,
 `soyadi` varchar(30) NOT NULL,
 `kodu` varchar(100) NOT NULL,
 `parola` varchar(255) NOT NULL,
 `salt` varchar(255) NOT NULL,
 `email` varchar(50) NOT NULL,
 `kayit_tarihi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `son_giris_tarihi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `ayarlar` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `kullanici_id` int(11) NOT NULL UNIQUE,
 `duyuru_mail` varchar(5) NOT NULL DEFAULT "no",
 `yeni_etkinlik_mail` varchar(5) NOT NULL DEFAULT "no",
 `gecmis_private` varchar(5) NOT NULL DEFAULT "no",
 `gelecek_private` varchar(5) NOT NULL DEFAULT "no",
 `dil` varchar(25) NOT NULL DEFAULT "Türkçe",
 `sehir` varchar(50) DEFAULT NULL,
 `guncelleme_tarihi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `dersler` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `kodu` varchar(100) NOT NULL,
 `isim` varchar(255) NOT NULL,
 `kontenjan` varchar(100) NOT NULL,
 `aciklama` text NOT NULL,
 `bolum_adi` text NOT NULL,
 `sinif` text NOT NULL,
 `duzenleyen_id` int(11) NOT NULL,
 `status` tinyint NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `katilimci` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `ogrenci_id` int(11) NOT NULL,
 `ders_id` int(11) NOT NULL,
 `kayit_tarihi` datetime NOT NULL,
 `tip` INT NOT NULL DEFAULT '0'
 PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `yorum` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `kullanici_id` int(11) DEFAULT 0,
 `ders_id` int(11) DEFAULT 0,
 `tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `icerik` text NOT NULL,
 `onay_durum` tinyint NOT NULL DEFAULT 0,
 `onay_tarih` datetime,
 `onaylayan_id` int(11),
 PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `hata` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `kullanici_id` int(11) NOT NULL,
 `tarih` datetime NOT NULL,
 `tip` text NOT NULL,        -- ERROR, WARNING, INFO, FATAL
 `baslik` text NOT NULL,
 `icerik` text NOT NULL,
 PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `gunluk` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `kullanici_id` int(11) DEFAULT 0,
 `ders_id` int(11) DEFAULT 0,
 `tarih` datetime NOT NULL,
 `tip` varchar(100) NOT NULL, -- USER, EVENT, SYSTEM
 `baslik` varchar(250) NOT NULL,
 `mesaj` text NOT NULL,
 PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bildirim`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `url` text NULL,
    `mesaj` text NOT NULL,
    `tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `kullanici_id` int(11) NOT NULL,
    `ders_id` int(11) NOT NULL DEFAULT 0,
    `goruldu`tinyint(4) NOT NULL DEFAULT 0,
    `goruldu_tarihi` datetime NULL,
    `tip` varchar(255) NOT NULL DEFAULT "NORMAL",
    PRIMARY KEY (`id`),
    KEY `idx_bildirim_kullanici_id` (`kullanici_id`)
)CHARACTER SET latin5 COLLATE latin5_turkish_ci;


CREATE TABLE IF NOT EXISTS `duyuru`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `mesaj` text NOT NULL,
    `tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `kullanici_id` int(11) NOT NULL,
    `ders_id` int(11) NOT NULL DEFAULT 0,
    `tip` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_duyuru_kullanici_id` (`kullanici_id`),
    KEY `idx_bildirim_ders_id` (`ders_id`)
)CHARACTER SET latin5 COLLATE latin5_turkish_ci;


CREATE TABLE  IF NOT EXISTS `odev`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `isim` varchar(255) NOT NULL,
    `aciklama` text NOT NULL,
    `olusturma_tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `son_tarih` datetime NOT NULL,
    `olusturan_id` int(11) NOT NULL,
    `ders_id` int(11) NOT NULL DEFAULT 0,
    `dosya_gonderme` tinyint NOT NULL DEFAULT 0,
    `dosya_id` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_duyuru_olusturan_id` (`olusturan_id`),
    KEY `idx_bildirim_ders_id` (`ders_id`)
)CHARACTER SET latin5 COLLATE latin5_turkish_ci;

CREATE TABLE  IF NOT EXISTS `dokuman`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `isim` varchar(255) NOT NULL,
    `aciklama` text NOT NULL,
    `olusturma_tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `olusturan_id` int(11) NOT NULL,
    `ders_id` int(11) NOT NULL DEFAULT 0,
    `dosya_id` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_duyuru_olusturan_id` (`olusturan_id`),
    KEY `idx_bildirim_ders_id` (`ders_id`)
)CHARACTER SET latin5 COLLATE latin5_turkish_ci;


CREATE TABLE  IF NOT EXISTS `dosya`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `kod` VARCHAR(60) NOT NULL,
    `isim` varchar(255) NOT NULL,
    `olusturma_tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `yukleyen_id` int(11) NOT NULL DEFAULT 0,
    `dosya_adi` tinyint NOT NULL DEFAULT 0,
    `indirme_link` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_bildirim_ders_id` (`yukleyen_id`)
)CHARACTER SET latin5 COLLATE latin5_turkish_ci;

