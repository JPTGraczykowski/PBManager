$(document).ready(function() {
  $('#limitModal').on('show.bs.modal', function (e) {
    let category_id = $(e.relatedTarget).data('category-id');
    let modal_header = 'Set the category limit';
    let modal = $(e.currentTarget);
    modal.find('input[name="limit"').val('');
    modal.find('#modalLabel').html(modal_header);
    modal.find('input[name="category_id"]').val(category_id);

    $.ajax({
      type: 'GET',
      url: '/CategoryController/limitExists',
      data: {category_id: category_id},
      success: function(response) {
        if (response === 'true') {
          modal.find('#unsetLimitButton').css('display', 'block');
        } else {
          modal.find('#unsetLimitButton').css('display', 'none');
        }
      },
      error: function() {
        alert('Not OK!');
      }
    })

    modal.find('#unsetLimitButton').click( function (e) {
      e.preventDefault();

      $.ajax({
        type: 'POST',
        url: '/CategoryController/unsetLimit',
        data: {category_id: category_id},
        success: function() {
          location.reload();
        },
      })
    })
   });
});