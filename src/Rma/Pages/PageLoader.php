<?php

namespace Rma\Pages;

/**
 * Description of Page
 *
 * @author George
 */
class PageLoader
{
    /**
     * Create pages per definitions
     * 
     * @param array $pageDefinitions
     */
    public function pageCreator($pageDefinitions) {
        foreach ($pageDefinitions as $slug => $page) {
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

    /**
     * Create shortcodes from page definitions
     * 
     * @param type $pageDefinitions
     */
    public function shortcodeGenerator($pageDefinitions) {
        foreach ($pageDefinitions as $slug => $page) {
            $code = str_replace(['[', ']'], '', $page['content']);
            if (!shortcode_exists($code)) {
                add_shortcode($code, [$page['class'], $page['function']]);
            }
        }
    }

    /**
     * Update meta values for given page/post id
     * 
     * @param integer $id
     * @param string $meta_key
     * @param string $meta_value
     * @return mixed
     */
    public function updatePostMeta($id, $meta_key, $meta_value) {
        return update_post_meta($id, $meta_key, $meta_value);
    }

}
