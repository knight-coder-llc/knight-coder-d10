<?php

/**
 * @file
 * Page title settings css styles.
 */

/**
 * Generate page title theme css styles.
 *
 * @param string $theme
 *   Theme machine name.
 * @param string $css
 *   Generated css styles string.
 * @param array $palette
 *   Theme palette array.
 */
function page_title_theme_settings_css($theme, &$css, array $palette) {
  $gutter_container = theme_get_setting('gutter_container', $theme);
  $breadcrumb_align = theme_get_setting('page_title_breadcrumbs_align', $theme);
  if ($breadcrumb_align == 'right') {
    $css .= ".page-title-full-width-container .breadcrumb { right: {$gutter_container}px; left: auto; } \n\n";
  }
  elseif ($breadcrumb_align == 'left') {
    $css .= ".page-title-full-width-container .breadcrumb { left: {$gutter_container}px; right: auto; } \n\n";
  }
  elseif ($breadcrumb_align == 'center') {
    $css .= ".page-title-full-width-container .breadcrumb { left: 0; right: 0;width:100%;text-align:center; } \n\n";
  }

  $css .= "#page-title { text-align:  " . theme_get_setting('page_title_align', $theme) . " } \n\n";

  /*
   * TITLE OPTIONS
   */
  if (theme_get_setting('title_type', $theme)['uppercase']) {
    $css .= <<<EOT
#page-title .page-title {
  text-transform: uppercase;
}

EOT;
  }
  if (theme_get_setting('title_type', $theme)['bold']) {
    $css .= <<<EOT
#page-title .page-title {
  font-weight: bold;
}

EOT;
  }
  if (theme_get_setting('title_type', $theme)['italic']) {
    $css .= <<<EOT
#page-title .page-title {
  font-style: italic;
}

EOT;
  }

  $css .= "#page-title .page-title { height:  " . theme_get_setting('page_title_height', $theme) . "px } \n\n";

  $css .= ".page-title-full-width-container:after { opacity:  " . theme_get_setting('page_title_image_opacity', $theme) . " } \n\n";

  if ($position = theme_get_setting('page_title_image_position')) {
    $position = str_replace('_', ' ', $position);
    $css .= ".page-title-full-width-container:after { background-position: $position; }\n\n";
  }

  if (theme_get_setting('page_title_image_mode') == 'fixed') {
    $position = str_replace('_', ' ', $position);
    $css .= ".page-title-full-width-container:after { background-attachment: fixed; }\n\n";
  }

  if ($bgstyle = theme_get_setting('page_title_image_style')) {
    switch ($bgstyle) {
      case 'no_repeat':
        $css .= ".page-title-full-width-container:after { background-size: auto;background-repeat: no-repeat; }\n\n";
        break;

      case 'repeat':
        $css .= ".page-title-full-width-container:after { background-size: auto; }\n\n";
        break;

      case 'contain':
        $css .= ".page-title-full-width-container:after { background-size: contain; }\n\n";
        break;
    }
  }

  if ($page_title_image_path = theme_get_setting('page_title_image_path', $theme)) {
    $css .= ".page-title-full-width-container:after { background-image: url('" . \Drupal::service('file_url_generator')->generateString($page_title_image_path) . "'); }\n\n";
  }
}
