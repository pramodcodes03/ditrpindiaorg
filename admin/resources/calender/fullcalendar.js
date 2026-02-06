$(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (https://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
       m    = date.getMonth(),
       y    = date.getFullYear()
    $('#calendar').fullCalendar({
      editable  : true,
      droppable : true,
      
      header    : {
        left  : 'prev',
        center: 'title',
        right : 'next'
      },
      buttonText: {
        today: 'Today',
        month: 'month',
        week : 'week',
        day  : 'day',
      },
      //Random default events
      events    : attendenceData,

      eventClick:  function(event, jsEvent, view) {
          var faculty     = $("#faculty").val();
          var appointment = $("#appointment").val();
          var attendancedate = (event.start).format("DD-MM-YYYY");
          getFacultyAttendence(faculty,appointment,attendancedate);

          $('#faculty_popup').val(faculty);
          $('#appointment_popup').val(appointment);
          $('#date').val(attendancedate);
          $('#theory').val('');
          $('#practical').val('');
          
          //$('#modalTitle').html(event.title);
          $('#modalBody').html(event.description);
          $('#eventUrl').attr('href',event.url);
          $('#calendarModal').modal();
        },
      dayClick:  function(date, event, jsEvent, view) {
            var date1 = new Date();
            var d = date1.getDate();
            var m = parseInt(date1.getMonth())+1;
            var y = date1.getFullYear();

            var d2 = date.format("D");
            var m2 = date.format("M");
            var y2 = date.format("YYYY");

            if(parseInt(d)<10) d = '0'+d;
            if(parseInt(m)<10) m = '0'+m;
            
            if(parseInt(d2)<10) d2 = '0'+d2;
            if(parseInt(m2)<10) m2 = '0'+m2;

            var today = new Date(y+'-'+m+'-'+d);
            var selected = new Date(y2+'-'+m2+'-'+d2);

            if(selected>today){ alert("You can not add attendance for dates greater than today!"); return;}

            var faculty     = $("#faculty").val();
            var appointment = $("#appointment").val();
            if(faculty=='')
            {
              alert('Select faculty !'); return;
            }
            if(appointment=='')
            {
              alert('Select appointment!'); return;
            }
          
           
     
            $('#faculty_popup').val(faculty);
            $('#appointment_popup').val(appointment);
            $('#date').val(date.format("DD-MM-YYYY"));
            $('#theory').val('');
            $('#practical').val('');

            $('#modalBody').html(event.description);
            $('#eventUrl').attr('href',event.url);
            $('#calendarModal').modal();
        },

    })

    function getFacultyAttendence(faculty,appointment,date)
    {
      var output=[];
      $.ajax({
         type:'post',
         url:'include/Controllers/ajax.php',
         data:{action:'get_faculty_attendance',faculty:faculty,appointment:appointment,date:date},
         success:function(data)
         {
          var data = JSON.parse(data);
          var ATTENDENCE_ID     = data[0].ATTENDENCE_ID;
          var APPOINTMENT_ID     = data[0].APPOINTMENT_ID;
          var ATTENDANCE_DATE_F = data[0].ATTENDANCE_DATE_F;
          var FACULTY_ID        = data[0].FACULTY_ID;
          var THEORY_HOURS      = data[0].THEORY_HOURS;
          var PRACTICAL_HOURS   = data[0].PRACTICAL_HOURS;

          $("#date").val(ATTENDANCE_DATE_F);
          $("#theory").val(THEORY_HOURS);
          $("#practical").val(PRACTICAL_HOURS);
          $("#faculty_popup").val(FACULTY_ID);
          $("#appointment_popup").val(APPOINTMENT_ID);
          $("#attendence").val(ATTENDENCE_ID);
          
          console.log(data);
         },
         error:function(data)
         {
          console.log(data);
         }
       
       }); 
      return output;
    }
    
  })