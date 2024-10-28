(function ($) {
  "use strict";

  // Initialize all modules or functionalities
  function init() {
    setupProfileForm();
    //   setupSearchFunctionality();
    // Add any other initializations here
  }

  // Frontend Profile form submission handling
  function setupProfileForm() {
    $(document).on("submit", "#frontend-profile-add", function (e) {
      e.preventDefault();

      // Object to hold form data
      const formData = {};

      // Define required fields
      const data = {
        requiredFields: {
          // Fixed typo from "requiredFieds" to "requiredFields"
          first_name: "First name",
          last_name: "Last name",
          title: "Professional Title",
          email: "Email",
          phone: "Phone Number",
          address: "Address",
          zip_code: "Zip Code",
          city: "City",
          salary: "Salary Per Month",
          employee_type: "Type of Employee",
          region: "Region",
          state: "State",
          country: "Country",
          municipality: "Municipality",
          department: "Department",
        },
        rating: {
          fair: "",
          professional: "",
          response: "",
          communication: "",
          decisions: "",
          recommend: "",
          comments: "",
        },
      };

      // Gather values for each required field
      for (const [fieldId, fieldLabel] of Object.entries(data.requiredFields)) {
        formData[fieldId] = $(`#${fieldId}`).val(); // Get value using field ID
      }

      // Gather values for rating fields
      for (const [fieldId, fieldLabel] of Object.entries(data.rating)) {
        formData[fieldId] = $(`#${fieldId}`).val(); // Get value using field ID
      }

      // Handle hidden inputs, if any
      $("input[type='hidden']").each(function () {
        const name = $(this).attr("name");
        const value = $(this).val();
        formData[name] = value; // Add hidden input value to formData
      });

      // Log formData for debugging purposes (optional)
     console.log("Form Data:", formData);

      // Call the submitForm function from ajaxHandler.js
      window.submitForm(formData);
    });
  }

  // Search functionality handling
  //   function setupSearchFunctionality() {
  //     $(document).on("click", "#search-button", function () {
  //       const searchTerm = $("#search-input").val();
  //       // Implement your search logic here
  //       console.log("Searching for:", searchTerm);
  //       // Example AJAX call for search
  //       $.ajax({
  //         url: "your-search-endpoint-url", // Replace with your search URL
  //         method: "GET",
  //         data: { query: searchTerm },
  //         success: function (response) {
  //           console.log("Search results:", response);
  //           // Handle displaying search results
  //         },
  //         error: function (error) {
  //           console.error("Search error", error);
  //         },
  //       });
  //     });
  //   }

  // Run the initialization function when the document is ready
  $(document).ready(function () {
    init();
  });
})(jQuery);
