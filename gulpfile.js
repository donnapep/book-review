
(function () {
  "use strict";

  var cleanCSS = require("gulp-clean-css");
  var concat = require("gulp-concat");
  var del = require("del");
  var gulp = require("gulp");
  var jshint = require("gulp-jshint");
  var rename = require("gulp-rename");
  var runSequence = require("run-sequence");
  var sass = require("gulp-sass");
  var sourcemaps = require("gulp-sourcemaps");
  var uglify = require("gulp-uglify");

  var sassOptions = {
    errLogToConsole: true,
    outputStyle: "expanded"
  };

  gulp.task("clean", function () {
    return del(["build"]);
  });

  gulp.task("settings-sass", function () {
    gulp.src("src/admin/scss/*.scss")
      .pipe(sourcemaps.init())
      .pipe(sass(sassOptions).on("error", sass.logError))
      .pipe(cleanCSS())
      .pipe(sourcemaps.write())
      .pipe(rename({ suffix: ".min" }))
      .pipe(gulp.dest("build/admin/css"))
  });

  gulp.task("public-sass", function () {
    gulp.src("src/public/scss/*.scss")
      .pipe(sourcemaps.init())
      .pipe(sass(sassOptions).on("error", sass.logError))
      .pipe(cleanCSS())
      .pipe(sourcemaps.write())
      .pipe(rename({ suffix: ".min" }))
      .pipe(gulp.dest("build/public/css"))
  });

  gulp.task("settings-js", function () {
    gulp.src([
      "src/admin/js/book-review-admin.js",
      "src/admin/js/book-review-links.js",
      "src/admin/js/book-review-utils.js"
    ])
      .pipe(jshint())
      .pipe(jshint.reporter("jshint-stylish"))
      .pipe(jshint.reporter("fail"))
      .pipe(concat("book-review-settings.js"))
      .pipe(sourcemaps.init())
      .pipe(uglify())
      .pipe(sourcemaps.write())
      .pipe(rename({ suffix: ".min" }))
      .pipe(gulp.dest("build/admin/js"));
  });

  gulp.task("meta-box-js", function () {
    gulp.src("src/admin/js/book-review-admin-meta-box.js")
      .pipe(jshint())
      .pipe(jshint.reporter("jshint-stylish"))
      .pipe(jshint.reporter("fail"))
      .pipe(concat("book-review-meta-box.js"))
      .pipe(sourcemaps.init())
      .pipe(uglify())
      .pipe(sourcemaps.write())
      .pipe(rename({ suffix: ".min" }))
      .pipe(gulp.dest("build/admin/js"));
  });

  gulp.task("sass", function(cb) {
    runSequence(["settings-sass", "public-sass"], cb);
  });

  gulp.task("js", function(cb) {
    runSequence(["settings-js", "meta-box-js"], cb);
  });

  gulp.task("php", function() {
    gulp.src("src/**/*.php")
      .pipe(gulp.dest("build"));
  });

  gulp.task("images", function() {
    gulp.src("src/**/images/*.png")
      .pipe(gulp.dest("build"));
  });

  gulp.task("languages", function() {
    gulp.src("src/**/languages/*.*")
      .pipe(gulp.dest("build"));
  });

  gulp.task("tests", function() {
    gulp.src("tests/*.php")
      .pipe(gulp.dest("build/tests"));
  });

  gulp.task("phpunit", function() {
    gulp.src("phpunit.xml")
      .pipe(gulp.dest("build"));
  });

  gulp.task("watch", function() {
    gulp.watch("src/**/scss/*.scss", ["sass"]);
    gulp.watch("src/**/js/*.js", ["js"]);
    gulp.watch("src/**/*.php", ["php"]);
    gulp.watch("src/**/images/*.png", ["images"]);
    gulp.watch("src/**/languages/*.*", ["languages"]);
  });

  gulp.task("build", function (cb) {
    runSequence("clean", ["sass", "js", "php", "images", "languages", "watch"], cb);
  });

  gulp.task("build-dev", function (cb) {
    runSequence("clean", ["tests", "phpunit", "sass", "js", "php", "images", "languages", "watch"], cb);
  });

  gulp.task("default", function(cb) {
    runSequence("build", cb);
  });
})();