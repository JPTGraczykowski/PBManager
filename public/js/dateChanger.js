$(document).ready(function() {
  $('input[type="radio"]').click(function() {
    var radio_value = $(this).val();
    $('form#dateForm').submit();
  });
})

