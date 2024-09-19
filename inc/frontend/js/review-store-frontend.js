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
  });
})(jQuery);