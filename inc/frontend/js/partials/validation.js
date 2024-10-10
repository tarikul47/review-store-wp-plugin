(function ($) {
  "use strict";

  // Export to global scope
  window.validateForm = function validateForm(data) {
    let errors = {};

    // Object to hold form data
    const formData = {};

    // Define required fields
    const requiredFields = {
      first_name: "First name",
      last_name: "Last name",
      title: "Professional Title",
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
    };

    // Check for empty required fields
    for (const [fieldKey, fieldLabel] of Object.entries(requiredFields)) {
      if (!data[fieldKey]) {
        errors[fieldKey] = `${fieldLabel} is required.`;
      }
    }

    return errors;
  };
})(jQuery);
