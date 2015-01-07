	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
      //lang:'zh-tw',
			header: {
				left: '',
				center: ''
			},
      theme: true,
      slotDuration: '00:15:00',
      allDaySlot: false,
      defaultView: 'agendaWeek',
			editable: false,
			eventLimit: true, // allow "more" link when too many events
      axisFormat: 'H:mm' ,
      timeFormat: 'H:mm' ,
			events: {
				url: './api.php?act=get_program_list',
				error: function() {
					$('#script-warning').show();
				}
			},
      eventRender: function (event, element) {            
                if(event.className!='outofdate'){
                  element.find('.fc-content').append('<button data-date="'+event.start.format('YYYY-MM-DD')+'" data-mouseenter="'+event.mouseenter+'" data-description="'+event.description+'" data-date="'+event.date+'" data-hinet_id="'+event.hinet_id+'" data-program_index="'+event.program_index+'" data-schedule_id="'+event.schedule_id+'"  type="button" class="'+event.className+' btn '+event.btnclass+' btn-xs" title="'+event.last_action+'">' + event.description + "</button>");
                }
      },
      loading: function(bool) {
        if (bool) 
          $('#myModal').modal();
        else 
          $('#myModal').modal('hide');
      }   
		});
    //console.log($('#channel-list').height());
		$('#calendar').fullCalendar('option', 'height', 1900);
    //$('#calendar').fullCalendar('option', 'contentHeight', $('#channel-list').height());
    $( document ).on( 'click', 'button.program', function(){      
      $('#myModal').modal();
      var btn = $(this);
      var program_index = $(this).data('program_index');
      var date = $(this).data('date');
      var hinet_id = $(this).data('hinet_id');
      $.post('./api.php?act=create_schedule',{program_index:program_index,date:date,hinet_id:hinet_id},function(data){
       btn.removeClass('btn-danger').removeClass('program').addClass(data.btnclass).addClass(data.className).text(data.description).data('mouseenter',data.mouseenter).data('description',data.description);
       //rray('description'=>'已預約','className'=>'schedule','btnclass'=>'btn-info','backgroundColor'=>'#FFF','textColor'=>'#000');       
       $('#myModal').modal('hide');
      },"json");
    });
    $( document ).on( 'click', 'button.schedule', function(){      
      $('#myModal').modal();
      var btn = $(this);
      var schedule_id = $(this).data('schedule_id');
      $.post('./api.php?act=delete_schedule',{schedule_id:schedule_id,date:btn.data('date')},function(data){
        btn.removeClass('btn-info').removeClass('schedule').addClass(data.btnclass).addClass(data.className).text(data.description);      
       $('#myModal').modal('hide');
      },"json");          
    });
    $( document ).on( 'mouseenter', 'button.schedule', function(){      
      var btn = $(this);
      btn.text(btn.data('mouseenter')).removeClass('btn-info').addClass('btn-warning');
    });
    $( document ).on( 'mouseleave', 'button.schedule', function(){      
      var btn = $(this);
      btn.text(btn.data('description')).removeClass('btn-warning').addClass('btn-info');
    });   
    $( document ).on( 'mouseenter', 'button.replay', function(){      
      var btn = $(this);
      btn.text(btn.data('mouseenter'));
    });
    $( document ).on( 'mouseleave', 'button.replay', function(){      
      var btn = $(this);
      btn.text(btn.data('description'));
    }); 
    $( document ).on( 'click', 'button.replay', function(){      
      var btn = $(this);
      var id = btn.data('program_id');
      //console.log(id);
      $.post("./api.php?act=get_program",{program_id: btn.data('program_id'),date:btn.data('date')},function(data){
      
      var audio = document.getElementById('player');      
      audio.src= './media'+data.source;
      audio.load();
      audio.play();
      $('#program_title').text(data.replay_title+data.source.replace('.mp3',''));
      },'json');
    });   
 
	});