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

    var $reviefrom = $("#reviewform"); // Updated to match your form ID

    $reviefrom.on("submit", function (e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        url: myPluginAjax.ajax_url,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          //   $importResults.show(); // Show progress container
          //  $importResults.html("Uploading file...");
          //  $importProgressBar.css("width", "0%").attr("aria-valuenow", 0);
          //  $importProgressText.text("Starting upload...");
        },
        success: function (response) {
          console.log("AJAX Response:", response); // Log the response for debugging

          if (response.success) {
            $("#review-message").text(response.data.message);
            $("#user-review-form").show(); // Show the message element
            $("#singlereview").hide(); // Hide the submit button
          } else {
            $("#review-message").text(response.data.message);
            $("#user-review-form").show(); // Show the message element
          }

          // Optional: Refresh the page or update the UI
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("AJAX Error:", textStatus, errorThrown); // Log AJAX errors
          $('#review-message').text('An error occurred while submitting your review.');
          $('#user-review-form').show(); // Show the message element
        },
      });
    });
  });
})(jQuery);
