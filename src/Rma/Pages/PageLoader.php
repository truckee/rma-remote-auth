<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rma\Pages;

/**
 * Description of Page
 *
 * @author George
 */
class PageLoader
{

    public function pageCreator($page_definitions) {
        foreach ($page_definitions as $slug => $page) {
            // Check that the page doesn't exist already
            $query = new \WP_Query('pagename=' . $slug);
            if (!$query->have_posts()) {
                // Add the page using the data from the array above
                wp_insert_post(
                        array(
                            'post_content' => $page['content'],
                            'post_name' => $slug,
                            'post_title' => $page['title'],
                            'post_status' => 'publish',
                            'post_type' => 'page',
                            'ping_status' => 'closed',
                            'comment_status' => 'closed',
                        )
                );
            }
        }
    }

    public function shortcodeGenerator($page_definitions) {
        foreach ($page_definitions as $slug => $page) {
            $code = str_replace(['[', ']'], '', $page['content']);
            if (!shortcode_exists($code)) {
                add_shortcode($code, [$page['class'], $page['function']]);
            }
        }
    }

}
