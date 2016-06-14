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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO egitim (id, baslamatarihi, bitirmetarihi, okulduzeyi, bolum, aciklama) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['baslamatarihi'], "date"),
                       GetSQLValueString($_POST['bitirmetarihi'], "date"),
                       GetSQLValueString($_POST['okulduzeyi'], "text"),
                       GetSQLValueString($_POST['bolum'], "text"),
                       GetSQLValueString($_POST['aciklama'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "egitim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_egitimekle = "SELECT * FROM egitim";
$egitimekle = mysql_query($query_egitimekle, $baglan) or die(mysql_error());
$row_egitimekle = mysql_fetch_assoc($egitimekle);
$totalRows_egitimekle = mysql_num_rows($egitimekle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/panel.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Yönetim Paneli</title>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
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
<!-- InstanceBeginEditable name="EditRegion1" -->
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Başlama Tarihi</div>
          <div class="panel-body">
                <input type="date" name="baslamatarihi" value=""/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Bitirme Tarihi</div>
          <div class="panel-body">
                <input type="date" name="bitirmetarihi" value=""/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Eğitim Türü</div>
          <div class="panel-body">
                <input type="text" name="okulduzeyi" value=""/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">Eğitim Bölümü/Eylem</div>
          <div class="panel-body">
                <input type="text" name="bolum" value=""/>
          </div>
        </div>
	</div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Bu Eğitiminiz Hakkında Açıklama Yazın</div>
          <div class="panel-body">
                        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap"><input type="submit" value="Kayıt Ekle" /></td>
          </tr>
        <tr valign="baseline">
          <td colspan="2" align="center" nowrap="nowrap"><textarea class="ckeditor" name="aciklama" cols="32"></textarea></td>
        </tr>
          </div>
        </div>
	</div>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <!-- InstanceEndEditable -->

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
mysql_free_result($egitimekle);
?>
