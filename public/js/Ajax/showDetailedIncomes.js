$(document).ready(function() {
  $('.editDeleteSpan').click(function(e) {
    e.preventDefault();

    let transaction_type = $(this).data('transaction-type');
    let category_name = $(this).data('category-name');
    let url = '';

    if (transaction_type == 'income') {
      url = '/Balance/showDetailedIncomes';
    } else {
      url = '/Balance/showDetailedExpenses';
    }

    $.ajax({
      type: 'GET',
      url: url,
      data: {category_name: category_name},
      success: function(response) {
        alert(response);
      },
      error: function(response) {
        alert('Not OK!');
      }
    });
  });
});