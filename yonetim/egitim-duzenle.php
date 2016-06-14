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
  $updateSQL = sprintf("UPDATE egitim SET baslamatarihi=%s, bitirmetarihi=%s, okulduzeyi=%s, bolum=%s, aciklama=%s WHERE id=%s",
 GetSQLValueString($_POST['baslamatarihi'], "date"),
 GetSQLValueString($_POST['bitirmetarihi'], "date"),
 GetSQLValueString($_POST['okulduzeyi'], "text"),
 GetSQLValueString($_POST['bolum'], "text"),
 GetSQLValueString($_POST['aciklama'], "text"),
 GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($updateSQL, $baglan) or die(mysql_error());

  $updateGoTo = "egitim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_egitimduzenle = "-1";
if (isset($_GET['id'])) {
  $colname_egitimduzenle = $_GET['id'];
}
mysql_select_db($database_baglan, $baglan);
$query_egitimduzenle = sprintf("SELECT * FROM egitim WHERE id = %s", GetSQLValueString($colname_egitimduzenle, "int"));
$egitimduzenle = mysql_query($query_egitimduzenle, $baglan) or die(mysql_error());
$row_egitimduzenle = mysql_fetch_assoc($egitimduzenle);
$totalRows_egitimduzenle = mysql_num_rows($egitimduzenle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Yönetim Paneli</title>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
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
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Başlama Tarihi</div>
          <div class="panel-body">
                <input type="date" name="baslamatarihi" value="<?php echo htmlentities($row_egitimduzenle['baslamatarihi'], ENT_COMPAT, 'utf-8'); ?>"/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Bitirme Tarihi</div>
          <div class="panel-body">
                <input type="date" name="bitirmetarihi" value="<?php echo htmlentities($row_egitimduzenle['bitirmetarihi'], ENT_COMPAT, 'utf-8'); ?>"/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Eğitim Türü</div>
          <div class="panel-body">
                <input type="text" name="okulduzeyi" value="<?php echo htmlentities($row_egitimduzenle['okulduzeyi'], ENT_COMPAT, 'utf-8'); ?>"/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Eğitim Bölümü/Eylem</div>
          <div class="panel-body">
                <input type="text" name="bolum" value="<?php echo htmlentities($row_egitimduzenle['bolum'], ENT_COMPAT, 'utf-8'); ?>"/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Bu Eğitiminiz Hakkında Açıklama Yazın</div>
          <div class="panel-body">
        <tr valign="baseline">
          <td colspan="2" align="center" nowrap="nowrap"><textarea class="ckeditor" name="aciklama" cols="32"><?php echo htmlentities($row_egitimduzenle['aciklama'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
        </br><input type="submit" class="btn btn-info pull-right" value="Eğitimi Güncelle" />
          </div>
        </div>
	</div>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="id" value="<?php echo $row_egitimduzenle['id']; ?>" />
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
</html>
<?php
mysql_free_result($egitimduzenle);
?>
