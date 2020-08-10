<!--
THIS WEBSITE IS PART OF VLIFE PROJECT
ALL RIGHTS ARE RESERVED TO THOMAS 'tholeb' LEBRETON - ANY COPY OR DISTRIBUTION OF THIS WEBSITE IS STRICTLY PROHIBITED
 -->
<?php
header('Content-Type: text/html; charset=utf-8');
$fileName = basename(__FILE__);
$aURL = __DIR__."/";
$protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
if ($_SERVER['HTTP_HOST'] == 'vlife.fr' || $_SERVER['HTTP_HOST'] == 'vlife.tholeb.fr') {
  $wURL = "$protocol://$_SERVER[HTTP_HOST]/";
} else {
  $wURL = "$protocol://$_SERVER[HTTP_HOST]/".basename(__DIR__)."/";
}
require $aURL.'assets/resources/config/database.php';
require $aURL.'assets/resources/config/links.php';
?>
