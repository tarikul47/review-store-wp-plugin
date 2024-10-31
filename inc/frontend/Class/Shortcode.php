<?php
namespace Tarikul\PersonsStore\Inc\Frontend\Class;

class Shortcode
{
    public function __construct()
    {
        // Register the shortcode
        add_shortcode('tjmk_search_person', [$this, 'render_search_form']);
    }

    public function render_search_form($atts)
    {
        // Set default attributes
        $atts = shortcode_atts([
            'action' => '/profiles', // Default action URL
        ], $atts, 'search_person');

        // Start output buffering
        ob_start(); ?>

        <div class="tjmk-search-person-content-box">
            <div class="content-title">
                <h2>vem letar du efter?</h2>
            </div>
            <form class="search-person-form" action="<?php echo esc_url($atts['action']); ?>" method="get">
                <div class="name-button-box">
                    <input type="text" name="search_term" placeholder="Enter name..." value="">
                </div>
                <div class="search-button">
                    <input type="submit" value="Search Now">
                </div>
            </form>
        </div>

        <?php
        // Return the buffered content
        return ob_get_clean();
    }
}

