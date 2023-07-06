$(document).ready(function () {
  function loadFavorites() {
    $.ajax({
      type: "GET",
      url: "https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/favorites/me",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      success: function (data) {
        let favoriteCards = "";

        data.forEach((favorite) => {
          favoriteCards += `
              <div class="col-12 col-md-6 col-lg-3 mb-5">
                <div class="card h-100">
                  <img src="${favorite.img_url}" class="card-img-top" alt="Recipe Image">
                  <div class="card-body">
                    <h5 class="card-title">${favorite.title}</h5>
                    <p class="card-text">${favorite.description}</p>
                    <button class="btn btn-primary" data-id="${favorite.id}">View Recipe</button>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted">Preparation time: <b>${favorite.prep_time}</b></small>
                  </div>
                </div>
              </div>
            `;
        });

        $("#favoritesRow").html(favoriteCards);

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
      error: function (xhr, status, error) {
        console.error(
          "Error occurred while fetching favorite recipes: ",
          error
        );
      },
    });
  }

  loadFavorites();
});
