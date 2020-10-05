$(document).ready(function() {
  $('#editMethodModal').on('show.bs.modal', function (e) {
    var method_id = $(e.relatedTarget).data('method-id');
    var modal_header = 'Edit method category';
    var modal = $(e.currentTarget);
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="method_name"').val('');
    modal.find('input[name="method_id"]').val(method_id);
   });
});

$('#editMethodModal').on('show.bs.modal', function (e) {
  $('#editMethodModalForm').validate({
    rules: {
      method_name: {
        required: true,
        remote: '/PaymentMethod/validatePaymentMethodName'
      }
    },
    messages: {
      method_name: {
          remote: 'Method name already taken'
      }
    }
  });
});

$(document).ready(function() {
  $('#deleteMethodModal').on('show.bs.modal', function (e) {
    var method_id = $(e.relatedTarget).data('method-id');
    var method_name = $(e.relatedTarget).data('method-name');
    var modal_header = 'Delete payment method';
    var modalQuestion = 'Are you sure to delete the payment method: ' + method_name + '?';
    var modal = $(e.currentTarget);
    modal.find('input[name="method_name"').val('');
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="method_id"]').val(method_id);
    modal.find('#modal-question').html(modalQuestion);
   });
});