(function ($) {
  $(function() {
    var links = new BookReview.Links($);

    links.init();

    $(".color-picker").wpColorPicker();

    // Event handlers
    registerRatingHandlers();
    registerCustomFieldHandlers();

    showRatingImages();

    $("#fields").sortable({
      "placeholder": "ui-sortable-placeholder"
    });

    /* Register event handlers for elements on the Rating Images tab. */
    function registerRatingHandlers() {
      $("#book_review_rating_default").change(showRatingImages);
    }

    /* Register event handlers for elements on the Custom Fields tab. */
    function registerCustomFieldHandlers() {
      $(".add-field").click(addField);
    }

    /* Show or hide Rating Image URLs. */
    function showRatingImages() {
      if ($("#book_review_rating_default").attr("checked")) {
        $(".rating").hide();
      }
      else {
        $(".rating").show();
      }
    }

    /* Add a new item to the Custom Fields list. */
    function addField() {
      var id = BookReview.Utils.getUniqueId(),
        $listItem = $("<li/>").attr({ "class": "field" }),
        $label = $("<input>").attr({
          "name": "book_review_fields[fields][" + id + "][label]",
          "type": "text",
          "class": "label regular-text",
          "placeholder": custom_fields.placeholder_text
        }),
        $icon = $("<div>").attr({ "class": "dashicons dashicons-sort" });

      $listItem.append($label).append($icon);
      $("#fields").append($listItem);
    }
  });
})(jQuery);