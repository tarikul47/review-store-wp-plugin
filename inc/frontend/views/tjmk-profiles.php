<?php
use Tarikul\TJMK\Inc\Database\Database;

get_header();
$db = Database::getInstance();
?>

<div class="tjmk-search-result-wrpper">
    <div class="search-table-wrpper">
        <div class="top-wrpper">
            <!-- Search Box -->
            <div class="search-input-wrpper">
                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . "/images/icons/search-icon.svg" ?>" alt="Search Icon">
                <input type="search" id="profile-search" placeholder="<?php esc_html_e('Search profiles...', 'tjmk'); ?>">
            </div>
            <!-- Search button -->
            <button id="search-button"><?php esc_html_e('Search', 'tjmk'); ?></button>
            <!-- Clear button (initially hidden) -->
            <button id="clear-button" style="display: none;"><?php esc_html_e('Clear', 'tjmk'); ?></button>
        </div>

        <!-- Table to Show Search Results -->
        <div class="result-shown-table">
            <div style="overflow-x:auto;">
                <table id="profiles-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('First Name', 'tjmk'); ?></th>
                            <th><?php esc_html_e('Last Name', 'tjmk'); ?></th>
                            <th><?php esc_html_e('Title', 'tjmk'); ?></th>
                            <th><?php esc_html_e('Type of Employee', 'tjmk'); ?></th>
                            <th><?php esc_html_e('Department', 'tjmk'); ?></th>
                            <th><?php esc_html_e('Municipality', 'tjmk'); ?></th>
                            <th><?php esc_html_e('Rating', 'tjmk'); ?></th>
                            <th><?php esc_html_e('Buy report', 'tjmk'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="profile-list">
                        <!-- Profiles will be loaded here dynamically via AJAX -->
                    </tbody>
                </table>
                <div class="pagination">
                    <!-- Pagination links will be added dynamically here -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>