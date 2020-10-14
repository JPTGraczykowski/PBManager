$(document).ready(function() {
  $('#formAddExpense').validate({
    rules: {
      amount: {
        required: true,
        min: 0.01,
        max: 9999999.99
      },
      date: 'required',
      comment: {
        maxlength: 100
      }
    }
  });
});

function checkLimit() {
  let category_name = $('#inputCategory').children('option:selected').data('category-name');
  let amount = $('#inputAmount').get(0).value;
  amount = Number(amount);
  let sum_in_the_category = 0;

  $.ajax({
    type: 'GET',
    url: '/Balance/showSumInTheCategory',
    data: {category_name: category_name},
    async: false,
    success: function(response) {
      sum_in_the_category = Number(response);
    }
  });

  $('#categoryLimitWarning').get(0).innerHTML = '';
  $('#inputAmount').css('background-color', 'white');

  $.ajax({
    type: 'GET',
    url: '/CategoryController/getCategoryLimitByName',
    data: {category_name: category_name},
    async: false,
    success: function(response) {
      if (response != 'null') {
        response = response.slice(0, -1);
        response = response.slice(1);
        response = Number(response);
        if (response < amount + sum_in_the_category) {
          $('#inputAmount').css('background-color', 'red');
          $('#categoryLimitWarning').get(0).innerHTML = 'You have exceeded the category limit';
        }
      }
    },
    error: function() {
      alert('Not OK!');
    }
  });
}