<?php
/*
Plugin Name: Webzine
Description: Custom controls for webzine.
Author: J.Shamsul (@jibone)
Author: URI: http://jshamsul.com
 */

require 'vendor/autoload.php';

// -----------------------------------------------------
// -- Base setup ---------------------------------------
add_action('admin_init', 'webzine_plugin_css');
if(!function_exists('webzine_plugin_css')) {
  function webzine_plugin_css() {
    wp_enqueue_style(
      'webzine-plugin-css', 
      plugins_url('css/webzine-plugin.css', __FILE__)
    );
  }
}
// -----------------------------------------------------
// -- Create Custom Post Type --------------------------
add_action('init', 'webzine_custom_post');
if(!function_exists('webzine_custom_post')) {
  function webzine_custom_post() {
    
    // -- custom post type for news item
    /*
    $news_labels = array(
      'name'                => __('News Items'),
      'singular_name'       => __('News Item'),
      'add_new'             => __('Add New'),
      'add_new_item'        => __('Add New News Item'),
      'edit_item'           => __('Edit News Item'),
      'new_item'            => __('New News Item'),
      'all_items'           => __('All News Items'),
      'view_item'           => __('View News Item'),
      'search_items'        => __('Search News Items'),
      'not_found'           => __('No news item found'),
      'not_found_in_trash'  => __('No news items found in the trash'),
      'parent_item_colon'   => '',
      'menu_name'           => 'News Items'
    );

    $news_args = array(
      'labels'              => $news_labels,
      'description'         => "News Items are short posts.",
      'public'              => true,
      'menu_position'       => 5,
      'supports'            => array('title', 'editor'),
      'has_archive'         => true,
      'taxonomies'          => array('post_tag', 'category')
    );

    register_post_type('news', $news_args);
    */

    // -- custom post type for featured post
    $featured_post_labels = array(
      'name'                => __('Featured Posts'),
      'singular_name'       => __('Featured Post'),
      'add_new'             => __('Add New'),
      'add_new_item'        => __('Add New Featured Post'),
      'edit_item'           => __('Edit Featured Post'),
      'new_item'            => __('New Featured Post'),
      'all_item'            => __('All Featured Post'),
      'view_item'           => __('View Featured Post'),
      'search_items'        => __('Search Featured Posts'),
      'not_found'           => __('No featured post found'),
      'not_found_in_trash'  => __('No featured post found in the trash'),
      'parent_item_colon'   => '',
      'menu_name'           => 'Featured Posts'
    );
    
    $featured_post_args = array(
      'labels'              => $featured_post_labels,
      'description'         => "Featured Post are weekly cover stories.",
      'public'              => true,
      'menu_position'       => 5,
      'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
      'has_archive'         => true,
      'taxonomies'          => array('post_tag', 'category')
    );

    register_post_type('featured', $featured_post_args);

    // -- custom post for reviews
    $review_labels = array(
      'name'                => __('Reviews'),
      'singular_name'       => __('Review'),
      'add_new'             => __('Add New'),
      'add_new_item'        => __('Add New Review'),
      'edit_item'           => __('Edit Review'),
      'new_item'            => __('New Review'),
      'all_item'            => __('All Reviews'),
      'view_item'           => __('View Reviews'),
      'search_items'        => __('Search Reviews'),
      'not_found'           => __('No review found'),
      'not_found_in_trash'  => __('No review found in the trash'),
      'parent_item_colon'   => '',
      'menu_name'           => 'Reviews'
    );

    $review_args = array(
      'labels'              => $review_labels,
      'description'         => "Reviews for music albums, films, books, and other stuff",
      'public'              => true,
      'menu_position'       => 5,
      'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
      'has_archive'         => true,
      'taxonomies'          => array('post_tag', 'category')
    );

    register_post_type('reviews', $review_args);

    // -- custom post for interviews
    $interview_labels = array(
      'name'                => __('Interviews'),
      'singular_name'       => __('Interview'),
      'add_new'             => __('Add New'),
      'add_new_item'        => __('Add New Interview'),
      'edit_item'           => __('Edit Interview'),
      'new_item'            => __('New Interview'),
      'all_item'            => __('All Interviews'),
      'view_item'           => __('View Interviews'),
      'search_items'        => __('Search Interviews'),
      'not_found'           => __('No review found'),
      'not_found_in_trash'  => __('No review found in the trash'),
      'parent_item_colon'   => '',
      'menu_name'           => 'Interviews'
    );

    $interview_args = array(
      'labels'              => $interview_labels,
      'description'         => "Interviews with makers, creators, and doers",
      'public'              => true,
      'menu_position'       => 5,
      'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
      'has_archive'         => true,
      'taxonomies'          => array('post_tag', 'category')
    );

    register_post_type('interviews', $interview_args);

  }
}

// -----------------------------------------------------
// -- Create Meta Box for Custom Post Type: News item --
/*
add_action('add_meta_boxes', 'webzine_news_item_source');
if(!function_exists('webzine_news_item_source')) {
  function webzine_news_item_source() {
    add_meta_box(
      'webzine_news_item_source',
      __('News Item Source'),
      'webzine_news_item_box_content',
      'news',
      'normal',
      'high'
    );
  }
}
if(!function_exists('webzine_news_item_box_content')) {
  function webzine_news_item_box_content() {
    wp_nonce_field(plugin_basename(__FILE__), 'webzine_news_item_box_content_nonce');
    
    $html = file_get_contents(__DIR__ .'/views/news_source_input.html');
    $m = new Mustache_Engine;

    global $post;
    $custom = get_post_custom($post->ID);
    $data = array();
    if(!empty($custom)) {
      $data = array(
        "news_source_name" => $custom["news_source_name"][0],
        "news_source_link" => $custom["news_source_link"][0]
      );
    }

    echo $m->render($html, $data);
  }
}
add_action('save_post', 'webzine_news_item_box_save');
if(!function_exists('webzine_news_item_box_save')) {
  function webzine_news_item_box_save() {
    global $post;

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return;

    if(!wp_verify_nonce($_POST['webzine_news_item_box_content_nonce'], plugin_basename(__FILE__)))
      return;

    if('page' == $_post['post_type']) {
      if(!current_user_can('edit_page', $post->ID))
        return;
    } else {
      if(!current_user_can('edit_post', $post->ID))
        return;
    }

    $source_name = $_POST["news_source_name"];
    $source_link = $_POST["news_source_link"];
    update_post_meta($post->ID, 'news_source_name', $source_name);
    update_post_meta($post->ID, 'news_source_link', $source_link);
  }
}
*/
