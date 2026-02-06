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
            $('#modalTitle').html(event.title);
            $('#modalBody').html(event.description);
            $('#eventUrl').attr('href',event.url);
            $('#calendarModal').modal();
        },
      dayClick:  function(date, event, jsEvent, view) {

            var faculty     = $("#faculty").val();
            var appointment = $("#appointment").val();
            if(faculty=='' && appointment=='')
            {
              alert('Select faculty and appointment!'); return;
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

   /* function getFacultyAttendence(faculty,date='')
    {
      $.ajax({
         type:'get',
         url:'',,
       
       }); 
    }
    */
  })