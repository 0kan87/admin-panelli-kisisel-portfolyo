<?php require_once('../Connections/baglan.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "giris.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  $isValid = False; 

  if (!empty($UserName)) { 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "giris.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ayarlar SET adsoyadi=%s, kullaniciadi=%s, emailadresi=%s, telefonnumarasi=%s, siteadresi=%s, sifre=%s, ilksayfabaslik=%s, ilksayfaicerik=%s, sitebaslik=%s, siteaciklama=%s, anahtarkelime=%s WHERE id=%s",
     GetSQLValueString($_POST['adsoyadi'], "text"),
     GetSQLValueString($_POST['kullaniciadi'], "text"),
     GetSQLValueString($_POST['emailadresi'], "text"),
     GetSQLValueString($_POST['telefonnumarasi'], "text"),
     GetSQLValueString($_POST['siteadresi'], "text"),
     GetSQLValueString($_POST['sifre'], "text"),
     GetSQLValueString($_POST['ilksayfabaslik'], "text"),
     GetSQLValueString($_POST['ilksayfaicerik'], "text"),
     GetSQLValueString($_POST['sitebaslik'], "text"),
     GetSQLValueString($_POST['siteaciklama'], "text"),
     GetSQLValueString($_POST['anahtarkelime'], "text"),
     GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($updateSQL, $baglan) or die(mysql_error());

  $updateGoTo = "islembasarili.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_ayarlar = "SELECT * FROM ayarlar";
$ayarlar = mysql_query($query_ayarlar, $baglan) or die(mysql_error());
$row_ayarlar = mysql_fetch_assoc($ayarlar);
$totalRows_ayarlar = mysql_num_rows($ayarlar);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Yönetim Paneli</title>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<style type="text/css">
#form1 table {
	text-align: left;
}
#form1 table {
	text-align: left;
}
</style>
<link href="../SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
	<div class="navbar navbar-inverse navbar-static-top">
		<div class="container">
			<a href="#" class="navbar-brand">Admin Paneli</a>
			
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

  <div class="container">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
	<div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Adı Soyadı</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="adsoyadi" value="<?php echo htmlentities($row_ayarlar['adsoyadi'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Kullanıcı Adı</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="kullaniciadi" value="<?php echo htmlentities($row_ayarlar['kullaniciadi'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
	</div>

  <div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Eposta Adresi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="emailadresi" value="<?php echo htmlentities($row_ayarlar['emailadresi'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
	</div>

	<div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Telefon Numaranız</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="telefonnumarasi" value="<?php echo htmlentities($row_ayarlar['telefonnumarasi'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
	</div>

  <div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Kullanıcı Şifresi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="sifre" value="<?php echo htmlentities($row_ayarlar['sifre'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
	</div>

  <div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Varsa Başka Site Adresiniz</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="url" class="form-control" name="siteadresi" value="<?php echo htmlentities($row_ayarlar['siteadresi'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Site Başlığı(70 Karakter İdealdir.)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="sitebaslik" value="<?php echo htmlentities($row_ayarlar['sitebaslik'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>  
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12">
    <table class="table table-bordered table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th colspan="2">Açılış Sayfası Yazısı</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Konu Başlığı</td>
          <td><input type="text" class="form-control" name="ilksayfabaslik" value="<?php echo htmlentities($row_ayarlar['ilksayfabaslik'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
        <tr>
          <td colspan="2"><textarea class="ckeditor" name="ilksayfaicerik"><?php echo htmlentities($row_ayarlar['ilksayfaicerik'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
      </tbody>
    </table>  
	</div>

  <div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Site Açıklaması</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="url" class="form-control" name="siteaciklama" value="<?php echo htmlentities($row_ayarlar['siteaciklama'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-4">
    <table class="table table-bordered table-hover table-responsive">
      <thead bgcolor="#46b8da" style="color:white;">
        <tr>
          <th>Anahtar Kelimeleriniz</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="anahtarkelime" value="<?php echo htmlentities($row_ayarlar['anahtarkelime'], ENT_COMPAT, 'utf-8'); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="col-xs-12 col-sm-4 col-md-4">
    <input type="submit" class="form-control btn btn-warning" value="Tüm Herşeyi Kaydet" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="id" value="<?php echo $row_ayarlar['id']; ?>" />
	</div>
  </form>
</div>
</br>
<div class="container">
  <div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    Oluşabilecek her hata için lütfen <a href="http://okandiyebiri.com/admin-panelli-kisisel-site-scripti/"><strong>destek</strong></a> sitesini ziyaret edin.
    </div>
    </br></br>
  </div>
    <div class="navbar navbar-default navbar-fixed-bottom">
		<div class="container">
			<p class="navbar-text pull-left">Okan IŞIK</p>
			<a href="http://okandiyebiri.com" class="navbar-btn btn-info btn pull-right">okandiyebiri.com</a>
		</div>
	</div>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php
mysql_free_result($ayarlar);
?>
