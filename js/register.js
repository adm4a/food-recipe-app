$(document).ready(function () {
  $("#registerForm").submit(function (event) {
    event.preventDefault();
    var username = $("#registerUsername").val();
    var password = $("#registerPassword").val();
    var firstName = $("#registerFirstName").val();
    var lastName = $("#registerLastName").val();
    var email = $("#registerEmail").val();

    if (!validateFields(username, password, firstName, lastName, email)) {
      return;
    }

    var data = {
      username: username,
      password: password,
      first_name: firstName,
      last_name: lastName,
      email: email,
    };

    $.ajax({
      url: "http://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/register",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(data),
      success: function (response) {
        Swal.fire({
          icon: "success",
          title: "Registration Successful",
          text: "You have successfully registered.",
        }).then(function () {
          window.location.href = "login.html";
          location.reload();
        });
      },
      error: function (xhr, status, error) {
        Swal.fire({
          icon: "error",
          title: "Registration Failed",
          text: "Sorry, there was an error during registration.",
        });
      },
    });
  });

  function validateFields(username, password, firstName, lastName, email) {
    if (
      username === "" ||
      password === "" ||
      firstName === "" ||
      lastName === "" ||
      email === ""
    ) {
      Swal.fire({
        icon: "error",
        title: "Validation Error",
        text: "Please fill in all fields.",
      });
      return false;
    }

    return true;
  }
});
