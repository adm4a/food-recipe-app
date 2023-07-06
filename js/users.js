$(document).ready(function () {
  function loadUsers() {
    $.ajax({
      type: "GET",
      url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/users",
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
