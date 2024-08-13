(function ($) {
  "use strict";

  /**
   * This function encapsulates all of the admin-facing JavaScript logic
   * for handling user interactions on the review management page.
   * It is executed when the DOM is fully loaded.
   *
   * @since 1.0.0
   */

  $(document).ready(function () {
    console.log("DOM ready");

    /**
     * Click event handler for the 'View Details' link.
     * Toggles the visibility of the review meta data row.
     *
     * @since 1.0.0
     */
    $(".view-details-link").on("click", function (e) {
      e.preventDefault();
      console.log("View Details link clicked");

      // Get the review ID from the data attribute
      var reviewId = $(this).data("review-id");
      console.log("Review ID:", reviewId);

      // Hide all open meta rows before showing the selected one
      $(".review-meta-row").hide();

      // Toggle the corresponding meta row
      $("#meta-" + reviewId).toggle();
    });

    /**
     * Click event handler for the 'Approve' and 'Reject' buttons.
     * Sends an AJAX request to the server to approve or reject a review.
     *
     * @since 1.0.0
     */
    $(".approve_reject").on("click", function (e) {
      e.preventDefault();

      // Determine the action (approve or reject) based on the button text
      var action = $(this).text().toLowerCase(); // 'approve' or 'reject'
      var reviewId = $(this).data("review-id");

      // Confirm action
      var confirmationMessage =
        action === "approve"
          ? "Are you sure you want to approve this review?"
          : "Are you sure you want to reject this review?";

          
      if (confirm(confirmationMessage)) {
        // AJAX request to approve or reject the review
        $.ajax({
          url: myPluginAjax.ajax_url,
          type: "POST",
          data: {
            action: action + "_review",
            review_id: reviewId,
            security: myPluginAjax.nonce,
          },
          success: function (response) {
            if (response.success) {
              alert(response.data.message);
              console.log(
                "Review ID " + reviewId + " " + action + "d successfully."
              );
              location.reload(); // Refresh the page to reflect changes
            } else {
              alert(response.data.message);
              console.log(
                "[ERROR] Failed to " + action + " Review ID " + reviewId + "."
              );
            }
          },
          error: function (xhr, status, error) {
            console.log("[ERROR] AJAX request failed: " + error);
          },
        });
      }
    });
  });
})(jQuery);
