<?php
// For URL field
$video_url = get_field('month'); // Replace 'video_url' with your ACF field name
if ($video_url) {
    echo '<iframe src="' . esc_url($video_url) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
}

// For File field
$video_file = get_field('month'); // Replace 'video_file' with your ACF field name
if ($video_file) {
    echo '<video autoplay muted playsinline loop >
              <source src="' . esc_url($video_file['url']) . '" type="' . $video_file['mime_type'] . '">
              Your browser does not support the video tag.
          </video>';
}
?>
