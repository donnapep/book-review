var file_frame;

jQuery(document).ready(function($) {
  // Uploading files
  $(".upload-image-button").on("click", function(event) {
    event.preventDefault();

    // If the media frame already exists, reopen it.
    if (file_frame) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: $(this).data("uploader_title"),
      button: {
        text: $(this).data("uploader_button_text"),
      },
      // Set to true to allow multiple files to be selected.
      multiple: false
    });

    // When an image is selected, run a callback.
    file_frame.on("select", function() {
      attachment = file_frame.state().get("selection").first().toJSON();
      $("#book_review_cover_url").val(attachment.url);
      $("#book_review_cover_image").attr("src", attachment.url).show();
    });

    // Finally, open the modal.
    file_frame.open();
  });

  $("#book_review_cover_url").on("change", function(event) {
    if ($(this).val() !== "") {
      $("#book_review_cover_image").attr("src", $(this).val()).show();
    }
    else {
      $("#book_review_cover_image").attr("src", "").hide();
    }
  });

  $("#get-book-info").on("click", function(event) {
    var volume = null;
    var author = "";
    var genre = "";
    var dateFormat = "";
    var data = {
      action: "get_book_info",
      nonce: $("#ajax_isbn_nonce").text(),
      isbn: $("#book_review_isbn").val()
    };

    if (!$.trim($("#book_review_isbn").val())) {
      $(".error-details").text(book_review_google_api.no_isbn).show();
    }
    else {
      $(".error-details").hide();
      $("#book-review-meta-box .spinner").addClass("is-active");

      $.post(ajaxurl, data, function(response) {
        var json, data, i = 0;

        $("#book-review-meta-box .spinner").removeClass("is-active");

        try {
          json = JSON.parse(response);

          if (json.hasOwnProperty("data") && json.hasOwnProperty("status") && (json.status === "success")) {
            data = JSON.parse(json.data);

            // Check that data has been returned.
            if (data.hasOwnProperty("items") && (data.items.constructor === Array)
              && (data.items.length > 0) && data.items[0].hasOwnProperty("volumeInfo")) {
              volume = data.items[0].volumeInfo;

              // Title
              if (volume.hasOwnProperty("title")) {
                setField($("#book_review_title"), volume.title);
              }

              // Author
              if (volume.hasOwnProperty("authors") && (volume.authors.constructor === Array)
                && !$.trim($("#book_review_author").val())) {
                for (i = 0; i < volume.authors.length; i++) {
                  author += volume.authors[i];

                  if (volume.authors.length > 1) {
                    author += ", ";
                  }
                }

                $("#book_review_author").val(author);
              }

              // Genre
              if (volume.hasOwnProperty("categories") && (volume.categories.constructor === Array)
                && !$.trim($("#book_review_genre").val())) {
                for (i = 0; i < volume.categories.length; i++) {
                  genre += volume.categories[i];

                  if (volume.categories.length > 1) {
                    genre += ", ";
                  }
                }

                $("#book_review_genre").val(genre);
              }

              // Publisher
              if (volume.hasOwnProperty("publisher")) {
                setField($("#book_review_publisher"), volume.publisher);
              }

              // Release Date
              if (volume.hasOwnProperty("publishedDate")) {
                showFormattedDate(volume.publishedDate, json.format);
              }

              // Pages
              if (volume.hasOwnProperty("pageCount")) {
                setField($("#book_review_pages"), volume.pageCount);
              }

              // Cover URL
              if (volume.hasOwnProperty("imageLinks") && volume.imageLinks.hasOwnProperty("thumbnail") &&
                !$.trim($("#book_review_cover_url").val())) {
                $("#book_review_cover_url").val(volume.imageLinks.thumbnail);
                $("#book_review_cover_image").attr("src", volume.imageLinks.thumbnail).show();
              }

              // Synopsis
              if (volume.hasOwnProperty("description")) {
                if (typeof tinymce !== "undefined") {
                  var editor = tinymce.get("book_review_summary");

                  if (editor && editor instanceof tinymce.Editor) {
                    if (!$.trim(editor.getContent())) {
                      editor.setContent(volume.description);
                      editor.save({ no_events: true });
                    }
                  }
                  else {
                    if (!$.trim($("textarea#book_review_summary").val())) {
                      $("textarea#book_review_summary").val(volume.description);
                    }
                  }
                }
              }
            }
            else {
              $(".error-details").text(book_review_google_api.not_found).show();
              console.log(data);
            }
          }
          else if (json.hasOwnProperty("status") && (json.status === "error")) {
            $(".error-details").html(book_review_google_api.unknown_error).show();

            if (json.hasOwnProperty("data")) {
              console.log("Error message: " + json.data);
            }
          }
        }
        catch (e) {
          console.log("JSON parsing error: " + e);
        }
      });
    }

    return false;
  });

  // Populate field if it does not already contain a value.
  function setField($elem, value) {
    if (!$.trim($elem.val()) && (value != null)) {
      $elem.val(value);
    }
  }

  function showFormattedDate(releaseDate, format) {
    var parts, currentDate, currentMonth, currentYear, months;

    if (!$.trim($("#book_review_release_date").val()) && (releaseDate != null)) {
      if (format !== "none") {
        parts = releaseDate.split("-");

        // Ensure that month, day and year have all been returned.
        if (parts.length === 3) {
          releaseDate = new Date(parts[0], parts[1] - 1, parts[2]);
          currentDate = releaseDate.getDate();
          currentMonth = releaseDate.getMonth();
          currentYear = releaseDate.getFullYear();
          months = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];

          if (format === "short") {
            currentMonth++;
            releaseDate = currentMonth + "/" + currentDate + "/" + currentYear;
          }
          else if (format === "european") {
            currentMonth++;
            releaseDate = currentDate + "/" + currentMonth + "/" + currentYear;
          }
          else if (format === "medium") {
            releaseDate = months[currentMonth].substring(0, 3) + " " +
              currentDate + " " + currentYear;
          }
          else if (format === "long") {
            releaseDate = months[currentMonth] + " " + currentDate + ", " +
              currentYear;
          }
        }
      }

      $("#book_review_release_date").val(releaseDate);
    }
  }
});