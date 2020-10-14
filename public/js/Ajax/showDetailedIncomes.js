$(document).ready(function() {
  $('.editDeleteSpan').click(function(e) {
    e.preventDefault();

    let transaction_type = $(this).data('transaction-type');
    let category_name = $(this).data('category-name');
    let url = '';
    let transactionDetails = $('#' + category_name + '_' + transaction_type);

    if (transactionDetails.is(':hidden')) {

      $(this).find('svg').css({'transform': 'rotate(-180deg)'});

      if (transaction_type === 'income') {
        url = '/Balance/showDetailedIncomes';
      } else {
        url = '/Balance/showDetailedExpenses';
      }

      $.ajax({
        type: 'GET',
        url: url,
        data: {category_name: category_name},
        success: function(response) {
          let transactions = JSON.parse(response);

          if (transaction_type === 'income') {
            for(let i=0; i<transactions.length; i++)
            {
              transactionDetails.get(0).innerHTML += transactions[i].date_of_income;
              transactionDetails.get(0).innerHTML += ' | ' + transactions[i].amount;
              if (transactions[i].income_comment != '')
                transactionDetails.get(0).innerHTML += ' | ' + transactions[i].income_comment;
              if (i < transactions.length - 1)
                transactionDetails.get(0).innerHTML += "<hr />";
            }
          } else {
            for(let i=0; i<transactions.length; i++)
            {
              transactionDetails.get(0).innerHTML += transactions[i].date_of_expense;
              transactionDetails.get(0).innerHTML += ' | ' + transactions[i].amount;
              transactionDetails.get(0).innerHTML += ' | ' + transactions[i].payment_method;
              if (transactions[i].expense_comment != '')
                transactionDetails.get(0).innerHTML += ' | ' + transactions[i].expense_comment;
              if (i < transactions.length - 1)
                transactionDetails.get(0).innerHTML += "<hr />";
            }
          }
        },
        error: function(response) {
          alert('Not OK!');
        }
      });

      transactionDetails.css('display', 'block');

    } else {
      $(this).find('svg').css({'transform': 'rotate(0deg)'});
      transactionDetails.get(0).innerHTML = '';
      transactionDetails.css('display', 'none');
    }
  });
});