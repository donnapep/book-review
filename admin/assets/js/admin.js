jQuery(document).ready(function($) {
  $(".color-picker").wpColorPicker();
});

function showRatingImages() {
  if (jQuery("#book_review_rating_default").attr("checked")) {
    jQuery(".rating").hide();
  }
  else {
    jQuery(".rating").show();
  }
}

function showLinks() {
  var i;
  var numLinks = parseInt(jQuery("#book_review_num_links").val());

  if (numLinks === 0) {
    jQuery(".links").hide();
  }
  else {
    jQuery(".links").show();
  }

  for (i = 1; i <= numLinks; i++) {
    jQuery("#link" + i).show();
  }

  for (i = numLinks + 1; i <= 5; i++) {
    jQuery("#link" + i).hide();
  }
}