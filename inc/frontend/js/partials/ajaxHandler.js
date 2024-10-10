(function ($) {
  "use strict";

  function submitForm(formData) {
    const errors = window.validateForm(formData);

    if (Object.keys(errors).length === 0) {
      jQuery.ajax({
        url: myPluginAjax.ajax_url,
        method: "POST",
        data: formData,
        success: function (response) {
          if (response.success) {
            // Display success message
            jQuery("#form-message").html(
              '<div class="success">' + response.data.message + "</div>"
            );
          } else {
            // Display error messages
            const errors = response.data.errors;
            let errorMessage = "There were errors:<ul>";
            for (const error of errors) {
              errorMessage += `<li>${error}</li>`;
            }
            errorMessage += "</ul>";
            jQuery("#form-message").html(
              '<div class="error">' + errorMessage + "</div>"
            );
          }
        },
      });
    } else {
      displayErrors(errors);
    }
  }

  function displayErrors(errors) {
    let errorList = "<ul>";
    for (let key in errors) {
      if (errors.hasOwnProperty(key)) {
        errorList += `<li>${errors[key]}</li>`;
      }
    }
    errorList += "</ul>";
    jQuery("#form-message").html(
      `<div class="error-messages">${errorList}</div>`
    );
  }

  window.submitForm = submitForm; // Export to global scope
})(jQuery);
