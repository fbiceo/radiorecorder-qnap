<?php
  include 'function.php';
  
  $jobs = json_decode(get('get_recording_jobs',array(user=>$_SESSION['user'])));   
  $root = $cfg['defVolMP'].'/'.$cfg['defMultimedia'].'/';
  $lame = $cfg['defVolMP'].'/.qpkg/radiorecorder/opt/bin/lame';
  foreach($jobs as $v){
    //$cmd = 'wget "'.$v->recording_url.'&nas='.$_SESSION['user'].'" -O - >> "'.$root.$v->channel_name.'/'.$v->program_name.date('Y-m-d').$v->program_time_range.'.mp3" --no-check-certificate';
    $cmd = 'curl -skS "'.$v->recording_url.'&nas='.$_SESSION['user'].'" >> "'.$root.$v->channel_name.'/'.$v->program_name.date('Y-m-d').$v->program_time_range.'.mp3"';
    if($v->recording_status == 0 && $v->recording_pid == 0){
      @mkdir($root.$v->channel_name);    
      
      //$cmd = "/usr/bin/ffmpeg -re -i '".urldecode(get_hls_url($v->channel_hinet_id))."' -f wav - | ".$lame." -  \"".$root.$v->channel_name.'/'.$v->program_name.date('Y-m-d').$v->program_time_range.'.mp3"';
      //$cmd = 'ffmpeg -re -i "'.urldecode(get_hls_url($v->channel_hinet_id)).'" -f mp4 "'.$root.$v->channel_name.'/'.$v->program_name.date('Y-m-d').$v->program_time_range.'.mp3"';
      //$cmd = 'ffmpeg -re -i "'.urldecode(get_hls_url($v->channel_hinet_id)).'" -acodec copy "'.$root.$v->channel_name.'/'.$v->program_name.date('Y-m-d').$v->program_time_range.'.mp3"';

      $pid = PsExec($cmd);
      
      get('update_recording',array('recording_id'=>$v->recording_id,'status'=>1,'pid'=>$pid,'user'=>$_SESSION['user']));
      //echo $cmd."\n";
      
    }
    else if($v->recording_status == 1 && $v->recording_pid > 0){
      //check pid status
      $result = PsExists($v->recording_pid);
      if(!$result){
        //get('update_recording',array('recording_id'=>$v->recording_id,'status'=>2,'pid'=>$v->recording_pid,'user'=>$_SESSION['user']));
        $pid = PsExec($cmd);
        get('update_recording',array('recording_id'=>$v->recording_id,'status'=>1,'pid'=>$pid,'user'=>$_SESSION['user']));
      }
      
      echo 'check '.$v->recording_pid.($result? ' success':' failed but restart recording')."\n";
    }
    else if($v->recording_status == 3 && $v->recording_pid > 0){
      //kill pid
      if(PsExists($v->recording_pid)){
        PsKill($v->recording_pid);
        get('update_recording',array('recording_id'=>$v->recording_id,'status'=>4,'pid'=>$v->recording_pid,'user'=>$_SESSION['user']));
        echo 'kill '.$v->recording_pid."\n";
      }
      else{
        get('update_recording',array('recording_id'=>$v->recording_id,'status'=>2,'pid'=>$v->recording_pid,'user'=>$_SESSION['user']));
        echo 'recording '.$v->recording_pid." stop abnormmal \n";        
      }
    }
    else{
      // should not happen
    }
    sleep(0.5);
  }
?>
