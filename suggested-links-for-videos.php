<?php

/**
 * Plugin Name:       Suggested links for videos
 * Plugin URI:        https://clazist.com
 * Description:       Show suggested links above videos
 * Version:           1.1
 * Author:            Clazist
 * Author URI:        https://github.com/clazist
 * Text Domain:       clazist
 * Domain Path:       /languages
 * License:           GPL v2 or later
 */

class Suggestedـlinksـforـvideos
{
    public $version = 1.0;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {   
        add_action( 'plugins_loaded', [$this,'load_text_domain']);
        require_once plugin_dir_path(__FILE__).'app/ajax.php';
        require_once plugin_dir_path(__FILE__).'inc/customizer.php';
        $this->hooks();
    }

    public function loadAdminAssets()
    {
        wp_enqueue_script('suggested_links', plugin_dir_url(__FILE__) . 'js/admin/suggestions.js', ['jquery'], $this->version, true);
        wp_enqueue_style('suggested_links', plugin_dir_url(__FILE__) . 'css/admin/suggestions.css', [], $this->version);
        wp_enqueue_style('rtl_suggested_links',plugin_dir_url(__FILE__). 'css/admin/suggestions.css',[],$this->version);
        wp_style_add_data( 'rtl_suggested_links', 'rtl', 'replace');
        wp_localize_script('suggested_links','suggested_links_obj',[
            'ajax_address' => admin_url(). 'admin-ajax.php',
            'nonce'        => wp_create_nonce('suggestions_nonce'),
            'title_here'   => __('Title here','clazist'),
            'save'         => __('Save','clazist'),
            'saving'       => __('Saving...','clazist'),
            'error'        => __('Error!','clazist'),
            'saved'        => __('Saved','clazist')
        ]);
    }

    public function loadFrontendAssets()
    {
        wp_enqueue_script('frontend_suggested_links', plugin_dir_url(__FILE__) . 'js/frontend/suggestions.js', ['jquery'], $this->version, true);
        wp_enqueue_style('frontend_suggested_links', plugin_dir_url(__FILE__) . 'css/frontend/suggestions.css', [], $this->version);
        wp_enqueue_style('rtl_suggested_links',plugin_dir_url(__FILE__). 'css/frontend/suggestions.css',[],$this->version);
        wp_localize_script('frontend_suggested_links','suggestions_obj',[
            'info_image'      =>    get_theme_mod('suggested_links_for_videos_icon') ? '<img src="'.get_theme_mod('suggested_links_for_videos_icon').'" width="24" height="24"/>' : '<?xml version="1.0" <svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="24px" height="24px">    <path d="M 12 2 C 6.4889971 2 2 6.4889971 2 12 C 2 17.511003 6.4889971 22 12 22 C 17.511003 22 22 17.511003 22 12 C 22 6.4889971 17.511003 2 12 2 z M 12 4 C 16.430123 4 20 7.5698774 20 12 C 20 16.430123 16.430123 20 12 20 C 7.5698774 20 4 16.430123 4 12 C 4 7.5698774 7.5698774 4 12 4 z M 11 7 L 11 9 L 13 9 L 13 7 L 11 7 z M 11 11 L 11 17 L 13 17 L 13 11 L 11 11 z"/></svg>',
            'box_direction'   =>    get_theme_mod('suggested_links_for_videos_direction') ? get_theme_mod('suggested_links_for_videos_direction') : 'left',
            'text_align'      =>    get_theme_mod('suggested_links_for_videos_text_align') ? get_theme_mod('suggested_links_for_videos_text_align') : 'left',
            'icon_direction'  =>    get_theme_mod('suggested_links_for_videos_icon_direction') ? get_theme_mod('suggested_links_for_videos_icon_direction') : 'left'
        ]);
    }

    public function hooks()
    {
        add_filter('attachment_fields_to_edit', [$this, 'suggested_links_field'], 10, 2);
        add_filter('attachment_fields_to_save', function(){return;}, 10, 2);
        add_filter('the_content', [$this, 'handleFrontendScript']);
        add_action('admin_enqueue_scripts', [$this, 'loadAdminAssets']);
        add_action('wp_enqueue_scripts', [$this, 'loadFrontendAssets']);
    }

    public function suggested_links_field ($form_fields, $post)
    {
        $form_fields['suggested_links'] = ['tr' => $this->handleHtmlForm($post)];
        return $form_fields;
    }

    public function handleHtmlForm($post)
    {
        $times = get_post_meta($post->ID, 'suggested_links', true);
        $html = '
        <tr class="suggested_links_table"><td>
        <button class="button add-suggestion-link">'.__('Add suggested link','clazist').'</button>
        <div class="suggested-links-app" data-id="' . $post->ID . '">
        ';
        if (is_array($times)) {
            foreach ($times as $key => $value) {
                $html .= '
                <div class="suggestion-box" data-count="' . $key . '">
                    <div contenteditable data-placeholder="'.__('Title here','clazist').'" data-name="title">' . $value['title'] . '</div>
                    <div contenteditable data-placeholder="https://yoursite.com" data-name="url">' . $value['url'] . '</div>
                    <div contenteditable data-placeholder="00:00:00" data-name="time">'.$value['time'].'</div>
                    <span><svg enable-background="new 0 0 24 24" height="24px" id="Layer_1" version="1.1" viewBox="0 0 24 24" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M22.245,4.015c0.313,0.313,0.313,0.826,0,1.139l-6.276,6.27c-0.313,0.312-0.313,0.826,0,1.14l6.273,6.272  c0.313,0.313,0.313,0.826,0,1.14l-2.285,2.277c-0.314,0.312-0.828,0.312-1.142,0l-6.271-6.271c-0.313-0.313-0.828-0.313-1.141,0  l-6.276,6.267c-0.313,0.313-0.828,0.313-1.141,0l-2.282-2.28c-0.313-0.313-0.313-0.826,0-1.14l6.278-6.269  c0.313-0.312,0.313-0.826,0-1.14L1.709,5.147c-0.314-0.313-0.314-0.827,0-1.14l2.284-2.278C4.308,1.417,4.821,1.417,5.135,1.73  L11.405,8c0.314,0.314,0.828,0.314,1.141,0.001l6.276-6.267c0.312-0.312,0.826-0.312,1.141,0L22.245,4.015z"/></svg></span>
                </div>
                ';
            }
        }
        $html .= '
        </div>
        <button id="send_suggestion_data" class="button-primary" data-post_id="' . $post->ID . '">'.__('Save','clazist').'</button>
        </td></tr>
        ';
        return $html;
    }

    public function handleFrontend()
    {
        $query = get_post(get_the_ID());
        $content = $query->post_content;
        preg_match_all('/<video.*?src="(.*?)"/', $content, $matches);
        $video_link = $matches[1];
        $media = [];
        $suggestions = [];
        foreach ($video_link as $key => $value) {
            $media[] = attachment_url_to_postid($value);
        }
        foreach ($media as $key => $media_id) {
            $suggestions[] = get_post_meta($media_id, 'suggested_links', true);
            foreach ($suggestions[$key] as $key1 => $value) {
                $suggestions[$key][$key1]['video_link'] = wp_get_attachment_url($media_id);
            }
        }
        return wp_json_encode($suggestions);
    }

    public function handleFrontendScript($content)
    {
        if (is_single()) {
            $codes = $this->handleFrontend();
            $content .= ''.$this->render_customizer_css().'<script>var suggested_links_obj = ' . $codes . '</script>';
        }
        return $content;
    }

    public function render_customizer_css()
    {
        ob_start();
        include_once plugin_dir_path(__FILE__).'inc/style.php';
        $styles = ob_get_clean();
        return $styles;
    }

    public function load_text_domain() {
        load_plugin_textdomain( 'clazist', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    }
}
new Suggestedـlinksـforـvideos();
