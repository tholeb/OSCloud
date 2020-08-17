<?php
include('./config.php');
if (isset($_GET['dir'])) {
  $dir = rtrim($_GET['dir'], '/') . '/';
} else {
  $dir = '';
}
$url = (empty($_SERVER['HTTPS'])?'http://':'https://').(empty( $_SERVER['HTTP_HOST'])?$defaultHost:$_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'].'?';
function pretty_filesize($file,$dir) {
  $size=filesize("./files/".$dir.$file);
  if($size<1024){$size=$size." Bytes";}
  elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
  elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
  else{$size=round($size/1073741824, 1)." GB";}
  return $size;
}
function addOrUpdateUrlParam($name, $value) {
  $params = $_GET;
  unset($params[$name]);
  $params[$name] = $value;
  return basename($_SERVER['PHP_SELF']).'?'.http_build_query($params);
}
if(isset($_GET['hidden']) && $_GET['hidden'] == 1) {
  $hide="";
  $ahref = addOrUpdateUrlParam('hidden',0);
  $atext=bsicons('eyeslashfill','1.5rem','1.5rem')." Hide";
}
else {
  $hide=".";
  $ahref = addOrUpdateUrlParam('hidden',1);
  $atext=bsicons('eyefill','1.5rem','1.5rem')." Show";
}
$basepath = $aURL.'files/';
$realBase = realpath($basepath);
$userpath = $basepath . $dir;
$realUserPath = realpath($userpath);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>OSCloud</title>
    <?php include('assets/resources/meta/head.php') ?>
  </head>
  <body class="c-app">
    <?php include('assets/resources/elements/nav/sidenav.php') ?>
    <div class="c-wrapper c-fixed-components">
      <?php include('assets/resources/elements/nav/header.php') ?>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="fade-in">
                <?php
                  if ($realUserPath === false || strpos($realUserPath, $realBase) !== 0) {
                    die('Sorry, but this is not allowed');
                  } else {
                    if (!file_exists("./files/".$dir)) {
                      if (!mkdir($aURL."files/".$dir, 0777,true)) {
                        die('Failure during directory creation...');
                      } else {
                        $oldmask = umask(0);
                        mkdir($aURL."files/".$dir, 0777);
                        chmod($aURL."files/".$dir, 0777);
                        umask($oldmask);
                      }
                      echo "<script>window.location.reload()</script>";
                    } else {
                      $myDirectory=opendir($userpath);
                      while($entryName=readdir($myDirectory)) {
                        $dirArray[]=$entryName;
                      }
                      closedir($myDirectory);
                      $indexCount=count($dirArray);
                      sort($dirArray);
                      ?>
                      <h2 class="display-3"><?= bsicons('folder','0.7em','0.7em') ?> Folders</h2>
                      <div class="row">
                        <?php
                          for($index=0; $index < $indexCount; $index++) {
                            if(substr("$dirArray[$index]", 0, 1) != $hide) {
                              $class="file";
                              $name=$dirArray[$index];
                              $namehref="./files/".$dir.$dirArray[$index];
                              $modtime=date("j/m/Y H:i", filemtime("./files/".$dir.$dirArray[$index]));
                              if(is_dir("./files/".$dir.$dirArray[$index])) {
                                $extn="&lt;Directory&gt;";
                                $size="&lt;Directory&gt;";
                                $sizekey="0";
                                $class="dir";
                                $namehref =  addOrUpdateUrlParam('dir',$dir.$dirArray[$index]);
                                $icon = bsicons('folder','1rem','1rem');
                                $bodyIcon = bsicons('folder','50%','50%');
                                $cardAccent="border-info";
                                if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;";}
                                if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
                              }
                              if ($class !== 'file') {
                                if ($name !== '.. (Parent Directory)' && $name !== '. (Current Directory)') {
                                  ?>
                                  <div class="col-sm-3 col-md-2">
                                    <div class="card <?= $cardAccent ?>">
                                      <div class="card-header">
                                        <div class="card-title">
                                          <a href="<?= $namehref ?>"><?= $icon ?> <?= $name ?></a>
                                        </div>
                                        <div class="card-header-actions">
                                          <a class="card-header-action btn-setting" href="#"><svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-settings"></use></svg></a>
                                          <!-- <a class="card-header-action btn-minimize" href="#"><svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-arrow-circle-top"></use></svg></a> -->
                                          <a class="card-header-action btn-close" href="#"><svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-x-circle"></use></svg></a>
                                        </div>
                                      </div>
                                      <!-- <div class="card-body">
                                        <p style="text-align:center"><?= $bodyIcon ?></p>
                                        <a href="<?= $namehref ?>"><?= $class ?> - <?= $extn ?> - <?= $size ?> - <?= $modtime ?></a>
                                      </div> -->
                                    </div>
                                  </div>
                                  <?php
                                }
                              }
                            }
                          }
                        ?>
                      </div>
                      <hr>
                      <h2 class="display-3"><?= bsicons('textfill','0.7em','0.7em') ?> Files</h2>
                      <div class="row">
                        <?php
                          for($index=0; $index < $indexCount; $index++) {
                            if(substr("$dirArray[$index]", 0, 1) != $hide) {
                              $class="file";
                              $name=$dirArray[$index];
                              $namehref="./files/".$dir.$dirArray[$index];
                              $modtime=date("j/m/Y H:i", filemtime("./files/".$dir.$dirArray[$index]));
                              if(is_dir("./files/".$dir.$dirArray[$index])) {
                                $class="dir";
                              } else {
                                $extn=pathinfo($dirArray[$index], PATHINFO_EXTENSION);
                                switch ($extn) {
                                  case "png": $extn="PNG Image";$icon=bsicons('image','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="max-width:80%;max-height:80%;">';$cardAccent="card-accent-success"; break;
                                  case "jpg": $extn="JPG Image";$icon=bsicons('imagefill','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="max-width:90%;max-height:80%;border:black solid 1px;">';$cardAccent="card-accent-success"; break;
                                  case "jpeg": $extn="JPEG Image";$icon=bsicons('imagefill','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="max-width:90%;max-height:80%;border:black solid 1px;">';$cardAccent="card-accent-success"; break;
                                  case "svg": $extn="SVG Image";$icon=bsicons('imagealt','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="max-width:80%;max-height:80%;border:black solid 1px;">';$cardAccent="card-accent-success"; break;
                                  case "gif": $extn="GIF Image";$icon=bsicons('images','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="max-width:80%;max-height:80%;border:black solid 1px;">';$cardAccent="card-accent-success"; break;
                                  case "ico": $extn="Windows Icon";$icon=bsicons('image','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="max-width:80%;max-height:80%;border:black solid 1px;">';$cardAccent="card-accent-success"; break;

                                  case "txt": $extn="Text File";$icon=bsicons('textfill','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "log": $extn="Log File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "htm": $extn="HTML File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "html": $extn="HTML File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "xhtml": $extn="HTML File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "shtml": $extn="HTML File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "php": $extn="PHP Script";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "js": $extn="Javascript File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "css": $extn="Stylesheet";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;

                                  case "pdf": $extn="PDF Document";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "xls": $extn="Spreadsheet";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "xlsx": $extn="Spreadsheet";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "doc": $extn="Microsoft Word Document";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "docx": $extn="Microsoft Word Document";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;

                                  case "zip": $extn="ZIP Archive";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "htaccess": $extn="Apache Config File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;
                                  case "exe": $extn="Windows Executable";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary"; break;

                                  default: if($extn!=""){$extn=strtoupper($extn)." File";$bodyIcon=bsicons('textfill','50%','50%');$cardAccent="card-accent-secondary";} else{$extn="Unknown";} break;
                                }
                                $size=pretty_filesize($dirArray[$index],$dir);
                                $sizekey=filesize("./files/".$dir.$dirArray[$index]);
                              }
                              if ($class !== 'dir') {
                                ?>
                                <div class="col-sm-3 col-md-2">
                                  <div class="card <?= $cardAccent ?>">
                                    <div class="card-header">
                                      <div class="card-title">
                                        <?= $icon ?> <?= $name ?>
                                      </div>
                                      <div class="card-header-actions">
                                        <a class="card-header-action btn-setting" href="#"><svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-settings"></use></svg></a>
                                        <!-- <a class="card-header-action btn-minimize" href="#"><svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-arrow-circle-top"></use></svg></a> -->
                                        <a class="card-header-action btn-close" href="#"><svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-x-circle"></use></svg></a>
                                      </div>
                                    </div>
                                    <div class="card-body">
                                      <p style="text-align:center"><?= $bodyIcon ?></p>
                                      <a href="<?= $namehref ?>"><?= $class ?> - <?= $extn ?> - <?= $size ?> - <?= $modtime ?></a>
                                    </div>
                                  </div>
                                </div>
                                <?php
                              }
                            }
                          }
                        ?>
                      </div>
                      <?php
                    }
                  }
              	?>
              <h2 style="text-align:center"><?php echo("<a href='$ahref'>$atext hidden files</a>"); ?></h2>
            </div>
          </div>
        </main>
        <?php include('assets/resources/elements/nav/footer.php') ?>
      </div>
    </div>
    <?php include('assets/resources/meta/scripts.php') ?>
  </body>
</html>
