$(document).ready(function() {

  $('input[type="radio"]').click(function() {
    $('form#dateForm').submit();
  });

  let time_period = $('#setDateRangeForm').data('time-period');
  
  switch (time_period) {
    case 'current_month':
      $('#currentMonthRadio').prop('checked', 'true');
      break;
    case 'previous_month':
      $('#previousMonthRadio').prop('checked', 'true');
      break;
    case 'current_year':
      $('#currentYearRadio').prop('checked', 'true');
      break;
    case 'total_time':
      $('#totalTimeRadio').prop('checked', 'true');
      break;
    case 'other_period_of_time':
      $('#otherPeriodOfTimeLabel').css('display', 'block');
  }  
})
