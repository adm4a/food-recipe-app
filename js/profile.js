$(document).ready(function () {
  $.ajax({
    url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/categories",
    type: "GET",
    headers: {
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
    success: function (response) {
      if (response && response.length > 0) {
        response.forEach(function (category) {
          $("#category_id").append(
            $("<option></option>")
              .attr("value", category.id)
              .text(category.name)
          );
        });

        response.forEach(function (category) {
          $("#editCategory").append(
            $("<option></option>")
              .attr("value", category.id)
              .text(category.name)
          );
        });
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log(xhr.status + ": " + xhr.responseText);
    },
  });

  $(document).ready(function () {
    $("#deleteAccountBtn").click(function () {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "DELETE",
            url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/me",
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
            success: function (response) {
              Swal.fire(
                "Deleted!",
                "Your account has been deleted.",
                "success"
              );
              localStorage.removeItem("token");
              location.reload();
            },
            error: function (xhr, status, error) {
              Swal.fire("Failed!", "Failed to delete your account.", "error");
            },
          });
        }
      });
    });

    $.ajax({
      type: "GET",
      url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/me",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      success: function (response) {
        $("#firstName").val(response.first_name);
        $("#lastName").val(response.last_name);
        $("#username").val(response.username);
        $("#email").val(response.email);
      },
    });

    $("#profileForm").submit(function (e) {
      e.preventDefault();

      var firstName = $("#firstName").val();
      var lastName = $("#lastName").val();
      var username = $("#username").val();
      var email = $("#email").val();
      var password = $("#password").val();

      $.ajax({
        type: "POST",
        url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/me",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        data: {
          first_name: firstName,
          last_name: lastName,
          username: username,
          email: email,
          password: password,
        },
        success: function (response) {
          Swal.fire({
            title: "Profile Updated",
            text: "Your profile has been updated successfully.",
            icon: "success",
          });
        },
        error: function (xhr, status, error) {
          Swal.fire({
            title: "Update Failed",
            text: "Something went wrong. Please try again.",
            icon: "error",
          });
        },
      });
    });

    $("#addRecipeForm").submit(function (e) {
      e.preventDefault();

      var title = $("#title").val();
      var description = $("#description").val();
      var instructions = $("#instructions").val();
      var imageURL = $("#imageURL").val();
      var prepTime = $("#prep_time").val();
      var categoryId = $("#category_id").val();

      var recipeData = {
        title: title,
        description: description,
        instructions: instructions,
        img_url: imageURL,
        prep_time: prepTime,
        category_id: categoryId,
      };

      $.ajax({
        type: "POST",
        url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/recipe",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        data: recipeData,
        success: function (response) {
          console.log("Recipe added successfully:", response);

          loadMyRecipes();

          $("#addRecipeForm")[0].reset();
          Swal.fire({
            title: "Recipe Added",
            text: "Your recipe has been added successfully.",
            icon: "success",
          });
        },
        error: function (xhr, status, error) {
          console.error("Failed to add recipe:", error);
          Swal.fire({
            title: "Error",
            text: "Failed to add recipe. Please try again later.",
            icon: "error",
          });
        },
      });
    });

    function loadMyRecipes() {
      $.ajax({
        type: "GET",
        url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/recipes/me",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        success: function (response) {
          var recipesHtml = response
            .map(function (recipe) {
              return `
          <div class="col-12 col-md-6 col-lg-3 mb-5 mt-5">
            <div class="card h-100">
              <img src="${recipe.img_url}" class="card-img-top" alt="Recipe image">
              <div class="card-body">
                <h5 class="card-title">${recipe.title}</h5>
                <p class="card-text">${recipe.description}</p>
                <div class="d-flex justify-content-end">
                  <button class="btn btn-danger btn-sm mx-1 delete-recipe" data-recipe-id="${recipe.id}">
                    <i class="fas fa-trash"></i>
                  </button>
                  <button class="btn btn-primary btn-sm mx-1 edit-recipe" data-recipe-id="${recipe.id}">
                    <i class="fas fa-edit"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          `;
            })
            .join("");

          if (response.length == 0) {
            recipesHtml = "You don't have any recipes added.";
          }

          $("#myRecipesRow").html(recipesHtml);

          $(".delete-recipe").click(function () {
            var recipeId = $(this).data("recipe-id");
            deleteRecipe(recipeId);
          });

          $(".edit-recipe").click(function () {
            var recipeId = $(this).data("recipe-id");
            var recipe = response.find(function (recipe) {
              return recipe.id === recipeId;
            });
            editRecipe(recipe);
          });
        },
        error: function (xhr, status, error) {
          Swal.fire({
            title: "Loading Failed",
            text: "Failed to load your recipes.",
            icon: "error",
          });
        },
      });
    }

    function deleteRecipe(recipeId) {
      Swal.fire({
        title: "Delete Recipe",
        text: "Are you sure you want to delete this recipe?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "DELETE",
            url: `https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/recipe/${recipeId}`,
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
            success: function (response) {
              Swal.fire({
                title: "Recipe Deleted",
                text: "The recipe has been deleted successfully.",
                icon: "success",
              });
              loadMyRecipes();
            },
            error: function (xhr, status, error) {
              Swal.fire({
                title: "Delete Failed",
                text: "Failed to delete the recipe. Please try again.",
                icon: "error",
              });
            },
          });
        }
      });
    }

    function editRecipe(recipe) {
      console.log(recipe);
      $("#editTitle").val(recipe.title);
      $("#editDescription").val(recipe.description);
      $("#editInstructions").val(recipe.instructions);
      $("#editImageURL").val(recipe.img_url);
      $("#editPrepTime").val(recipe.prep_time);
      $("#editCategory").val(recipe.category_id);

      $("#editRecipeModal").data("recipe-id", recipe.id);

      $("#editRecipeModal").modal("show");
    }

    $("#editRecipeForm").submit(function (e) {
      e.preventDefault();

      var title = $("#editTitle").val();
      var description = $("#editDescription").val();
      var instructions = $("#editInstructions").val();
      var imageURL = $("#editImageURL").val();
      var prepTime = $("#editPrepTime").val();
      var categoryId = $("#editCategory").val();

      var recipeData = {
        title: title,
        description: description,
        instructions: instructions,
        img_url: imageURL,
        prep_time: prepTime,
        category_id: categoryId,
      };

      var recipeId = $("#editRecipeModal").data("recipe-id");

      $.ajax({
        type: "PUT",
        contentType: "application/json",

        url:
          "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/recipe/" +
          recipeId,
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        data: JSON.stringify(recipeData),
        success: function (response) {
          console.log("Recipe updated successfully:", response);
          $("#editRecipeModal").modal("hide");
          Swal.fire({
            title: "Recipe Updated",
            text: "Your recipe has been updated successfully.",
            icon: "success",
          });
          loadMyRecipes();
        },
        error: function (xhr, status, error) {
          console.error("Failed to update recipe:", error);
          Swal.fire({
            title: "Error",
            text: "Failed to update recipe. Please try again later.",
            icon: "error",
          });
        },
      });
    });

    loadMyRecipes();
  });
});
