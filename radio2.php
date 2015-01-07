<?php
  //set_time_limit(0);
  header("Last-Modified: " . gmdate("D, d M Y H:i:s",date('U') ) . " GMT");
  header('Content-type: audio/mpeg');  
  //$url = str_replace('mms','mmsh',$_GET['url']);
  $url = 'http://cpradio-hichannel.cdn.hinet.net/live/pool/hich-ra000003/ra-hls/index.m3u8?token1=2BJEObrVZW6OW1_rUqKjqQ&token2=J6J-sE-trG-YwyIml68sdw&expire1=1413466312&expire2=1413487912';
  //$url = urlencode($_GET['url']);
  $cmd = "/usr/bin/ffmpeg -i '".$url."' -f wav - | /share/Web/radiorecorder/../opt/bin/lame - - 2>>/dev/null";
  passthru($cmd);
?>