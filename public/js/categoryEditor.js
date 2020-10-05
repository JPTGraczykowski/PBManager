$(document).ready(function() {
  $('#editCategoryModal').on('show.bs.modal', function (e) {
    let category_id = $(e.relatedTarget).data('category-id');
    let transaction_type = $(e.relatedTarget).data('transaction-type');
    let modal_header = 'Edit ' + transaction_type + ' category';
    let modal = $(e.currentTarget);
    modal.find('input[name="category_name"').val('');
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="transaction_type"]').val(transaction_type);
    modal.find('input[name="category_id"]').val(category_id);
   });
});

$('#editCategoryModal').on('show.bs.modal', function (e) {
  let transaction_type = $(e.relatedTarget).data('transaction-type');
  let remote_method_link = (transaction_type == 'income') ? '/CategoryController/validateIncomeCategoryName' : '/CategoryController/validateExpenseCategoryName';
  $('#editCategoryModalForm').validate({
    rules: {
      category_name: {
        required: true,
        remote: remote_method_link
      }
    },
    messages: {
      category_name: {
          remote: 'Category name already taken'
      }
    }
  });
});

$(document).ready(function() {
  $('#deleteCategoryModal').on('show.bs.modal', function (e) {
    let category_id = $(e.relatedTarget).data('category-id');
    let category_name = $(e.relatedTarget).data('category-name');
    let transaction_type = $(e.relatedTarget).data('transaction-type');
    let modal_header = 'Delete ' + transaction_type + ' category';
    let modalQuestion = 'Are you sure to delete the category: ' + category_name + '?';
    let modalHint = 'If there are transactions which belongs to this category they will be assigned to "Default" category.';
    let modal = $(e.currentTarget);
    modal.find('input[name="category_name"').val('');
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="transaction_type"]').val(transaction_type);
    modal.find('input[name="category_id"]').val(category_id);
    modal.find('#modal-question').html(modalQuestion);
    modal.find('#modal-hint').html(modalHint);
   });
});