<?php 
namespace Suggestedـlinksـforـvideos;

class Ajax{

    public function __construct()
    {
        $this->init();
    }
    public function init()
    {
        add_action('wp_ajax_suggested_links_save',[$this,'HandleRequest']);
        add_action('wp_ajax_nopriv_suggested_links_save', [$this,'HandleRequest']);
    }

    public function HandleRequest()
    {
        if(current_user_can('manage_options')){
            check_ajax_referer('suggestions_nonce','nonce');
            $id = filter_var($_POST['post_id'],FILTER_SANITIZE_NUMBER_INT);
            $suggestions = filter_var_array($_POST['suggestions'],FILTER_SANITIZE_STRING);
            update_post_meta($id, 'suggested_links', $suggestions);
        }
        wp_die();
    }
}

new Ajax();