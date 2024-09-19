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

      // Hide all meta rows
      $(".review-meta-row")
        .not("#meta-" + reviewId)
        .hide();

      // Toggle the corresponding meta row
      var $metaRow = $("#meta-" + reviewId);
      if ($metaRow.length) {
        $metaRow.toggle(); // Toggle the visibility of the specific meta row
      } else {
        console.log("Meta row with ID 'meta-" + reviewId + "' not found.");
      }
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
      var profileId = $(this).data("profile-id");

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
            profile_id: profileId,
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

    /**
     * Bulk person import functionality.
     *
     * @since 1.0.0
     */
    var $importForm = $("#urp-import-form");
    var $importResults = $("#import-progress-container");
    var $importProgressBar = $("#import-progress-bar");
    var $importProgressText = $("#import-progress-text");

    console.log($importResults);
    console.log($importProgressBar);
    console.log($importProgressText);

    $importForm.on("submit", function (e) {
      e.preventDefault();

      var formData = new FormData(this);
      formData.append("action", "urp_handle_file_upload_async");
      formData.append("security", $('input[name="security"]').val()); // Get nonce from form

      $.ajax({
        url: myPluginAjax.ajax_url,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          $importResults.show(); // Show progress container
          $importResults.html("Uploading file...");
          $importProgressBar.css("width", "0%").attr("aria-valuenow", 0);
          $importProgressText.text("Starting upload...");
        },
        success: function (response) {
          console.log("AJAX Response:", response); // Log the response for debugging

          if (response.success) {
            console.log("processChunksAsync Response:", response.success); // Log the response for debugging
            processChunksAsync();
          } else {
            $importResults.html("Error: " + response.data);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("AJAX Error:", textStatus, errorThrown); // Log AJAX errors
          $importResults.html("An error occurred while uploading the file.");
        },
      });
    });

    function processChunksAsync() {
      $.ajax({
        url: myPluginAjax.ajax_url,
        method: "POST",
        data: {
          action: "urp_process_chunks_async",
          security: $('input[name="security"]').val(), // Get nonce from form
        },
        success: function (response) {
          console.log("Chunks Response:", response); // Log the response for debugging

          if (response.success) {
            var percentCompleted = Math.min(
              100,
              (response.data.completed / response.data.total_chunks) * 100
            );
            $importProgressBar
              .css("width", percentCompleted + "%")
              .attr("aria-valuenow", percentCompleted);
            $importProgressText.text(
              "Chunks processed. Remaining: " + response.data.remaining
            );

            if (response.data.remaining > 0) {
              setTimeout(processChunksAsync, 1000); // Adjust delay as needed
            } else {
              $importResults.html("Import completed successfully.");
            }
          } else {
            $importResults.html("Error: " + response.data);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Chunks Processing Error:", textStatus, errorThrown); // Log AJAX errors
          $importResults.html("An error occurred while processing chunks.");
        },
      });
    }

    /**
     * Profile delete
     */
    $(".delete-profile-btn").on("click", function (e) {
      e.preventDefault();

      if (!confirm("Are you sure you want to delete this user?")) {
        return;
      }

      var profileId = $(this).data("profile-id");
      var row = $(this).closest("tr");

      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "delete_profile",
          profile_id: profileId,
        },
        success: function (response) {
          if (response.success) {
            row.css("background-color", "red").fadeOut(500, function () {
              $(this).remove();
            });
            alert(response.data.message);
          } else {
            alert(response.data.message);
          }
        },
        error: function () {
          alert("An error occurred while deleting the profile.");
        },
      });
    });

    /**
     * Bulk Person delete
     */
    $("#bulk-action-form").on("submit", function (event) {
      event.preventDefault(); // Prevent the default form submission

      var action = $("#bulk-action-selector-top").val();
      if (action === "delete") {
        // Collect selected profile IDs
        var selectedIds = $('input[name="profile_ids[]"]:checked')
          .map(function () {
            return $(this).val();
          })
          .get();

        if (selectedIds.length === 0) {
          alert("Please select at least one profile to delete.");
          return;
        }

        if (confirm("Are you sure you want to delete the selected profiles?")) {
          $.ajax({
            url: myPluginAjax.ajax_url,
            type: "POST",
            data: {
              action: "urp_bulk_delete_profiles",
              security: myPluginAjax.bulk_delete_nonce,
              profile_ids: selectedIds,
            },
            success: function (response) {
              if (response.success) {
                // Reload the page to reflect changes
                location.reload();
              } else {
                alert(response.data.message);
              }
            },
            error: function (xhr, status, error) {
              console.error("AJAX Error:", status, error);
              alert("An error occurred while processing your request.");
            },
          });
        }
      } else {
        alert("Invalid action selected.");
      }
    });
  }); // document read function
})(jQuery);
