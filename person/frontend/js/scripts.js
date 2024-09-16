jQuery(document).ready(function($) {
    $('.single-review').each(function() {
        var $review = $(this);

        // Handle click event on star items
        $review.find('.review-icon .star').on('click', function() {
            var $stars = $review.find('.review-icon .star');
            var selectedValue = $(this).data('value');

            // Remove 'selected' class from all stars
            $stars.removeClass('selected');
            
            // Add 'selected' class to the clicked star and all stars before it
            $stars.each(function() {
                if ($(this).data('value') <= selectedValue) {
                    $(this).addClass('selected');
                }
            });

            // Update the hidden input field with the selected value
            $review.find('input[type="hidden"]').val(selectedValue);
        });
    });
});