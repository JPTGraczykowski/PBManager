$(document).ready(function() {
  $('#editCategoryModal').on('show.bs.modal', function (e) {
    var category_id = $(e.relatedTarget).data('category-id');
    var transaction_type = $(e.relatedTarget).data('transaction-type');
    var modal_header = 'Edit ' + transaction_type + ' category';
    var modal = $(e.currentTarget);
    modal.find('input[name="category_name"').val('');
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="transaction_type"]').val(transaction_type);
    modal.find('input[name="category_id"]').val(category_id);
   });
});

$('#editCategoryModal').on('show.bs.modal', function (e) {
  var transaction_type = $(e.relatedTarget).data('transaction-type');
  var remote_method_link = (transaction_type == 'income') ? '/CategoryController/validateIncomeCategoryName' : '/CategoryController/validateExpenseCategoryName';
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
    var category_id = $(e.relatedTarget).data('category-id');
    var category_name = $(e.relatedTarget).data('category-name');
    var transaction_type = $(e.relatedTarget).data('transaction-type');
    var modal_header = 'Delete ' + transaction_type + ' category';
    var modalQuestion = 'Are you sure to delete the category: ' + category_name + '?';
    var modal = $(e.currentTarget);
    modal.find('input[name="category_name"').val('');
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="transaction_type"]').val(transaction_type);
    modal.find('input[name="category_id"]').val(category_id);
    modal.find('#modal-question').html(modalQuestion);
   });
});