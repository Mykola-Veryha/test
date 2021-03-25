/**
 * @file
 * Statistics functionality.
 */

(function ($, Drupal, drupalSettings) {
  $(document).ready(() => {
    $.ajax({
      type: 'POST',
      cache: false,
      url: drupalSettings.ffwStatistics.url,
      data: drupalSettings.ffwStatistics.data,
    });
  });
})(jQuery, Drupal, drupalSettings);
