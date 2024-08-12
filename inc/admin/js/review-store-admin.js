(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
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
   * The file is enqueued from inc/admin/class-admin.php.
   */
  //alert("ggg");
  // Wait until the DOM is fully loaded
  $(document).ready(function () {
    console.log("DOM ready");

    // Click event handler for view-details-link
    $(".view-details-link").on("click", function (e) {
      e.preventDefault();
      console.log("View Details link clicked");

      // Get the review ID from the data attribute
      var reviewId = $(this).data("review-id");
      console.log("Review ID:", reviewId);

      // Toggle the corresponding meta row
      $("#meta-" + reviewId).toggle();
    });
  });
})(jQuery);
