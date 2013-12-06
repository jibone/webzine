<?php

require 'vendor/autoload.php';

//$dir = get_bloginfo("template_url");
$dir = "/wp-content/themes/webzine/";


//echo "<pre>";
$args = array(
  'post_type'       => 'featured',
  'post_per_page'   => '1'
);
$results = new WP_Query($args);
$results_post = $results->get_posts();

$title      = apply_filters('the_title', $results_post[0]->post_title);
$excerpt    = apply_filters('the_excerpt', $results_post[0]->post_excerpt);
$content    = apply_filters('the_content', $results_post[0]->post_content);
$post_date  = date('F j, Y', strtotime($results_post[0]->post_date_gmt));
$permalink  = get_permalink($results_post[0]->ID);
$image      = wp_get_attachment_image_src(get_post_thumbnail_id($results_post[0]->ID), 'large');
$author     = get_userdata($results_post[0]->post_author);
$avatar     = get_wp_user_avatar_src($results_post[0]->post_author, 24);
if( $image[0] == ''    || $image[0] == null || 
    $excerpt == ''  || $excerpt == null ||
    $title == ''    || $title == null) {
      $featured_post = false;
      $coming_soon = true;
} else {
  $coming_soon = false;
  $featured_post = array(
    "date"      => $post_date,
    "title"     => $title,
    "excerpt"   => $excerpt,
    "image"     => $image[0],
    "author"    => $author->display_name,
    "avatar"    => $avatar,
    "permalink" => $permalink
  );
}

// -- get articles, reviews and interviews
$post_count = '10';
$args = array(
  'post_type'     => array('post', 'reviews', 'interviews'),
  'post_per_page' => $post_count
);
$results = new WP_Query($args);
$results_post = $results->get_posts();

//print_r($results_post);
$content_post = array();
$c = count($results_post);
for($i = 0; $i < $c; $i++) {
  $type       = $results_post[$i]->post_type;
  $title      = apply_filters('the_title', $results_post[$i]->post_title);
  $excerpt    = apply_filters('the_excerpt', $results_post[$i]->post_excerpt);
  $content    = apply_filters('the_content', $results_post[$i]->post_content);
  $post_date  = date('l, F j, Y', strtotime($results_post[$i]->post_date_gmt));
  $permalink  = get_permalink($results_post[$i]->ID);
  $image      = wp_get_attachment_image_src(get_post_thumbnail_id($results_post[$i]->ID), 'large');
  $author     = get_userdata($results_post[$i]->post_author);
  $avatar     = get_wp_user_avatar_src($results_post[$i]->post_author, 24);
  // all post must have excerpt and cover image
  if($excerpt != '' && $image != false) {
    $content = array(
      "type" => $type,
      "date"      => $post_date,
      "title"     => $title,
      "excerpt"   => $excerpt,
      "image"     => $image[0],
      "author"    => $author->display_name,
      "avatar"    => $avatar,
      "permalink" => $permalink
    );
    array_push($content_post, $content);
  }
}

// -- get news stuff
$news_count = '30';
$args = array(
  'post_type'       => 'news',
  'post_per_page'   => $news_count
);
$results = new WP_Query($args);
$results_post = $results->get_posts();
$news_post = array();
$c = count($results_post);
for($i = 0; $i < $c; $i++) {
  $custom     = get_post_custom($results_post[$i]->ID);
  $src_name   = $custom['news_source_name'][0];
  $src_link   = $custom['news_source_link'][0];
  $type       = $results_post[$i]->post_type;
  $title      = apply_filters('the_title', $results_post[$i]->post_title);
  $excerpt    = apply_filters('the_excerpt', $results_post[$i]->post_excerpt);
  $content    = apply_filters('the_content', $results_post[$i]->post_content);
  $post_date  = date('l, F j, Y', strtotime($results_post[$i]->post_date_gmt));
  $permalink  = get_permalink($results_post[$i]->ID);
  $image      = wp_get_attachment_image_src(get_post_thumbnail_id($results_post[$i]->ID), 'large');
  $author     = get_userdata($results_post[$i]->post_author);
  $avatar     = get_wp_user_avatar_src($results_post[$i]->post_author, 24);
  // all post must have excerpt and cover image
  if($title != '' && $content != '') {
    $content = array(
      "type"      => $type,
      "date"      => $post_date,
      "title"     => $title,
      "content"   => $content,
      "src_name"  => $src_name,
      "src_link"  => $src_link,
      "author"    => $author->display_name,
      "avatar"    => $avatar,
      "permalink" => $permalink
    );
    array_push($news_post, $content);
  }
}

//print_r($news_post);

//echo "</pre>";

// -- Setup social media tag content
$og = array(
  "title"       => get_bloginfo('name'),
  "site_name"   => get_bloginfo('name'),
  "description" => get_bloginfo('description'),
  "type"        => "website",
  "url"         => get_bloginfo('url'),
  "image"       => $dir."img/logo.png"
);
$tw = array(
  "card"        => "summary",
  "site"        => "@jiboneus",
  "creator"     => "@jiboneus",
  "title"       => get_bloginfo('name'),
  "description" => get_bloginfo('description'),
  "image"       => $dir."img/logo.png"
);

// -- Only show analytics when in live server
$show_analytics = false;
if($_SERVER['SERVER_NAME'] == 'jiboneus.com') {
  $show_analytics = true;
}

$data = array(
  "paths"   => array(
    "dir" => $dir,
    "img" => $dir."img/"
  ),
  "show_analytics" => $show_analytics,
  "assets" => array(
    "css"         => $dir ."/css/jiboneus.css",
    "modernizr"   => $dir ."/js/vendor/modernizr/modernizr-2.6.2.min.js",
    "jquery"      => $dir ."/js/vendor/jquery/jquery-1.10.1.min.js",
    "bootstrap"   => $dir ."/js/vendor/bootstrap/bootstrap.min.js",
    "js"          => $dir ."/js/jiboneus.js"
  ),
  "og" => $og,
  "tw" => $tw,
  "content" => array(
    "window_title"  => "Jiboneus Webzine",
    "coming_soon"   => $coming_soon,
    "featured_post" => $featured_post,
    "content_post"  => $content_post,
    "news_post"     => $news_post
  )
);

$m = new Mustache_Engine(array(
  'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/views/layouts', array('extension' => '.html')),
  'partials_loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/views/partials', array('extension' => '.html'))
));

echo $m->render('home', $data);
