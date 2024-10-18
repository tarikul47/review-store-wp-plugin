<?php
use Tarikul\PersonsStore\Inc\Database\Database;

get_header();
$db = Database::getInstance();
?>

<div class="tjmk-search-result-wrpper">
    <div class="search-table-wrpper">
        <div class="top-wrpper">
            <!-- Search Box -->
            <div class="search-input-wrpper">
                <img src="<?php echo PLUGIN_NAME_ASSETS_URI . "/images/icons/search-icon.svg" ?>" alt="Search Icon">
                <input type="search" id="profile-search" placeholder="Search profiles...">
            </div>
            <!-- Search button -->
            <button id="search-button">Search</button>
            <!-- Clear button (initially hidden) -->
            <button id="clear-button" style="display: none;">Clear</button>
        </div>

        <!-- Table to Show Search Results -->
        <div class="result-shown-table">
            <div style="overflow-x:auto;">
                <table id="profiles-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Title</th>
                            <th>Type of Employee</th>
                            <th>Department</th>
                            <th>Municipality</th>
                            <th>Rating</th>
                            <th>Buy report</th>
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