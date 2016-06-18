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
include "ust.php";
?>
    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Script Hakkında</h3>
            </div>
            <div class="panel-body">
              <p>Yeni versiyona; harita ekleme, karekod ekleme, yorum ekleme özellikleri eklenecektir.</p>
              <p>  Kişisel Script V2.2 05,03.2015</p>
              <p>  -Resim galerisi eklendi,</p>
              <p>  -Veritabanına ekleme yapıldığı için sql dosyası yeniden import edilmeli</p>
              <p> Kişisel Script V2 26.02.2015</p>
              <p>  -Sıfırdan panel yazıldı,</p>
              <p>  -Güvenlik açıkları kapatıldı,</p>
              <p> -Panel HTML 5 ile kodlandı,</p>
              <p>  -Daha çok alana müdahale imkanı getirildi.</p>
              <p>  -Beceri-Yetenek ekleme, renk ayarları</p>
              <p>  -Sosyal linkler ekleme, renk atama</p>
              <p>  Kişisel Script V1 11.02.2015</p>
              <p>  -Hazırda bulunan basit bir panel giydirildi.</p>
              <p>  V3 de görüşmek üzere 🙂</p>
              <p>  Merhaba arkadaşlar daha önce sizlere kişisel site scripti paylaşmıştım. Scripti portfolyo olarak bende kullanıyorum. Önizlemesini şuradan görebilirsiniz. Biraz boş vakit bulunca bu scripte basit ama kullanışlı bir panel yazmak istedim. Scripti sizin yorumlarınız ve düşünceleriniz doğrultusunda güncelleyeceğim.</p>
              <p> Veri tabanı bağlantısı için Connections klasöründe bulunan baglan.php dosyasını kendi bilgilerinize göre düzenleyin.</p>
              <p> Yönetim panelinize siteadi.com/yonetim şeklinde giriyorsunuz. Kullanıcı adı: admin Şifre:12345</p>
              <p> sql dosyası indirdiğiniz dosyanın içinde mevcuttur. Veri tabanına sql dosyasını okutmadan çalışmaz. Videoda nasıl yapacağınızı anlattım. Kolay bir kurulum için videoyu mutlaka izleyiniz.</p>
            </div>
          </div>
        </div>
    </div>
<?php include "alt.php"; ?>