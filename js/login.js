$(document).ready(function () {
  $("#loginForm").submit(function (e) {
    e.preventDefault();

    var username = $("#loginUsername").val();
    var password = $("#loginPassword").val();

    $.ajax({
      type: "POST",
      url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/login",
      data: {
        username: username,
        password: password,
      },
      success: function (response) {
        localStorage.setItem("token", response.token);

        Swal.fire({
          title: "Login Successful",
          text: "You have successfully logged in.",
          icon: "success",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "home.html";
            location.reload();
          }
        });
      },
      error: function (xhr, status, error) {
        if (xhr.status === 401) {
          Swal.fire({
            title: "Login Failed",
            text: "Invalid username or password.",
            icon: "error",
          });
        }
      },
    });
  });
});
