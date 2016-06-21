<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Yönetim Paneli</title>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
	<div class="navbar navbar-inverse navbar-static-top">
		<div class="container">
			<a href="./" class="navbar-brand">Admin Paneli</a>
			
			<button class="navbar-toggle" data-toggle="collapse" data-target=".navbarSec">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="collapse navbar-collapse navbarSec">
				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a href="index.php">Anasayfa</a></li>
		            <li><a href="ayarlar.php">Ayarlar</a></li>
		            <li><a href="profil-resmi-ekle.php">Profil Resmi</a></li>
		            <li><a href="galeri.php">Resim Galerisi</a></li>
		            <li><a href="deneyimler.php">Deneyimler</a></li>
		            <li><a href="egitim.php">Eğitimler</a></li>
		            <li><a href="sosyal.php">Sosyal Linkler</a></li>
		            <li><a href="yetenekler.php">Yetenekler</a></li>
		            <li><a href="../index.php">Site Anasayfa</a></li>
					<li><a href="<?php echo $logoutAction ?>">Çıkış</a></li>
				</ul>
			</div>
		</div>
	</div>