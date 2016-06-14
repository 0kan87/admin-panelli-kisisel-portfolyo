-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 15 Haz 2016, 01:03:00
-- Sunucu sürümü: 5.6.24
-- PHP Sürümü: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Veritabanı: `yonetimpaneli`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ayarlar`
--

CREATE TABLE IF NOT EXISTS `ayarlar` (
  `id` int(11) NOT NULL,
  `adsoyadi` varchar(50) COLLATE utf8_bin NOT NULL,
  `kullaniciadi` varchar(50) COLLATE utf8_bin NOT NULL,
  `emailadresi` varchar(100) COLLATE utf8_bin NOT NULL,
  `telefonnumarasi` varchar(25) COLLATE utf8_bin NOT NULL,
  `siteadresi` varchar(225) COLLATE utf8_bin NOT NULL,
  `sifre` varchar(20) COLLATE utf8_bin NOT NULL,
  `ilksayfabaslik` varchar(250) COLLATE utf8_bin NOT NULL,
  `ilksayfaicerik` text COLLATE utf8_bin NOT NULL,
  `sitebaslik` varchar(200) COLLATE utf8_bin NOT NULL,
  `siteaciklama` varchar(200) COLLATE utf8_bin NOT NULL,
  `anahtarkelime` varchar(230) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `ayarlar`
--

INSERT INTO `ayarlar` (`id`, `adsoyadi`, `kullaniciadi`, `emailadresi`, `telefonnumarasi`, `siteadresi`, `sifre`, `ilksayfabaslik`, `ilksayfaicerik`, `sitebaslik`, `siteaciklama`, `anahtarkelime`) VALUES
(1, 'Okan IŞIK', 'admin', 'okansibut@gmail.com', '+90 546 863 21 60', 'http://okandiyebiri.com', '12345', 'İlk sayfanızın başlığı', '<p><em><strong>Lorem ipsum dolor sit amet, consectetur</strong></em></p>\r\n\r\n<p style="text-align:justify"><img alt="" src="https://avatars1.githubusercontent.com/u/19917975?v=3&amp;s=460" style="float:left; height:230px; margin:10px; width:230px" />Adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\r\n\r\n<p style="text-align:justify">Duis aute irure dolor in <strong>reprehenderit</strong> in voluptate <span style="color:#FFF0F5"><strong><span style="background-color:#4B0082">velit esse cillum</span></strong></span> dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, <span style="color:#008080"><strong>sunt in culpa qui officia</strong></span> deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur <strong><span style="color:#FF0000">adipisicing elit</span></strong>, sed do eiusmod tempor <a href="https://okandiyebiri.com">okan ışık</a> ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis <span style="background-color:#FF8C00">nostrud</span> exercitation.</p>\r\n', 'Okan''ın Sitesi', 'Herşey Çok Güzel Olacak', 'okandiyebiri, okan ışık');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bilgiler`
--

CREATE TABLE IF NOT EXISTS `bilgiler` (
  `id` int(11) NOT NULL,
  `adi_soyadi` varchar(255) COLLATE utf8_bin NOT NULL,
  `telefon` varchar(255) COLLATE utf8_bin NOT NULL,
  `siteadresi` varchar(255) COLLATE utf8_bin NOT NULL,
  `emailadresi` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `bilgiler`
--

INSERT INTO `bilgiler` (`id`, `adi_soyadi`, `telefon`, `siteadresi`, `emailadresi`) VALUES
(30, 'okan Ä±ÅŸÄ±k', '05468632160', 'okandiyebiri.com meselaaa', 'okansibut@gmail.com');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deneyimler`
--

CREATE TABLE IF NOT EXISTS `deneyimler` (
  `id` int(11) NOT NULL,
  `baslamatarihi` date NOT NULL,
  `ayrilmatarihi` date NOT NULL,
  `calistigiyer` varchar(250) COLLATE utf8_bin NOT NULL,
  `gorevi` varchar(250) COLLATE utf8_bin NOT NULL,
  `aciklama` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `deneyimler`
--

INSERT INTO `deneyimler` (`id`, `baslamatarihi`, `ayrilmatarihi`, `calistigiyer`, `gorevi`, `aciklama`) VALUES
(4, '2005-01-01', '2016-01-01', 'OKANDIYEBIRI', 'Webmaster', '<p><span style="background-color:rgb(252, 252, 252); color:rgb(102, 102, 102); font-family:cabin,sans-serif; font-size:16px">Backend, Frontend</span></p>\r\n');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `egitim`
--

CREATE TABLE IF NOT EXISTS `egitim` (
  `id` int(11) NOT NULL,
  `baslamatarihi` date NOT NULL,
  `bitirmetarihi` date NOT NULL,
  `okulduzeyi` varchar(150) COLLATE utf8_bin NOT NULL,
  `bolum` varchar(150) COLLATE utf8_bin NOT NULL,
  `aciklama` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `egitim`
--

INSERT INTO `egitim` (`id`, `baslamatarihi`, `bitirmetarihi`, `okulduzeyi`, `bolum`, `aciklama`) VALUES
(7, '1993-01-01', '2005-01-01', 'Lise', 'Sözel', '<p>Bağcılar Orhan Gazi Lisesi</p>\r\n');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `resim`
--

CREATE TABLE IF NOT EXISTS `resim` (
  `id` int(11) NOT NULL,
  `resim` varchar(255) COLLATE utf8_bin NOT NULL,
  `altbaslik` varchar(250) COLLATE utf8_bin NOT NULL,
  `ustbaslik` varchar(250) COLLATE utf8_bin NOT NULL,
  `aciklama` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `resim`
--

INSERT INTO `resim` (`id`, `resim`, `altbaslik`, `ustbaslik`, `aciklama`) VALUES
(60, 'okandiyebiri1784', 'wdwdwa', 'dadadada', 'portfolio_post filter1 filter3'),
(61, 'okandiyebiri99383', 'sdda', 'asdasda', 'portfolio_post filter2 filter1'),
(62, 'okandiyebiri56406', 'asdasd', 'sada', 'portfolio_post filter3 filter2'),
(63, 'okandiyebiri59726', 'axsaad', 'asdasda', 'portfolio_post filter1 filter3'),
(64, 'okandiyebiri80748', 'dasdasd', 'dadasa', 'portfolio_post filter1 filter3'),
(66, 'okandiyebiri46629', 'gdgdfgd', 'fgdfgdgd', 'portfolio_post filter2 filter1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sag_yetenekler`
--

CREATE TABLE IF NOT EXISTS `sag_yetenekler` (
  `id` int(11) NOT NULL,
  `yetenekler` varchar(250) COLLATE utf8_bin NOT NULL,
  `nekadariyisin` varchar(3) COLLATE utf8_bin NOT NULL,
  `renkkodu` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `sag_yetenekler`
--

INSERT INTO `sag_yetenekler` (`id`, `yetenekler`, `nekadariyisin`, `renkkodu`) VALUES
(5, 'PHP', '65', '#ff0080'),
(6, 'CSS', '70', '#ff8000'),
(7, 'HTML5', '90', '#00ff00'),
(8, 'Java Script', '55', '#ff8080');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sosyal`
--

CREATE TABLE IF NOT EXISTS `sosyal` (
  `id` int(11) NOT NULL,
  `link` varchar(250) COLLATE utf8_bin NOT NULL,
  `iconno` varchar(6) COLLATE utf8_bin NOT NULL,
  `icontur` varchar(1) COLLATE utf8_bin NOT NULL,
  `renk` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `sosyal`
--

INSERT INTO `sosyal` (`id`, `link`, `iconno`, `icontur`, `renk`) VALUES
(6, 'http://facebook.com/kullancamseni', '', '0', '#3b5998'),
(7, 'http://twitter.com', '', '1', '#44ccf6'),
(9, 'http://facebook.com/kullancamseni', '', '6', '#aad450'),
(10, 'http://facebook.com/kullancamseni', '', '7', '#bb0000'),
(11, 'http://facebook.com/kullancamseni', '', '2', '#dd4b39');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yetenekler`
--

CREATE TABLE IF NOT EXISTS `yetenekler` (
  `id` int(11) NOT NULL,
  `yetenekler` varchar(100) COLLATE utf8_bin NOT NULL,
  `nekadariyisin` varchar(4) COLLATE utf8_bin NOT NULL,
  `renkkodu` varchar(200) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `yetenekler`
--

INSERT INTO `yetenekler` (`id`, `yetenekler`, `nekadariyisin`, `renkkodu`) VALUES
(12, 'Word Office', '85', '#008000'),
(13, 'Camtasia', '95', '#ff0080'),
(14, 'Photofiltre', '100', '#d8dc23'),
(15, 'Dreamwaver CS6', '85', '#31ce94'),
(16, 'Photoshop', '90', '#f2cd4d');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `ayarlar`
--
ALTER TABLE `ayarlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `bilgiler`
--
ALTER TABLE `bilgiler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `deneyimler`
--
ALTER TABLE `deneyimler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `egitim`
--
ALTER TABLE `egitim`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `resim`
--
ALTER TABLE `resim`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `sag_yetenekler`
--
ALTER TABLE `sag_yetenekler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `sosyal`
--
ALTER TABLE `sosyal`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yetenekler`
--
ALTER TABLE `yetenekler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `ayarlar`
--
ALTER TABLE `ayarlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Tablo için AUTO_INCREMENT değeri `bilgiler`
--
ALTER TABLE `bilgiler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- Tablo için AUTO_INCREMENT değeri `deneyimler`
--
ALTER TABLE `deneyimler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Tablo için AUTO_INCREMENT değeri `egitim`
--
ALTER TABLE `egitim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- Tablo için AUTO_INCREMENT değeri `resim`
--
ALTER TABLE `resim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
--
-- Tablo için AUTO_INCREMENT değeri `sag_yetenekler`
--
ALTER TABLE `sag_yetenekler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- Tablo için AUTO_INCREMENT değeri `sosyal`
--
ALTER TABLE `sosyal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Tablo için AUTO_INCREMENT değeri `yetenekler`
--
ALTER TABLE `yetenekler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
