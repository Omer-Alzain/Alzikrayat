<?php
foreach ($photos as $photo) {
    echo '<div class="photo">';
    echo '<img src="' . '/images/uploads/' . $photo['file_name'] . '" alt="' . $photo['title'] . '">';
    echo '<h3>' . $photo['title'] . '</h3>';
    echo '<p>' . $photo['description'] . '</p>';
    echo '</div>';
}