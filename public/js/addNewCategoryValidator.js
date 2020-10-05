$('#addIncomeCategoryModal').ready(function() {
  $('#addIncomeCategoryModalForm').validate({
      rules: {
        category_name: {
          required: true,
          remote: '/CategoryController/validateIncomeCategoryName'
        }
      },
      messages: {
        category_name: {
            remote: 'Category name already taken'
        }
      }
  });
});

$('#addExpenseCategoryModal').ready(function() {
  $('#addExpenseCategoryModalForm').validate({
      rules: {
        category_name: {
          required: true,
          remote: '/CategoryController/validateExpenseCategoryName'
        }
      },
      messages: {
        category_name: {
            remote: 'Category name already taken'
        }
      }
  });
});

$('#addPaymentMethodModal').ready(function() {
  $('#addPaymentMethodModalForm').validate({
      rules: {
        method_name: {
          required: true,
          remote: '/PaymentMethod/validatePaymentMethodName'
        }
      },
      messages: {
        method_name: {
            remote: 'Payment method name already taken'
        }
      }
  });
});
