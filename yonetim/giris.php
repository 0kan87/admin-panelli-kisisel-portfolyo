<?php require_once('../Connections/baglan.php'); ?>
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
?>
<?php
  
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  ob_start();
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['kullaniciadi'])) {
  $loginUsername=$_POST['kullaniciadi'];
  $password=$_POST['sifre'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "basarisiz-giris.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_baglan, $baglan);
  
  $LoginRS__query=sprintf("SELECT kullaniciadi, sifre FROM ayarlar WHERE kullaniciadi=%s AND sifre=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $baglan) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Yönetici Giriş Paneli</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<div class="container">
<div class="col-xs-12 col-sm-4 col-sm-offset-5 col-md-4 col-md-offset-5">
  <!-- Default panel contents -->
  <div class="panel-heading">Yönetici Girişi</div>

  <!-- Table -->
  <table class="table">
    <tr>
      <td width="142" align="right">Kullanıcı Adı:</td>
      <td width="144"><label for="kullaniciadi5"></label>
      <input type="text" name="kullaniciadi" id="kullaniciadi5" /></td>
    </tr>
    <tr>
      <td align="right">Şifre:</td>
      <td><label for="sifre"></label>
      <input type="password" name="sifre" id="sifre" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="girisyap" id="girisyap" value="Giriş Yap" /></td>
    </tr>
  </table>
</div>
</div>
</form>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>