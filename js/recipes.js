$(document).ready(function () {
  let currentPage = 1;
  let itemsPerPage = 8;

  function loadRecipes(page, itemsPerPage, searchText = "") {
    $.ajax({
      type: "GET",
      url: `https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/recipes?page=${page}&itemsPerPage=${itemsPerPage}&searchText=${searchText}`,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      success: function (data) {
        let recipeCards = "";

        data.recipes.forEach((recipe) => {
          let isFavorite = recipe.isFavorite ? "favorite" : "";
          let star = `<i class="fa fa-star ${isFavorite}" style="cursor: pointer;" data-id="${recipe.id}"></i>`;
          recipeCards += `
            <div class="col-12 col-md-6 col-lg-3 mb-5">
              <div class="card h-100">
                <img src="${recipe.img_url}" class="card-img-top" alt="Recipe Image">
                <div class="card-body">
                  <h5 class="card-title">${recipe.title}</h5>
                  <p class="card-text">${recipe.description}</p>
                  <button class="btn btn-primary" data-id="${recipe.id}">View Recipe</button>
                </div>
                <div class="card-footer">
                  <small class="text-muted">Preparation time: <b>${recipe.prep_time}</b></small>
                  ${star}
                </div>
              </div>
            </div>
          `;
        });

        let pagination = "";
        for (let i = 1; i <= Math.ceil(data.totalCount / itemsPerPage); i++) {
          pagination += `<li class="page-item ${
            i == currentPage ? "active" : ""
          }"><a class="page-link" href="#">${i}</a></li>`;
        }

        $("#recipesRow").html(recipeCards);
        $("#pagination").html(pagination);

        $(".btn-primary").on("click", function (e) {
          e.stopPropagation();
          let recipeId = $(this).data("id");
          $("#app").load(`/food-recipe-app/pages/recipe.html`, function () {
            $.getScript("/food-recipe-app/js/recipe.js", function () {
              loadRecipe(recipeId);
            });
          });
        });

        $("#recipesRow").on("click", ".fa-star", function (e) {
          e.stopPropagation();
          let recipeId = $(this).data("id");
          let isFavorite = $(this).hasClass("favorite");
          let starElement = $(this);

          if (isFavorite) {
            $.ajax({
              url: `https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/favorites/me`,
              type: "DELETE",
              data: JSON.stringify({ recipe_id: recipeId }),
              contentType: "application/json",

              headers: {
                Authorization: "Bearer " + localStorage.getItem("token"),
              },
              success: function () {
                starElement.removeClass("favorite");
              },
            });
          } else {
            $.ajax({
              url: `https://food-recipe-ibu-a36511ad80e4.herokuapp.com/rest/favorites/me`,
              type: "POST",
              data: JSON.stringify({ recipe_id: recipeId }),
              contentType: "application/json",

              headers: {
                Authorization: "Bearer " + localStorage.getItem("token"),
              },
              success: function () {
                starElement.addClass("favorite");
              },
            });
          }
        });
      },
      error: function (xhr, status, error) {
        console.error("Error occurred while fetching recipes: ", error);
      },
    });
  }

  loadRecipes(currentPage, itemsPerPage);

  $(document).on("click", ".page-link", function (e) {
    e.preventDefault();
    currentPage = parseInt($(this).text());
    loadRecipes(currentPage, itemsPerPage, $("#searchInput").val());
  });

  $("#searchInput").on(
    "keyup",
    _.debounce(function (e) {
      currentPage = 1;
      loadRecipes(currentPage, itemsPerPage, $(this).val());
    }, 300)
  );
});
