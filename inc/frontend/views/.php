<?php
get_header();
?>
<div class="tjmk-search-profile-content-box">
    <div class="content-title">
        <h2>vem letar du efter?</h2>
    </div>
    <form class="search-profile-form" action="/profiles" method="get">
        <!-- Change action to your profile page URL -->
        <div class="name-button-box">
            <input type="text" name="search_term" placeholder="Enter name..." value="">
        </div>
        <div class="search-button">
            <input type="submit" value="Search Now">
        </div>
    </form>
</div>
<?php
get_footer();