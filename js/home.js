$(document).ready(function () {
    $.ajax({
      type: "GET",
      url: "http://localhost/food-recipe-app/rest/me",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      success: function (response) {
        var user = response;
        $("#welcomeMessage").html("Welcome, " + user.username + "!");
      },
    });

    $.ajax({
      type: "GET",
      url: "http://localhost/food-recipe-app/rest/recipes",
      success: function (response) {
        var recipes = response.recipes; 

        shuffleArray(recipes);

        var featuredRecipes = recipes.slice(0, 3);
        var featuredRecipesHtml = "";

        featuredRecipes.forEach(function (recipe) {
          featuredRecipesHtml += `
            <div class="col-lg-4 col-md-6">
              <div class="card mb-4">
                <img src="${recipe.img_url}" class="card-img-top" alt="${recipe.title}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                  <h5 class="card-title">${recipe.title}</h5>
                  <p class="card-text">${recipe.description}</p>
                  <button class="btn btn-primary" data-id="${recipe.id}">View Recipe</button>
                </div>
              </div>
            </div>
          `;
        });

        $("#featuredRecipes").html(featuredRecipesHtml);

        $(".btn-primary").on("click", function (e) {
          e.stopPropagation();
          let recipeId = $(this).data("id");
          $("#app").load(`/food-recipe-app/pages/recipe.html`, function () {
            $.getScript("/food-recipe-app/js/recipe.js", function () {
              loadRecipe(recipeId);
            });
          });
        });
      },
    });

    function shuffleArray(array) {
      for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
      }
    }
  });