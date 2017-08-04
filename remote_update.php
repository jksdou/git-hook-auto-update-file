<?php
error_reporting(1);
header("Content-type: text/html; charset=utf-8");
$logfile  = 'log.json';

$data = file_get_contents('php://input'); // print_r(urldecode($data)."\n");
$json = json_decode($data, true);
if ($f = file_put_contents($logfile, $data."\n", FILE_APPEND)) {
  echo 'log wrote successed'."\n";
}
if (empty($json['commits'])) {
  echo "<br />\n";
  exit('Error Request');
}
for($i = 0; $i < count($json['commits']); $i++) {
  if (!empty($json['commits'][$i]['modified'])) {
    for($j = 0; $j < count($json['commits'][$i]['modified']); $j++) {
      if (!empty($json['commits'][$i]['modified'][$j])) {
        echo download(
          "https://raw.githubusercontent.com/doudoudzj/random-picture/master/".$json['commits'][$i]['modified'][$j]."?t=".time(),
          "/",
          $json['commits'][$i]['modified'][$j]);
        if ($f = file_put_contents($logfile, "modified:".$json['commits'][$i]['modified'][$j]."\n", FILE_APPEND)) {
          echo 'log wrote successed'."\n";
        }
      }
    }
  }
  if (!empty($json['commits'][$i]['added'])) {
    for($j = 0; $j < count($json['commits'][$i]['added']); $j++) {
      if (!empty($json['commits'][$i]['added'][$j])) {
        echo download(
          "https://raw.githubusercontent.com/doudoudzj/random-picture/master/".$json['commits'][$i]['added'][$j]."?t=".time(),
          "/",
          $json['commits'][$i]['added'][$j]);
        if ($f = file_put_contents($logfile, "added:".$json['commits'][$i]['added'][$j]."\n", FILE_APPEND)) {
          echo 'log wrote successed'."\n";
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
  $filename = $dir . $filename;
  //开始捕捉
  ob_start();
  readfile($url);
  $img = ob_get_contents();
  ob_end_clean();
  $fp2 = fopen($filename , "w+");
  fwrite($fp2, $img);
  fclose($fp2);
  return $filename;
}
