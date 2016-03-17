var BookReview = BookReview || {};

BookReview.Links = function ($) {
  "use strict";

  var fileFrame = null;

  /*
   *  Public Methods
   */
  function init() {
    // Set initial visibility of elements.
    $(".book-review-admin .site-link").each(function() {
      setSiteLinkVisibility.call(this);
    });

    $(".book-review-admin .custom-image").each(function() {
      setUrlVisibility.call(this);
    });

    // Add event handlers.
    $(".book-review-admin .site-link").on("click", setSiteLinkVisibility);
    $(".book-review-admin .links").on("change", "input[type='radio']", setUrlVisibility);
    $(".book-review-admin .add-link").on("click", addCustomLink);

    $(".book-review-admin").on("click", ".set-custom-image", showMediaUploader);
    // $(".book-review-admin").on("click", ".update-name", updateCustomLinkText);
  }

  /*
   *  Private Methods
   */

  /* Set site link section visibility. */
  function setSiteLinkVisibility() {
    /*jshint validthis: true */
    var siteLink = $(this).attr("data-site-link");

    if (siteLink && (siteLink.trim().length > 0)) {
      $(".book-review-admin ." + siteLink).toggle($(this).prop("checked"));
    }
  }

  /* Set URL field and upload button visibility. */
  function setUrlVisibility() {
    /*jshint validthis: true */
    var $customImage = null,
      $urlContainer = null;

    // Check if this is already the Custom Image button.
    if ($(this).hasClass("custom-image")) {
      $customImage = $(this);
    }
    else {
      $customImage = $(this).parent().parent().find(".custom-image");
    }

    if ($customImage.length > 0) {
      $urlContainer = $customImage.siblings(".url-container");

      // Set visibility property as opposed to the display property to prevent a small shift in the
      // position of the radio button when selected / unselected.
      if ($urlContainer.length > 0) {
        if ($customImage.is(":checked")) {
          $urlContainer.css("visibility", "visible");
        }
        else {
          $urlContainer.css("visibility", "hidden");
        }
      }
    }
  }

  /* Add UI for creating a custom link. */
  function addCustomLink(e) {
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

  /* Update the link text of a custom link. */
  // function updateCustomLinkText(e) {
  //    /*jshint validthis: true */
  //   var $name = $(this).siblings(".name"),
  //     $heading = $(this).closest(".custom").find(".heading"),
  //     $link = $(this).closest(".custom").find(".link");

  //   e.preventDefault();

  //   // Set custom link heading text.
  //   if (($name.length > 0) && ($name.val() !== "")) {
  //     if ($heading.length > 0) {
  //       $heading.text($name.val());
  //     }

  //     if ($link.length > 0) {
  //       $link.text($name.val());
  //     }

  //     $(".book-review-admin .custom .error-details").hide();

  //     // Show next step.
  //     $(".book-review-admin .custom .link-types").show();
  //   }
  //   else {
  //     $(".book-review-admin .custom .error-details").show();
  //   }
  // }

  /* Show the media uploader. */
  function showMediaUploader(e) {
     /*jshint validthis: true */
    var self = this;

    e.preventDefault();

    // Create the media frame.
    if (!fileFrame) {
      fileFrame = wp.media.frames.file_frame = wp.media({
        title: media_uploader.title,
        button: {
          text: media_uploader.button_text
        },
        multiple: false
      });
    }

    // Remove any existing event handlers.
    fileFrame.off("select");

    // Handle an image being selected.
    fileFrame.on("select", function() {
      setCustomImage($(self));
    });

    fileFrame.open();
  }

  /* Handle custom image selection. */
  function setCustomImage($btn) {
    var attachment = fileFrame.state().get("selection").first().toJSON();

    // Set the URL in the appropriate field.
    $btn.prev().val(attachment.url);
  }

  return {
    "init": init
  };
};
