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
  $insertSQL = sprintf("INSERT INTO sosyal (id, link, icontur, renk) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['link'], "text"),
                       GetSQLValueString($_POST['icontur'], "text"),
                       GetSQLValueString($_POST['renk'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "sosyal.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_sosyalekle = "-1";
if (isset($_GET['id'])) {
  $colname_sosyalekle = $_GET['id'];
}
mysql_select_db($database_baglan, $baglan);
$query_sosyalekle = sprintf("SELECT * FROM sosyal WHERE id = %s", GetSQLValueString($colname_sosyalekle, "int"));
$sosyalekle = mysql_query($query_sosyalekle, $baglan) or die(mysql_error());
$row_sosyalekle = mysql_fetch_assoc($sosyalekle);
$totalRows_sosyalekle = mysql_num_rows($sosyalekle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/panel.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Yönetim Paneli</title>
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
    <div class="row">
    <div class="col-md-4 col-md-offset-4">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Link:</td>
          <td><input type="text" name="link" value="" size="45" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Örnek:</td>
          <td>http://facebook.com/kullancamseni</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Link İconu:</td>
          <td><select name="icontur">
            <option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>>Facebook</option>
            <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>Twitter</option>
            <option value="2" <?php if (!(strcmp(2, ""))) {echo "SELECTED";} ?>>Google+</option>
            <option value="5" <?php if (!(strcmp(5, ""))) {echo "SELECTED";} ?>>Pinterest</option>
            <option value="6" <?php if (!(strcmp(6, ""))) {echo "SELECTED";} ?>>Vimeo</option>
            <option value="7" <?php if (!(strcmp(7, ""))) {echo "SELECTED";} ?>>Youtube</option>
            <option value="l" <?php if (!(strcmp("l", ""))) {echo "SELECTED";} ?>>Skype</option>
            <option value="l" <?php if (!(strcmp("p", ""))) {echo "SELECTED";} ?>>İnstagram</option>
            <option value="4" <?php if (!(strcmp("l", ""))) {echo "SELECTED";} ?>>Linkedin</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Renk:</td>
          <td><input type="color" name="renk" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Link Ekle" /></td>
        </tr>
      </table>
      <table width="100%">
        <tbody>
          <tr>
            <td width="3%" bgcolor="#3B5998">&nbsp;</td>
            <td width="35%"><strong>Facebook Blue</strong><br />
              Hex: #3b5998<br />
              RGB: 59, 89, 152</td>
            <td width="3%" bgcolor="#517FA4">&nbsp;</td>
            <td width="59%"><strong>Instagram Blue</strong><br />
              Hex: #517fa4<br />
              RGB: 81, 127, 164<br /></td>
          </tr>
          <tr>
            <td bgcolor="#00ACED">&nbsp;</td>
            <td><strong>Twitter Blue</strong><br />
              Hex: #00aced<br />
              RGB: 0, 172, 237</td>
            <td bgcolor="#CB2027">&nbsp;</td>
            <td><strong>Pinterest Red</strong><br />
              Hex: #cb2027<br />
              RGB: 203, 32, 39<br /></td>
          </tr>
          <tr>
            <td bgcolor="#DD4B39">&nbsp;</td>
            <td><strong>Google+ Red</strong><br />
              Hex: #dd4b39<br />
              RGB: 221, 75, 57</td>
            <td bgcolor="#AAD450">&nbsp;</td>
            <td><strong>Vimeo Green</strong><br />
              Hex: #aad450<br />
              RGB: 170, 212, 80</td>
          </tr>
          <tr>
            <td bgcolor="#BB0000">&nbsp;</td>
            <td><strong>YouTube Red</strong><br />
              Hex: #bb0000<br />
              RGB: 187, 0, 0</td>
            <td bgcolor="#007BB6">&nbsp;</td>
            <td><strong>Linkedin Blue</strong><br />
              Hex: #007bb6<br />
              RGB: 0, 123, 182</td>
            </tr>
        </tbody>
      </table>
      <p>&nbsp;</p>
      <p>
        <input type="hidden" name="MM_insert" value="form1" />
      </p>
    </form>
    </div>
    </div>
    <p>&nbsp;</p>
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
mysql_free_result($sosyalekle);
?>
