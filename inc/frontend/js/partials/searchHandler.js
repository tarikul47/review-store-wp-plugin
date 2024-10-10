// searchHandler.js
window.performSearch = function (searchTerm) {
  jQuery.ajax({
    url: "your-search-endpoint-url", // Replace with the correct URL
    method: "GET",
    data: { q: searchTerm },
    success: function (results) {
      console.log("Search results:", results);
      // Handle displaying results
    },
    error: function (error) {
      console.error("Search error", error);
      // Handle error
    },
  });
};

jQuery(document).on("keyup", "#search-input", function () {
  const searchTerm = jQuery(this).val();
  window.performSearch(searchTerm); // Call the performSearch function from searchHandler.js
});
