<?php include('./config.php') ?>
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
              <div class="row">
                <?php
                  if (isset($_GET['dir'])) {
                    $dir = rtrim($_GET['dir'], '/') . '/';
                  } else {
                    $dir = '';
                  }
                	function pretty_filesize($file,$dir) {
                		$size=filesize("./files/".$dir.$file);
                		if($size<1024){$size=$size." Bytes";}
                		elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
                		elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
                		else{$size=round($size/1073741824, 1)." GB";}
                		return $size;
                	}
                  function folder_exist($folder) {
                    $path = realpath($folder);
                    if($path !== false AND is_dir($path))
                    {
                      return $path;
                    }
                    return false;
                  }
                	if($_SERVER['QUERY_STRING']=="hidden") {
                    $hide="";
                	  $ahref="./";
                	  $atext="Hide";}
                	else {
                    $hide=".";
                	  $ahref="./?hidden";
                	  $atext="Show";
                  }
                  if (!file_exists("./files/".$dir)) {
                    if (!mkdir("./files/".$dir, 0777, true)) {
                      die('Echec lors de la création des répertoires...');
                    } else {
                      $oldmask = umask(0);
                      mkdir("./files/".$dir, 0777);
                      chmod("./files/".$dir, 0777);
                      umask($oldmask);
                    }
                    echo "<script>window.location.reload()</script>";
                  } else {
                    $myDirectory=opendir("./files/".$dir);
                    while($entryName=readdir($myDirectory)) {
                  	   $dirArray[]=$entryName;
                  	}
                  	closedir($myDirectory);
                  	$indexCount=count($dirArray);
                  	sort($dirArray);
                  	for($index=0; $index < $indexCount; $index++) {
                  	   if(substr("$dirArray[$index]", 0, 1)!=$hide) {
                    		$favicon="";
                    		$class="file";
                    		$name=$dirArray[$index];
                    		$namehref="./files/".$dir.$dirArray[$index];
                    		$modtime=date("M j Y g:i A", filemtime("./files/".$dir.$dirArray[$index]));
                    		$timekey=date("YmdHis", filemtime("./files/".$dir.$dirArray[$index]));
                    		if(is_dir("./files/".$dirArray[$index])) {
                  				$extn="&lt;Directory&gt;";
                  				$size="&lt;Directory&gt;";
                  				$sizekey="0";
                  				$class="dir";
                          $namehref = "?dir=".$dirArray[$index];
                          $icon = bsicons('folder','1rem','1rem');
                          $bodyIcon = bsicons('folder','100%','100%');
                  				if(file_exists("$namehref/favicon.ico")) {
                						$favicon=" style='background-image:url($namehref/favicon.ico);'";
                						$extn="&lt;Website&gt;";
                					}
                  				if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;"; $favicon=" style='background-image:url($namehref/.favicon.ico);'";}
                  				if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
                    		} else {
                    			$extn=pathinfo($dirArray[$index], PATHINFO_EXTENSION);
                    			switch ($extn) {
                    				case "png": $extn="PNG Image";$icon=bsicons('image','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="width:100%;height:100%:">'; break;
                    				case "jpg": $extn="JPG Image";$icon=bsicons('imagefill','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="width:100%;height:100%:">'; break;
                    				case "jpeg": $extn="JPEG Image";$icon=bsicons('imagefill','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="width:100%;height:100%:">'; break;
                    				case "svg": $extn="SVG Image";$icon=bsicons('imagealt','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="width:100%;height:100%:">'; break;
                    				case "gif": $extn="GIF Image";$icon=bsicons('images','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="width:100%;height:100%:">'; break;
                    				case "ico": $extn="Windows Icon";$icon=bsicons('image','1rem','1rem');$bodyIcon='<img src="'.$namehref.'" alt="'.$extn.'" style="width:100%;height:100%:">'; break;

                    				case "txt": $extn="Text File";$icon=bsicons('folder','1rem','1rem');$bodyIcon=bsicons('folder','50%','50%'); break;
                    				case "log": $extn="Log File";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "htm": $extn="HTML File";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "html": $extn="HTML File";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "xhtml": $extn="HTML File";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "shtml": $extn="HTML File";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "php": $extn="PHP Script";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "js": $extn="Javascript File";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "css": $extn="Stylesheet";$icon=bsicons('folder','1rem','1rem'); break;

                    				case "pdf": $extn="PDF Document";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "xls": $extn="Spreadsheet";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "xlsx": $extn="Spreadsheet";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "doc": $extn="Microsoft Word Document";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "docx": $extn="Microsoft Word Document";$icon=bsicons('folder','1rem','1rem'); break;

                    				case "zip": $extn="ZIP Archive";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "htaccess": $extn="Apache Config File";$icon=bsicons('folder','1rem','1rem'); break;
                    				case "exe": $extn="Windows Executable";$icon=bsicons('folder','1rem','1rem'); break;

                    				default: if($extn!=""){$extn=strtoupper($extn)." File";} else{$extn="Unknown";} break;
                    			}
                  				$size=pretty_filesize($dirArray[$index],$dir);
                  				$sizekey=filesize("./files/".$dir.$dirArray[$index]);
                    		}
                        ?>
                          <div class="col-sm-3 col-md-2">
                            <div class="card card-accent-info">
                              <div class="card-header">
                                <?= $icon ?>
                                <?= $name ?>
                                <div class="card-header-actions"><a class="card-header-action btn-setting" href="#">
                                <svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-settings"></use></svg></a><a class="card-header-action btn-minimize" href="#">
                                <!-- <svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-arrow-circle-top"></use></svg></a><a class="card-header-action btn-close" href="#"> -->
                                <svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-x-circle"></use></svg></a></div>
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
            </div>
          </div>
        </main>
        <?php include('assets/resources/elements/nav/footer.php') ?>
      </div>
    </div>
    <?php include('assets/resources/meta/scripts.php') ?>
  </body>
</html>
