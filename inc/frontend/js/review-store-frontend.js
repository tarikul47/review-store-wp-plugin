(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   *
   * The file is enqueued from inc/frontend/class-frontend.php.
   */
  $(document).ready(function () {
    console.log("DOM ready");

    /**
     * Review Form start marking and store value in hidden input
     */
    $(".single-review").each(function () {
      var $review = $(this);

      // Handle click event on star items
      $review.find(".review-icon .star").on("click", function () {
        var $stars = $review.find(".review-icon .star");
        var selectedValue = $(this).data("value");

        // Remove 'selected' class from all stars
        $stars.removeClass("selected");

        // Add 'selected' class to the clicked star and all stars before it
        $stars.each(function () {
          if ($(this).data("value") <= selectedValue) {
            $(this).addClass("selected");
          }
        });

        // Update the hidden input field with the selected value
        $review.find('input[type="hidden"]').val(selectedValue);
      });
    });

    /**
     * Here Frontend Review Adding
     * Action and other neecessary thigs already set on form page
     */
    var $reviewform = $("#reviewform"); // Correct form ID
    $reviewform.on("submit", function (e) {
      e.preventDefault();

      // Collect all rating inputs
      var ratingInputs = [
        { name: "fair", input: $(this).find('input[name="fair"]').val() },
        {
          name: "professional",
          input: $(this).find('input[name="professional"]').val(),
        },
        {
          name: "response",
          input: $(this).find('input[name="response"]').val(),
        },
        {
          name: "communication",
          input: $(this).find('input[name="communication"]').val(),
        },
        {
          name: "decisions",
          input: $(this).find('input[name="decisions"]').val(),
        },
        {
          name: "recommend",
          input: $(this).find('input[name="recommend"]').val(),
        },
        {
          name: "comments",
          input: $(this).find('textarea[name="comments"]').val(),
        },
      ];

      // Check if any rating is missing (empty, null, or 0)
      var isValid = true;
      var missingFields = [];

      ratingInputs.forEach(function (rating) {
        console.log("rating", rating); // Debugging log to see input values

        // If it's a numeric rating, reject empty, null, or "0" as invalid
        if (
          [
            "fair",
            "professional",
            "response",
            "communication",
            "decisions",
            "recommend",
          ].includes(rating.name)
        ) {
          if (!rating.input || rating.input === "0") {
            isValid = false;
            missingFields.push(rating.name);
          }
        }
        // If it's the comments field, reject empty or whitespace values
        else if (
          rating.name === "comments" &&
          (!rating.input || rating.input.trim() === "")
        ) {
          isValid = false;
          missingFields.push(rating.name);
        }
      });

      // console.log(!isValid);
      // console.log(missingFields);
      // return;

      // If validation fails, show an error message and prevent submission
      if (!isValid) {
        var errorMessage =
          "Please select a rating for the following fields: " +
          missingFields.join(", ");
        $("#review-message").text(errorMessage).show(); // Show error message
        console.log("Validation failed. Missing fields: ", missingFields); // Log missing fields for debugging
        return false; // Prevent form submission
      }

      // If all fields are valid, proceed with AJAX
      if (isValid) {
        var formData = new FormData(this);

        $.ajax({
          url: myPluginAjax.ajax_url, // Ensure this is set to the correct AJAX URL
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: function () {
            // Optional: Add preloader or progress bar actions here
          },
          success: function (response) {
            console.log("AJAX Response:", response); // Log the response for debugging

            if (response.success) {
              $("#review-message").text(response.data.message).show(); // Show success message
              $("#singlereview").hide(); // Hide the submit form or button
            } else {
              $("#review-message").text(response.data.message).show(); // Show error message from server
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown); // Log AJAX errors
            $("#review-message")
              .text("An error occurred while submitting your review.")
              .show(); // Show error message
          },
        });
      }
    });

    /** ======================================
     *  Profile Loads dynamically
     *  =====================================*/

    const searchButton = $("#search-button");
    const clearButton = $("#clear-button");
    const searchInput = $("#profile-search");
    const profileList = $("#profile-list");
    const pagination = $(".pagination");

    let currentPage = 1; // Track the current page

    // Function to perform AJAX request (initial load, search, and pagination)
    function performAjaxSearch(searchTerm = "", page = 1) {
      $.ajax({
        url: myPluginAjax.ajax_url, // WordPress AJAX handler URL
        type: "POST",
        data: {
          action: "search_profiles",
          search_term: searchTerm, // Send the search term to the backend
          page: page, // Send current page number for pagination
        },
        beforeSend: function () {
          profileList.html('<tr><td colspan="9">Loading...</td></tr>');
        },
        success: function (response) {
          console.log(response);

          // Response should include the table rows and pagination links
          profileList.html(response.data.profiles);
          pagination.html(response.data.pagination); // Pagination links
        },
        error: function () {
          profileList.html(
            '<tr><td colspan="9">An error occurred. Please try again.</td></tr>'
          );
        },
      });
    }

    // Function to toggle the search button based on input value
    function toggleSearchButton() {
      const searchTerm = searchInput.val().trim();
      if (searchTerm === "") {
        searchButton.prop("disabled", true); // Disable button
        console.log('searcg');
        performAjaxSearch("", currentPage);
      } else {
        searchButton.prop("disabled", false); // Enable button
      }
    }

    // Load profiles on page load
    performAjaxSearch("", currentPage);

    // When the user types in the search box (use input event for real-time changes)
    searchInput.on("input", function () {
      toggleSearchButton(); // Check whether to enable or disable the search button

      const searchTerm = $(this).val().trim();

      // Show the clear button if there's input
      if (searchTerm !== "") {
        clearButton.show();
      } else {
        clearButton.hide();
      }
    });

    // Initially disable the search button (since the input is empty on load)
    toggleSearchButton();

    // When the search button is clicked
    searchButton.on("click", function () {
      const searchTerm = searchInput.val().trim();

      // Perform search only if there is a search term
      currentPage = 1; // Reset to page 1 when searching
      performAjaxSearch(searchTerm, currentPage);
    });

    // When the clear button is clicked
    clearButton.on("click", function () {
      // Clear the search input
      searchInput.val("");

      // Hide the clear button again
      clearButton.hide();

      currentPage = 1; // Reset to page 1
      performAjaxSearch("", currentPage); // Reload all profiles (clear search)
    });

    // Event delegation for pagination links
    $(document).on("click", ".pagination a:not(.disabled)", function (e) {
      e.preventDefault();

      // Get page number from the link
      const page = parseInt($(this).attr("data-page"), 10);

      if (page && !isNaN(page)) {
        currentPage = page; // Set current page
        const searchTerm = searchInput.val().trim(); // Get current search term
        performAjaxSearch(searchTerm, currentPage); // Load the selected page
      }
    });
  });
})(jQuery);
