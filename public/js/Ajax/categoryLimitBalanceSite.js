$(document).ready(function () {
  let span_counter = 0;
  $('.expenseCell').map(function () {
    let category_name = $(this).data('category-name');
    let amount = this.innerHTML;
    $.ajax({
      type: 'GET',
      url: '/CategoryController/getCategoryLimitByName',
      data: {category_name: category_name},
      success: function(response) {
        if (response != 'null') {
          response = response.slice(0, -1);
          response = response.slice(1);
          response = Number(response);
          $('.categoryExpenseCell').get(span_counter).innerHTML += ' (' + response.toFixed(2) + ')';
          if (response < amount) {
            $('.expenseCell').get(span_counter).style.cssText = 'background-color: red;';
          } else {
            $('.expenseCell').get(span_counter).style.cssText = 'background-color: green;';
          }
        }
        span_counter ++;
      },
      error: function() {
        alert('Not OK!');
      }
    });
  });
});