<?php
    echo '<div class="photo-details">';
    echo '<img src="' . '/images/uploads/' . $photo['file_name'] . '" alt="' . $photo['title'] . '">';
    echo '<h3>' . $photo['title'] . '</h3>';
    echo '<p>' . $photo['description'] . '</p>';
    echo '</div>';