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