jQuery(document).ready(function($) {
  $(".color-picker").wpColorPicker();

  /* Delete Custom Link */
  // $(".delete").on("click", function(event) {
  //   var self = this,
  //     json, data;

  //   if (window.confirm(book_review_confirm.confirm_message)) {
  //     data = {
  //       action: "delete_link",
  //       id: $(this).data("id"),
  //       nonce: $("#book_review_delete_link_" + $(this).data('id') + "_nonce").val()
  //     };

  //     $.post(ajaxurl, data, function(resp) {
  //       try {
  //         resp = JSON.parse(resp);

  //         if (resp.success) {
  //           $(self).closest("tr").remove();
  //         }
  //         else {
  //           console.log("Invalid nonce");
  //         }
  //       }
  //       catch (e) {
  //         console.log("JSON parsing error: ", e);
  //       }
  //     });
  //   }
  //   else {
  //     return false;
  //   }
  // });
});

/* Rating Images Tab */
function showRatingImages() {
  if (jQuery("#book_review_rating_default").attr("checked")) {
    jQuery(".rating").hide();
  }
  else {
    jQuery(".rating").show();
  }
}

/* Links Tab */
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
  linkImage.className = "text-input";
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