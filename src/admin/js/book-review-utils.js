var BookReview = BookReview || {};

BookReview.Utils = (function () {
  "use strict";

  var phpJs = {};

  /*
   *  Public  Methods
   */

  /* Generate a unique ID. Based on PHP's uniqid function.
   * discuss at: http://phpjs.org/functions/uniqid/
   * original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
   *  revised by: Kankrelune (http://www.webfaktory.info/)
   *        note: Uses an internal counter (in phpJs global) to avoid collision
   *        test: skip
   *   example 1: uniqid();
   *   returns 1: "a30285b160c14"
   *   example 2: uniqid("foo");
   *   returns 2: "fooa30285b1cd361"
   *   example 3: uniqid("bar", true);
   *   returns 3: "bara20285b23dfd1.31879087"
   */
  function getUniqueId(prefix, moreEntropy) {
    var retId;

    if (typeof prefix === "undefined") {
      prefix = "";
    }

    var formatSeed = function(seed, reqWidth) {
      seed = parseInt(seed, 10).toString(16); // To hex string

      // So long we split.
      if (reqWidth < seed.length) {
        return seed.slice(seed.length - reqWidth);
      }

      // So short we pad.
      if (reqWidth > seed.length) {
        return Array(1 + (reqWidth - seed.length)).join("0") + seed;
      }

      return seed;
    };

    // Init seed with big random int.
    if (!phpJs.uniqidSeed) {
      phpJs.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }

    phpJs.uniqidSeed++;

    // Start with prefix, add current milliseconds hex string.
    retId = prefix;
    retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);

    // Add seed hex string.
    retId += formatSeed(phpJs.uniqidSeed, 5);

    // For more entropy we add a float lower to 10.
    if (moreEntropy) {
      retId += (Math.random() * 10).toFixed(8).toString();
    }

    return "book_review_" + retId;
  }

  return {
    getUniqueId: getUniqueId
  };
})();