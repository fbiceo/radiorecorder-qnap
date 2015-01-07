<?php
  require 'function.php';
  //set_time_limit(0);

  // Expires in the past
  header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
  // Always modified
  header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
  // HTTP/1.1
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  // HTTP/1.0
  header("Pragma: no-cache");  
  
  
  
  header('Content-type: audio/mpeg');  
  $channel = (int) $_GET['channel'];
  $url = urldecode(get_hls_url($channel)).'&nas='.$_GET['nas'].'&ip='.getIP();//
  
  passthru("/usr/bin/ffmpeg -re -i '".$url."' -f wav - | /share/Web/radiorecorder/../opt/bin/lame - - 2>>/dev/null");
  //passthru("/usr/bin/ffmpeg -re -i '".$url."' -acodec copy -f wav - 2>>/dev/null"); //無法mp3收聽
  //passthru("/usr/bin/ffmpeg -re -i '".$url."' -f wav - 2>>/dev/null");
  
?>
