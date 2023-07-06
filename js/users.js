$(document).ready(function () {
    function loadUsers() {
      $.ajax({
        type: "GET",
        url: "http://localhost/food-recipe-app/rest/users",
        success: function (data) {
          let userList = "";

          data.forEach((user) => {
            userList += `<li>${user.username}</li>`;
          });

          $("#userList").html(userList);
        },
        error: function (xhr, status, error) {
          console.error("Error occurred while fetching users: ", error);
        },
      });
    }

    loadUsers();
  });