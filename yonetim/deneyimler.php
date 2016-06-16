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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_deneyimler = 10;
$pageNum_deneyimler = 0;
if (isset($_GET['pageNum_deneyimler'])) {
  $pageNum_deneyimler = $_GET['pageNum_deneyimler'];
}
$startRow_deneyimler = $pageNum_deneyimler * $maxRows_deneyimler;

mysql_select_db($database_baglan, $baglan);
$query_deneyimler = "SELECT * FROM deneyimler ORDER BY id DESC";
$query_limit_deneyimler = sprintf("%s LIMIT %d, %d", $query_deneyimler, $startRow_deneyimler, $maxRows_deneyimler);
$deneyimler = mysql_query($query_limit_deneyimler, $baglan) or die(mysql_error());
$row_deneyimler = mysql_fetch_assoc($deneyimler);

if (isset($_GET['totalRows_deneyimler'])) {
  $totalRows_deneyimler = $_GET['totalRows_deneyimler'];
} else {
  $all_deneyimler = mysql_query($query_deneyimler);
  $totalRows_deneyimler = mysql_num_rows($all_deneyimler);
}
$totalPages_deneyimler = ceil($totalRows_deneyimler/$maxRows_deneyimler)-1;

$queryString_deneyimler = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_deneyimler") == false && 
        stristr($param, "totalRows_deneyimler") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_deneyimler = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_deneyimler = sprintf("&totalRows_deneyimler=%d%s", $totalRows_deneyimler, $queryString_deneyimler);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Yönetim Paneli</title>
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
    <a href="deneyim-ekle.php" class="navbar-btn btn-info btn pull-right">Deneyim Ekle</a>
    <table class="table table-bordered table-hover table-responsive">
      <tr>
        <thead bgcolor="#46b8da" style="color:white;">
          <th>Başlama Tarihi</th>
          <th>Ayrılma Tarihi</th>
          <th>Çalıştığın Yer</th>
          <th colspan="3">Görevin</th>
        </thead>
      </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_deneyimler['baslamatarihi']; ?></td>
            <td><?php echo $row_deneyimler['ayrilmatarihi']; ?></td>
            <td><?php echo $row_deneyimler['calistigiyer']; ?></td>
            <td width="171"><?php echo $row_deneyimler['gorevi']; ?></td>
            <td width="16"><a href="deneyim-duzenle.php?id=<?php echo $row_deneyimler['id']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
            <td width="16"><a href="deneyim-sil.php?id=<?php echo $row_deneyimler['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
          </tr>
          <?php } while ($row_deneyimler = mysql_fetch_assoc($deneyimler)); ?>
    </table>

    <table border="0">
      <tr>
        <td><?php if ($pageNum_deneyimler > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_deneyimler=%d%s", $currentPage, 0, $queryString_deneyimler); ?>">&#304;lk</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_deneyimler > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_deneyimler=%d%s", $currentPage, max(0, $pageNum_deneyimler - 1), $queryString_deneyimler); ?>">&Ouml;nceki</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_deneyimler < $totalPages_deneyimler) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_deneyimler=%d%s", $currentPage, min($totalPages_deneyimler, $pageNum_deneyimler + 1), $queryString_deneyimler); ?>">Sonraki</a>
            <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_deneyimler < $totalPages_deneyimler) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_deneyimler=%d%s", $currentPage, $totalPages_deneyimler, $queryString_deneyimler); ?>">Son</a>
            <?php } // Show if not last page ?></td>
      </tr>
    </table>
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
mysql_free_result($deneyimler);
?>
