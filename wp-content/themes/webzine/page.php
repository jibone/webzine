<?php
global $post;

$dir = "/wp-content/themes/webzine/";

//print_r($post);

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
$non_featured = true;
$post_content = array(
  "featured"  => $is_featured,
  "normal"    => $non_featured,
  "type"      => $type,
  "date"      => $post_date,
  "title"     => $title,
  "excerpt"   => $excerpt,
  "content"   => $content,
  "image"     => $image[0],
  "author"    => $author->display_name,
  "avatar"    => $avatar,
  "permalink" => $permalink
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
