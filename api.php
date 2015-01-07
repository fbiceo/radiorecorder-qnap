<?php
  include 'function.php';
  switch($_GET['act']){
    case 'get_program':
      echo get_program($_REQUEST['program_id'],$_REQUEST['date']);
    break;
    case 'get_program_list':      
      echo get_program_list();
    break;
    case 'create_schedule':
      echo create_schedule();
    break;
    case 'delete_schedule':
      echo delete_schedule();
    break;    
    default:
    break;
  }
?>