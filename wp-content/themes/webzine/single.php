<?php
global $post;

$dir = "/wp-content/themes/webzine/";

$type       = $post->post_type;
$title      = apply_filters('the_title', $post->post_title);
$excerpt    = apply_filters('the_excerpt', $post->post_excerpt);
$content    = apply_filters('the_content', $post->post_content);
$post_date  = date('F j, Y', strtotime($post->post_date_gmt));
$permalink  = get_permalink($post->ID);
$image      = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
$author     = get_userdata($post->post_author);
$avatar     = get_wp_user_avatar_src($post->post_author, 24);
$is_featured = false;
$not_page = true;
$non_featured = true;
if($type == 'featured') {
  $is_featured = true;
  $non_featured = false;
}
if($type == 'page') {
  $not_page = false;
}
if( $image[0] == ''    || $image[0] == null || 
    $excerpt == ''  || $excerpt == null ||
    $title == ''    || $title == null) {
      $featured_post = false;
      $coming_soon = true;
} else {
  $coming_soon = false;
  $post_content = array(
    "featured"  => $is_featured,
    "normal"    => $non_featured,
    "not_page"  => $not_page,
    "type"      => $type,
    "date"      => $post_date,
    "title"     => $title,
    "excerpt"   => $excerpt,
    "content"   => $content,
    "image"     => $image[0],
    "author"    => $author->display_name,
    "author_bio"  => $author->user_description,
    "avatar"    => $avatar,
    "permalink" => $permalink
  );
}

// -- Setup social media tag content
$og = array(
  "title"     => $title,
  "site_name" => get_bloginfo('name'),
  "description" => strip_tags(html_entity_decode($excerpt)),
  "type"        => "article",
  "url"         => $permalink,
  "image"       => $image[0]
);
$tw = array(
  "card"        => "summary",
  "site"        => "@jiboneus",
  "creator"     => "@jiboneus", // -- [TODO] setup a field for author to put in twitter handle
  "title"       => $title,
  "description" => strip_tags(html_entity_decode($excerpt)),
  "image"       => $image[0]

);

$feed = array(
  "rss"         => get_bloginfo('rss_url'),
  "rss2"        => get_bloginfo('rss2_url'),
  "rdf"         => get_bloginfo('rdf_url'),
  "atom"        => get_bloginfo('atom_url')
);

$show_analytics = false;
if($_SERVER['SERVER_NAME'] == 'jiboneus.com') {
  $show_analytics = true;
}

// prepare the view
$data = array(
  "paths"   => array(
    "dir"   => $dir,
    "img"   => $dir."img/"
  ),
  "show_analytics" => $show_analytics,
  "assets" => array(
    "css"         => $dir ."/css/jiboneus.css",
    "modernizr"   => $dir ."/js/vendor/modernizr/modernizr-2.6.2.min.js",
    "jquery"      => $dir ."/js/vendor/jquery/jquery-1.10.1.min.js",
    "bootstrap"   => $dir ."/js/vendor/bootstrap/bootstrap.min.js",
    "js"          => $dir ."/js/jiboneus.js"
  ),
  "og"    => $og,
  "tw"    => $tw,
  "feed"  => $feed,
  "content" => array(
    "window_title"  => $title,
    "post"          => $post_content
  )
);

$m = new Mustache_Engine(array(
  'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/views/layouts', array('extension' => '.html')),
  'partials_loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/views/partials', array('extension' => '.html'))
));

echo $m->render('single', $data);
