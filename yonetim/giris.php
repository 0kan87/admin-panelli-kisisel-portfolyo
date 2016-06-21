<?php require_once('../Connections/baglan.php');
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
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport"/>
<title>Yönetici Giriş Paneli</title>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/kullanici-girisi.css">
</head>
<body>
  <div class = "container">
      <form action="<?php echo $loginFormAction; ?>" method="post" name="form1" id="form1" class="form-signin">
          <center><h3 class="form-signin-heading">YÖNETİM PANELİ</h3></center>
         
          <input type="text" name="kullaniciadi" id="kullaniciadi5" class="form-control" placeholder="Kulalnıcı Adı" required="" autofocus="" />
          <br/>
          <input type="password" name="sifre" id="sifre" class="form-control" placeholder="Şifre" required=""/>          
   
          <button class="btn btn-lg btn-primary btn-block"  name="girisyap" id="girisyap" value="Login" type="Submit">Giriş Yap</button>        
      </form>     
  </div>
  <script src="//getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>