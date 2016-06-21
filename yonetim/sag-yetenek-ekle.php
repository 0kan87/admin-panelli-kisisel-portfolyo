<?php require_once('../Connections/baglan.php');
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sag_yetenekler (id, yetenekler, nekadariyisin, renkkodu) VALUES (%s, %s, %s, %s)",
  GetSQLValueString($_POST['id'], "int"),
  GetSQLValueString($_POST['yetenekler'], "text"),
  GetSQLValueString($_POST['nekadariyisin'], "text"),
  GetSQLValueString($_POST['renkkodu'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "yetenekler.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_sagyetenek = "SELECT * FROM sag_yetenekler";
$sagyetenek = mysql_query($query_sagyetenek, $baglan) or die(mysql_error());
$row_sagyetenek = mysql_fetch_assoc($sagyetenek);
$totalRows_sagyetenek = mysql_num_rows($sagyetenek);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Yönetim Paneli</title>
<link href="dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="//code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="dist/js/bootstrap-colorpicker.js"></script>
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

  <div class="container">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <div class="col-xs-12 col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div  class= "input-group" > 
          <span  class= "input-group-addon"  id= "basic-addon1" > Yetenek Adı: </span> 
          <input  type= "text" name="yetenekler" class= "form-control"  aria-describedby= "basic-addon1" > 
        </div> 
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div  class= "input-group" >
          <span  class= "input-group-addon"  id= "basic-addon1" > Nekadar İyisin (0-100 arası): </span>
          <input  type= "text" name="nekadariyisin" class= "form-control"  aria-describedby= "basic-addon1" >
        </div> 
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div  class= "input-group" >
          <span  class= "input-group-addon"  id= "basic-addon1" > Yetenek Rengi: </span>
          <div id="cp2" class="input-group colorpicker-component">
          <input  type= "text" name="renkkodu" class= "form-control"  aria-describedby= "basic-addon1" >
        <span class="input-group-addon"><i></i></span>
        </div>
        <script>
            $(function() {
                $('#cp2').colorpicker();
            });
        </script>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
      <input type="submit" class="btn btn-info" value="Yetenek Ekle" />
      <input type="hidden" name="MM_insert" value="form1" />
    </div>
    </form>
  </div>

  </br>
  <div class="container">
    <div class="alert alert-warning alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      Oluşabilecek her hata için lütfen <a href="http://okandiyebiri.com/admin-panelli-kisisel-site-scripti/"><strong>destek</strong></a> sitesini ziyaret edin.
    </div>
  </div></br></br>

  <div class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
      <p class="navbar-text pull-left">Okan IŞIK</p>
      <a href="//okandiyebiri.com" class="navbar-btn btn-info btn pull-right">okandiyebiri.com</a>
    </div>
  </div>
  <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>  
</body>
</html>
<?php
mysql_free_result($sagyetenek);
?>