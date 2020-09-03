$.validator.addMethod('validPassword',
      function(value, element, param) {
          if (value != '') {
              if (value.match(/.*[a-z]+.*/i) == null) {
                  return false;
              }
              if (value.match(/.*\d+.*/) == null) {
                  return false;
              }
          }

          return true;
      },
      'Must contain at least one letter and one number'
  );

  $('#changeNameModal').ready(function() {
      $('#changeNameModalForm').validate({
          rules: {
              name: 'required',
          }
      });
  });

  $('#changeEmailModal').ready(function() {
      $('#changeEmailModalForm').validate({
          rules: {
            email: {
              required: true,
              email: true,
              remote: '/Signup/validateEmail'
            }
          },
          messages: {
            email: {
                remote: 'Email already taken'
            }
          }
      });
  });

  $('#changePasswordModal').ready(function() {
    $('#changePasswordModalForm').validate({
      rules: {
        old_password: {
          remote: '/Settings/checkPassword'
        },
        new_password: {
          required: true,
          minlength: 6,
          validPassword: true
        },
        password_confirmation: {
          equalTo: '#inputPassword'
        }
      },
      messages: {
        email: {
          remote: 'Email already taken'
        },
        old_password: {
          remote: 'Password is incorrect'
        }
      }
    });
  });