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
    //  formData.append("action", "urp_handle_file_upload_async");
   //   formData.append("none", $('input[name="security"]').val()); // Get nonce from form

      // Array including ratings and comments
      // var formDataArray = [
      //   { name: "fair", value: $("#fair").val() },
      //   { name: "professional", value: $("#professional").val() },
      //   { name: "response", value: $("#response").val() },
      //   { name: "communication", value: $("#communication").val() },
      //   { name: "decisions", value: $("#decisions").val() },
      //   { name: "recommend", value: $("#recommend").val() },
      //   { name: "comments", value: $("#comments").val() }, // Include comments in the array
      // ];

      // Append each element of the array to the FormData object
    //   formDataArray.forEach(function (item) {
    //  //   formData.append(item.name, item.value);
    //   });

    //  console.log("FormData with array including comments:", formData);

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
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("AJAX Error:", textStatus, errorThrown); // Log AJAX errors
       //   $importResults.html("An error occurred while uploading the file.");
        },
      });
    });
  });
})(jQuery);
