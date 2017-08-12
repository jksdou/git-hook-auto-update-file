<?php
error_reporting(1);
header("Content-type: text/html; charset=utf-8");
$token = 'lkagkw34o5ukndljg034352dhlero';
if (empty($_GET['token']) || $_GET['token'] !== $token) {
  exit('Error Token');
}
$logfile  = 'log.json';
$data = file_get_contents('php://input'); // print_r(urldecode($data)."\n");
$json = json_decode($data, true);
$github_raw = 'https://raw.githubusercontent.com';
$github_user = 'doudoudzj';
$github_repo = 'random-picture';
$github_branch = 'master';
$github_uri = $github_raw.'/'.$github_user.'/'.$github_repo.'/'.$github_branch;

if ($f = file_put_contents($logfile, time().':'.$data."\n", FILE_APPEND)) {
  echo 'log successed'."\n";
}
if (empty($json['commits'])) {
  echo "<br />\n";
  exit('Error Request');
}

for($i = 0; $i < count($json['commits']); $i++) {
  if (!empty($json['commits'][$i]['modified'])) {
    $modified = $json['commits'][$i]['modified'];
    for($j = 0; $j < count($modified); $j++) {
      if (!empty($modified[$j])) {
        download($github_uri.'/'.$modified[$j].'?t='.time(), '/', $modified[$j])."\n";
        if ($f = file_put_contents($logfile, time().': modified,'.$modified[$j]."\n", FILE_APPEND)) {
          echo time().': log successed, modified, '.$modified[$j]."\n";
        }
      }
    }
  }
  if (!empty($json['commits'][$i]['added'])) {
    $added = $json['commits'][$i]['added'];
    for($j = 0; $j < count($added); $j++) {
      if (!empty($added[$j])) {
        download($github_uri.'/'.$added[$j].'?t='.time(), '/', $added[$j])."\n";
        if ($f = file_put_contents($logfile, time().': added,'.$added[$j]."\n", FILE_APPEND)) {
          echo time().': log successed, added, '.$added[$j]."\n";
        }
      }
    }
  }
  if (!empty($json['commits'][$i]['removed'])) {
    $removed = $json['commits'][$i]['removed'];
    for($j = 0; $j < count($removed); $j++) {
      if (!empty($removed[$j])) {
        if (!unlink($removed[$j])) {
          if ($f = file_put_contents($logfile, time().': removed,'.$removed[$j]."\n", FILE_APPEND)) {
            echo time().': log successed, remove failed, '.$removed[$j]."\n";
          } else {
            echo ("remove log failed\n");
          }
        } else {
          if ($f = file_put_contents($logfile, time().': removed,'.$removed[$j]."\n", FILE_APPEND)) {
            echo time().': log successed, remove successed, '.$removed[$j]."\n";
          } else {
            echo ("remove log failed\n");
          }
        }
      }
    }
  }
  
}

function download($url, $dir, $filename=''){
  if(empty($url)) {
    return false;
  }
  $ext = strrchr($url, '.');
  $dir = realpath($dir);
  $fileUri = $dir . $filename;

  ob_start();
  readfile($url);
  $file = ob_get_contents();
  ob_end_clean();

  $fhandler = fopen($fileUri, "w+");
  fwrite($fhandler, $file);
  rewind($fhandler); // 指针移到最前面
  // echo filesize($fileUri)."\n";
  // if ($fileLenght == filesize($fileUri)) {
  //   fclose($fhandler);
  //   echo 'write successed'."\n";
  // } else {
  //   fwrite($fhandler, $file);
  //   rewind($fhandler); // 指针移到最前面
  //   fclose($fhandler);
  //   echo 'write failed'."\n";
  // }
  fclose($fhandler);
  echo $fileUri."\n";
}
