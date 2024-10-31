<?php

?>
<div class="wrap">
    <h1>TJMK Settings</h1>
    <?php settings_errors(); // Display the notices ?>
    <form method="post" action="options.php">
        <?php
        settings_fields('tjmk_options_group');
        do_settings_sections('tjmk_plugin-settings');
        submit_button();
        ?>
    </form>
</div>
<?php


