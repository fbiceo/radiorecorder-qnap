<?php
  set_time_limit(0);
  header("Last-Modified: " . gmdate("D, d M Y H:i:s",date('U') ) . " GMT");
  header('Content-type: audio/mpeg');  
  $url = str_replace('mms','mmsh',$_GET['url']);
  passthru("/usr/bin/ffmpeg -re -i '".$url."' -f wav - | /share/Web/radiorecorder/../opt/bin/lame - - 2>>/dev/null");
  //passthru("/usr/bin/ffmpeg -re -i '".$url."' -acodec copy -f wav - 2>>/dev/null");
  //passthru("/usr/bin/ffmpeg -re -i '".$url."' -f wav - 2>>/dev/null");
  
?>
