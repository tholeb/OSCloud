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
echo shell_exec('mkdir -p files && chmod -R 775 ./files/');

require $aURL.'assets/resources/config/database.php';
require $aURL.'assets/resources/config/links.php';
require $aURL.'assets/resources/config/icons.php';
?>
