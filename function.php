<?php
  session_start();
  $cfg = parse_ini_file('/etc/config/def_share.info')+parse_ini_file('/etc/config/uLinux.conf',true,INI_SCANNER_RAW);
  $_SESSION['api'] = 'https://m.ccg.tw/radiorecorder/';
  $_SESSION['user'] = $cfg['System']['UPNP_UUID'];
  
  function get_channel_list(){
    return json_decode(get('get_channel_list',array()));
  }
  function get_index_channel(){
    return json_decode(get('get_index_channel',array('hinet_id'=>$hinet_id)));
  }
  function get_channel($hinet_id){
    return json_decode(get('get_channel',array('hinet_id'=>$hinet_id)));
  }
  function get_program_list(){    
    $params = array('hinet_id'=>$_COOKIE['hinet_id'],'user'=>$_SESSION['user'],'start'=>$_GET['start'],'end'=>$_GET['end']);    
    return get('get_program_list',$params);
  }
  function read_and_parse($file,$ary){
    $buf = file_get_contents($file);
    foreach($ary as $k => $v)
      $buf = str_replace($k,$v,$buf);
    return $buf;
  }
  function get($act,$array){
    $url = $_SESSION['api'].'?act='.$act;        
    return file_get_contents($url.'&'.http_build_query($array));
  }
  function create_schedule(){    
    $params['program_index'] = $_POST['program_index'];
    $params['hinet_id'] = $_POST['hinet_id'];
    $params['date'] = $_POST['date'];    
    $params['user'] = $_SESSION['user'];
    return get('create_schedule',$params);    
  }
  function delete_schedule(){       
    $params['schedule_id'] = $_POST['schedule_id'];   
    $params['user'] = $_SESSION['user'];    
    return get('delete_schedule',$params);    
  }  
  
  function PsExec($commandJob) { 
    //$command = $commandJob.' > /dev/null 2>&1 & echo $!'; 
    $command = $commandJob.' 2>&1 & echo $!'; 
    exec($command ,$op); 
    $pid = (int)$op[0]; 
    if($pid!="") return $pid; 
    return false; 
  } 

  function PsExists($pid) { 
    /*
    exec("ps ax | grep $pid 2>&1", $output); 
    while( list(,$row) = each($output) ) { 
      $row_array = explode(" ", $row); 
      $check_pid = $row_array[0]; 
      if($pid == $check_pid) { 
              return true; 
      } 
    } 
    return false; 
    */
    return file_exists( "/proc/$pid" );
  } 

  function PsKill($pid) { 
    exec("kill -9 $pid", $output); 
  }   
  function get_program($program_id,$date){
    return get('get_program',array('program_id'=>$program_id,'date'=>$date));
  }
  function get_hls_url($channel){
    $data = json_decode(get('get_hls_url',array('channel'=>$channel)));
    $page = file($data->url);
    $url = explode('"',$page[$data->line]);
    return urlencode($url[1]);
  }
  function getIP(){
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
    $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else    {
    $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }
?>
