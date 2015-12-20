(function ($) {
  $(document).ready(function($) {
    $(".color-picker").wpColorPicker();

    // Add event handlers.
    $("#book_review_rating_default").change(showRatingImages);
    $(".add-link").click(addLink);
    $(".add-field").click(addField);

    showRatingImages();

    $("#fields").sortable({
      "placeholder": "ui-sortable-placeholder"
    });
  });

  /* Show or hide Rating Image URLs. */
  function showRatingImages() {
    if ($("#book_review_rating_default").attr("checked")) {
      $(".rating").hide();
    }
    else {
      $(".rating").show();
    }
  }

  /* Add a row to the Custom Links table. */
  function addLink() {
    var tbody = document.getElementById("custom-links").getElementsByTagName("tbody")[0],
      rowCount = tbody.querySelectorAll("tr").length + 1,
      newRow = tbody.insertRow(),
      linkTextCell = newRow.insertCell(),
      linkImageCell = newRow.insertCell(),
      activeCell = newRow.insertCell(),
      idText = document.createElement("input"),
      linkText = document.createElement("input"),
      linkImage = document.createElement("input"),
      active = document.createElement("input");

    // Hidden ID field
    idText.setAttribute("type", "hidden");
    idText.setAttribute("name", "book_review_links[" + rowCount + "][id]");

    // Link Text
    linkText.setAttribute("type", "text");
    linkText.setAttribute("name", "book_review_links[" + rowCount + "][text]");

    // Link Image URL
    linkImage.setAttribute("type", "text");
    linkImage.setAttribute("name", "book_review_links[" + rowCount + "][image]");

    // Active
    activeCell.className = "active";
    active.setAttribute("type", "checkbox");
    active.setAttribute("name", "book_review_links[" + rowCount + "][active]");
    active.setAttribute("value", "1");
    active.setAttribute("checked", "checked");

    linkTextCell.appendChild(idText);
    linkTextCell.appendChild(linkText);
    linkImageCell.appendChild(linkImage);
    activeCell.appendChild(active);
  }

  /* Add a new item to the Custom Fields list. */
  function addField() {
    var id = uniqid(),
      $listItem = $("<li/>").attr({ "class": "field" }),
      $label = $("<input>").attr({
        "name": "book_review_fields[fields][book_review_" + id + "][label]",
        "type": "text",
        "class": "label regular-text",
        "placeholder": "Field Name (e.g. Illustrator)"
      }),
      $icon = $("<div>").attr({ "class": "dashicons dashicons-sort" });

    $listItem.append($label).append($icon);
    $("#fields").append($listItem);
  }

  /* Generate a unique ID. Based on PHP's uniqid function. */
  function uniqid(prefix, moreEntropy) {
    //  discuss at: http://phpjs.org/functions/uniqid/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //  revised by: Kankrelune (http://www.webfaktory.info/)
    //        note: Uses an internal counter (in phpJs global) to avoid collision
    //        test: skip
    //   example 1: uniqid();
    //   returns 1: "a30285b160c14"
    //   example 2: uniqid("foo");
    //   returns 2: "fooa30285b1cd361"
    //   example 3: uniqid("bar", true);
    //   returns 3: "bara20285b23dfd1.31879087"
    var retId;

    if (typeof prefix === "undefined") {
      prefix = "";
    }

    var formatSeed = function(seed, reqWidth) {
      seed = parseInt(seed, 10).toString(16); // To hex string

      if (reqWidth < seed.length) {
        // So long we split
        return seed.slice(seed.length - reqWidth);
      }

      if (reqWidth > seed.length) {
        // So short we pad
        return Array(1 + (reqWidth - seed.length)).join("0") + seed;
      }

      return seed;
    };

    if (!this.phpJs) {
      this.phpJs = {};
    }

    if (!this.phpJs.uniqidSeed) {
      // Init seed with big random int.
      this.phpJs.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }

    this.phpJs.uniqidSeed++;

    // Start with prefix, add current milliseconds hex string.
    retId = prefix;
    retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
    // Add seed hex string.
    retId += formatSeed(this.phpJs.uniqidSeed, 5);

    if (moreEntropy) {
      // For more entropy we add a float lower to 10.
      retId += (Math.random() * 10).toFixed(8).toString();
    }

    return retId;
  }
})(jQuery);