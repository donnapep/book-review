jQuery(document).ready(function($) {
  $(".color-picker").wpColorPicker();
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