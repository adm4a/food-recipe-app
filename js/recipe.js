function loadRecipe(recipeId) {
  if (recipeId) {
    $.ajax({
      type: "GET",
      url: `http://localhost/food-recipe-app/rest/recipe/${recipeId}`,
      success: function (data) {
        $("#recipeTitle").text(data.title);
        $("#recipeImage").attr("src", data.img_url);
        $("#recipeDescription")
          .text(data.description)
          .parent()
          .toggle(!!data.description);
        $("#recipePrepTime")
          .text(data.prep_time)
          .parent()
          .toggle(!!data.prep_time);
        $("#recipeAuthor").text(data.username).parent().toggle(!!data.username);
        $("#recipeInstructions")
          .text(data.instructions)
          .parent()
          .toggle(!!data.instructions);
        tyle = "display: none";
        $("#recipeAddedOn")
          .text(data.created_at)
          .parent()
          .toggle(!!data.created_at);
        $("#recipeCategory")
          .text(data.category)
          .parent()
          .toggle(!!data.category);
      },
      error: function (xhr, status, error) {
        console.error("Error occurred while fetching the recipe: ", error);
      },
    });
  }
}
$("#backToRecipes").click(function () {
  $("#app").load("/food-recipe-app/pages/recipes.html", function () {});
});
