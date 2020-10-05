$(document).ready(function() {
  $('#editMethodModal').on('show.bs.modal', function (e) {
    let method_id = $(e.relatedTarget).data('method-id');
    let modal_header = 'Edit method category';
    let modal = $(e.currentTarget);
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
    let method_id = $(e.relatedTarget).data('method-id');
    let method_name = $(e.relatedTarget).data('method-name');
    let modal_header = 'Delete payment method';
    let modalQuestion = 'Are you sure to delete the payment method: ' + method_name + '?';
    let modalHint = 'If there are transactions which belongs to this payment method they will be assigned to "Default" payment method.';
    let modal = $(e.currentTarget);
    modal.find('input[name="method_name"').val('');
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="method_id"]').val(method_id);
    modal.find('#modal-question').html(modalQuestion);
    modal.find('#modal-hint').html(modalHint);
   });
});