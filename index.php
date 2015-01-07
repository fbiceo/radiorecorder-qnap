<?php
  include 'function.php';
  error_reporting(E_ALL & ~E_NOTICE);

  if((int) $_GET['channel'] > 0){
    $CHANNEL = get_channel($_GET['channel']);      
  }
  else{
    $CHANNEL = get_index_channel();  
  }
  setcookie( "hinet_id", $CHANNEL->hinet_id, strtotime( '+30 days' ) );
  //$_SESSION['hinet_id'] = $CHANNEL->hinet_id;
  
  
  $template = array();  
  $template['{CHANNEL_LOGO}'] = $CHANNEL->logo; 
  $template['{CHANNEL_NAME}'] = $CHANNEL->name;
  $template['{URL}'] = $CHANNEL->streaming;
  $channel_list = get_channel_list();
  foreach($channel_list as $v){
    $template['{CHANNEL_LIST}'] .= '<li class="list-group-item"><a href="?channel='.$v->hinet_id.'"><img src="'.$_SESSION['api'].'logo/'.$v->hinet_id.'.png" alt="'.$v->name.'" title="'.$v->name.'" class="img-thumbnail"/>'.$v->name.'</a></li>'."\n";
  }
  
  echo read_and_parse('template.html',$template);
?>