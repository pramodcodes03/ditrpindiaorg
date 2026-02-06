(function($) {
  'use strict';
  $(function() {
    if ($('#calendar').length) {
      var date = new Date()
      var d    = date.getDate(),
       m    = date.getMonth(),
       y    = date.getFullYear()

      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,basicWeek'
        },
        defaultDate: '2017-07-12',
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: [{
            title: 'Present',
            start: '2023-05-10'
          },
          {
            title: 'Absent',
            start: '2017-07-01'
          }
        ]
      })
    }
  });
})(jQuery);