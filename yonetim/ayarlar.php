<?php require_once('../Connections/baglan.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
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

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
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
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/panel.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
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
<!-- InstanceEndEditable -->
<link href="../SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
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
        <div class="panel panel-default">
          <div class="panel-heading">Adı Soyadı</div>
          <div class="panel-body">
                  <input type="text" class="form-control" name="adsoyadi" value="<?php echo htmlentities($row_ayarlar['adsoyadi'], ENT_COMPAT, 'utf-8'); ?>" />
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Yönetici Paneli Kullanıcı Adı</div>
          <div class="panel-body">
                <input type="text" class="form-control" name="kullaniciadi" value="<?php echo htmlentities($row_ayarlar['kullaniciadi'], ENT_COMPAT, 'utf-8'); ?>" />
          </div>
        </div>
	</div>
        <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Eposta Adresiniz</div>
          <div class="panel-body">
            <input type="text" class="form-control" name="emailadresi" value="<?php echo htmlentities($row_ayarlar['emailadresi'], ENT_COMPAT, 'utf-8'); ?>" />
          </div>
        </div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Telefon Numaranız</div>
          <div class="panel-body">
                  <input type="text" class="form-control" name="telefonnumarasi" value="<?php echo htmlentities($row_ayarlar['telefonnumarasi'], ENT_COMPAT, 'utf-8'); ?>" />
            </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Kullanıcı Şifresi</div>
          <div class="panel-body">
                <input type="text" class="form-control" name="sifre" value="<?php echo htmlentities($row_ayarlar['sifre'], ENT_COMPAT, 'utf-8'); ?>" />
          </div>
        </div>
	</div>
        <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Diğer Site Adresiniz</div>
          <div class="panel-body">
            <input type="text" class="form-control" name="siteadresi" value="<?php echo htmlentities($row_ayarlar['siteadresi'], ENT_COMPAT, 'utf-8'); ?>" />
          </div>
        </div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Site Başlığı(70 Karakter İdealdir.)</div>
            <div class="panel-body">
              <input type="text" class="form-control" name="sitebaslik" value="<?php echo htmlentities($row_ayarlar['sitebaslik'], ENT_COMPAT, 'utf-8'); ?>" />
            </div>
        </div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Açılış Sayfası Yazısı</div>
              <div class="panel-body">
                 <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Konu Başlığı:</span>
                      <input type="text" class="form-control" name="ilksayfabaslik" value="<?php echo htmlentities($row_ayarlar['ilksayfabaslik'], ENT_COMPAT, 'utf-8'); ?>" />
                  </div>
               </div>
               <div class="panel-body">
                      <textarea class="ckeditor" name="ilksayfaicerik" cols="32"><?php echo htmlentities($row_ayarlar['ilksayfaicerik'], ENT_COMPAT, 'utf-8'); ?></textarea>
           </div>
        </div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Site Açıklaması</div>
          <div class="panel-body">
                  <input type="text" class="form-control" name="siteaciklama" value="<?php echo htmlentities($row_ayarlar['siteaciklama'], ENT_COMPAT, 'utf-8'); ?>" />
            </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Anahtar Kelimeleriniz "," virgül koymayı unutmayın.</div>
          <div class="panel-body">
                <input type="text" class="form-control" name="anahtarkelime" value="<?php echo htmlentities($row_ayarlar['anahtarkelime'], ENT_COMPAT, 'utf-8'); ?>" />
          </div>
        </div>
	</div>
        <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Tüm Ayarları Kaydet</div>
          <div class="panel-body">
            <input type="submit" class="form-control btn btn-info" value="Ayarları Kaydet" />
            <input type="hidden" name="MM_update" value="form1" />
            <input type="hidden" name="id" value="<?php echo $row_ayarlar['id']; ?>" />
          </div>
        </div>
	</div>
</form>

    </div>
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
<!-- InstanceEnd --></html>
<?php
mysql_free_result($ayarlar);
?>
