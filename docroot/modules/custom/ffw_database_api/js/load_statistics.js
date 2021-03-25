/**
 *
 * @file
 * User popup.
 */
(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.ffwLoadStatistics = {
    attach: function (context, settings) {

      $('.ffw-entity-statistics').once('ffw-entity-statistics').each(function () {
        if (Drupal.behaviors.ffwLoadStatistics.validate($(this))) {
          let nid = $(this).attr('data-statistics-nid');
          $.ajax({
            url: '/load_ffw_statistics/' + nid,
            success: function (response) {
              $('.ffw-entity-statistics[data-statistics-nid="' + nid + '"]').each(function (index, element) {
                Drupal.behaviors.ffwLoadStatistics.replaceHtml(element, response.data);
              });
            }
          });
        } else {
          console.log('There is invalid data attribute: "data-statistics-nid"!');
        }
      });
    },

    validate: function ($element) {
      return !($element.attr('data-statistics-nid') === undefined || $element.attr('data-statistics-nid') === null);
    },

    replaceHtml: function (element, html) {
      $(element).html(html);
      $(element).find('[data-statistics-name]').css('filter', 'none');
    }
  };
})(jQuery, Drupal, drupalSettings);
