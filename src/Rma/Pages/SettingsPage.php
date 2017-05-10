<?php

namespace Rma\Pages;

use Rma\Pages\WpSubPage;

class SettingsPage extends WpSubPage
{

    public function render_settings_page() {
        $option_group = $this->properties['option_group'];
        $page = $this->properties['menu_slug'];
        $submit = $this->properties['submit_label'];
        echo $this->properties['page_title'];
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
                <?php
                settings_fields($option_group);
                do_settings_sections($page);
                submit_button($submit);
                ?>
            </form>
        </div>
        <?php
    }
}
