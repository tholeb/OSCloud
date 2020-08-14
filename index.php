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
                	function pretty_filesize($file) {
                		$size=filesize("./files/".$file);
                		if($size<1024){$size=$size." Bytes";}
                		elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
                		elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
                		else{$size=round($size/1073741824, 1)." GB";}
                		return $size;
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
                	$myDirectory=opendir("./files/");
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
                		$namehref="./files/".$dirArray[$index];
                		$modtime=date("M j Y g:i A", filemtime("./files/".$dirArray[$index]));
                		$timekey=date("YmdHis", filemtime("./files/".$dirArray[$index]));
                		if(is_dir($dirArray[$index])) {
              				$extn="&lt;Directory&gt;";
              				$size="&lt;Directory&gt;";
              				$sizekey="0";
              				$class="dir";
              				if(file_exists("$namehref/favicon.ico"))
              					{
              						$favicon=" style='background-image:url($namehref/favicon.ico);'";
              						$extn="&lt;Website&gt;";
              					}
              				if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;"; $favicon=" style='background-image:url($namehref/.favicon.ico);'";}
              				if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
                		}
                		else {
                			$extn=pathinfo($dirArray[$index], PATHINFO_EXTENSION);
                			switch ($extn) {
                				case "png": $extn="PNG Image"; break;
                				case "jpg": $extn="JPEG Image"; break;
                				case "jpeg": $extn="JPEG Image"; break;
                				case "svg": $extn="SVG Image"; break;
                				case "gif": $extn="GIF Image"; break;
                				case "ico": $extn="Windows Icon"; break;

                				case "txt": $extn="Text File"; break;
                				case "log": $extn="Log File"; break;
                				case "htm": $extn="HTML File"; break;
                				case "html": $extn="HTML File"; break;
                				case "xhtml": $extn="HTML File"; break;
                				case "shtml": $extn="HTML File"; break;
                				case "php": $extn="PHP Script"; break;
                				case "js": $extn="Javascript File"; break;
                				case "css": $extn="Stylesheet"; break;

                				case "pdf": $extn="PDF Document"; break;
                				case "xls": $extn="Spreadsheet"; break;
                				case "xlsx": $extn="Spreadsheet"; break;
                				case "doc": $extn="Microsoft Word Document"; break;
                				case "docx": $extn="Microsoft Word Document"; break;

                				case "zip": $extn="ZIP Archive"; break;
                				case "htaccess": $extn="Apache Config File"; break;
                				case "exe": $extn="Windows Executable"; break;

                				default: if($extn!=""){$extn=strtoupper($extn)." File";} else{$extn="Unknown";} break;
                			}
              				$size=pretty_filesize($dirArray[$index]);
              				$sizekey=filesize("./files/".$dirArray[$index]);
                		}
                        ?>
                        <div class="col-sm-3 col-md-2">
                          <div class="card card-accent-info">
                            <div class="card-header"><a target="_blank" href="<?= $namehref ?>"><?= $name ?></a>
                              <div class="card-header-actions"><a class="card-header-action btn-setting" href="#">
                              <svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-settings"></use></svg></a><a class="card-header-action btn-minimize" href="#">
                              <svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-arrow-circle-top"></use></svg></a><a class="card-header-action btn-close" href="#">
                              <svg class="c-icon"><use xlink:href="assets/img/icons/svg/free.svg#cil-x-circle"></use></svg></a></div>
                            </div>
                            <div class="card-body"><a target="_blank" href=""><?= $extn ?> - <?= $size ?> - <?= $modtime ?></a></div>
                          </div>
                        </div>
                        <?php
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
