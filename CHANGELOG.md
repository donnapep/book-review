# Change Log #

## 2.2.3 ##
* Tweak: Update admin notice that shows when plugin is first activated.

## 2.2.2 ##
* New: Add Dutch translation.
* Tweak: Internationalize countries on *Advanced* tab.
* Fix: Fix *Format* not showing in review box.

## 2.2.1 ##
* New: Add Serbian translation.
* New: Add `book_review_meta_box` hook for when *Book Info* meta box is added.
* Tweak: Update website links.
* Fix: Fix broken translation string on *Advanced* tab.

## 2.2.0 ##
* New: Add ability to select post types to show book information for.
* Tweak: Remove Release Date Format field. Format is now controlled via the Date Format field in Settings > General.

## 2.1.14 ##
* New: Add Country dropdown to Advanced tab of Book Review settings.
* Tweak: Apply WordPress CSS class names to input elements.
* Fix: Fix 403 error from Google Books API for some users.

## 2.1.13 ##
* New: Add dismissible admin notice when plugin is first activated.
* New: Add link to Google Developers Console text on Advanced tab.

## 2.1.12 ##
* New: Add Indonesian translation.
* New: Add `book_review_links` filter for rendering links in review box.
* Tweak: Eliminate use of `extract` function.
* Tweak: Don't close `<input>` or `<img>` tags.
* Fix: Escape all variables.
* Fix: Links defined in the *Book Info* meta box were not being removed when cleared.
* Fix: Spinner in *Book Info* meta box for WordPress 4.2 and higher.

## 2.1.11 ##
* Fixed issue with styling of meta box for custom post types.
* Fixed issue processing the Google Books API response when some fields are not present.
* Added Swedish & Norwegian translation files.

## 2.1.10 ##
* Fixed issue with rating images not showing for PHP versions older than 5.3.0.

## 2.1.9 ##
* Added Review Box Border Width setting.
* Removed tooltips from Book Review Settings.
* Updated error message that displays when unable to retrieve book info.
* Updated links throughout plugin.

## 2.1.8 ##
* Fixed data in Book Info section not updating when field cleared.
* Restructured code to conform to WordPress Plugin Boilerplate 3.0.

## 2.1.7 ##
* Fixed broken schema markup so that ratings, author and published date show in a search engine results page.

## 2.1.6 ##
* Changed Book Review Settings to be a tabbed interface.
* Removed limit of only being able to configure 5 custom links.
* Added ability to set individual custom links to inactive.
* Added custom hooks for developers.
* Added Czech translation files.

## 2.1.5 ##
* Renamed Arabic translation files.

## 2.1.4 ##
* Added Arabic & French translation files.
* Fixed bug with Google Books API text not translating.

## 2.1.3 ##
* Added German translation files.

## 2.1.2 ##
* Fixed some text not translating on the Book Review Settings page.
* Added Russian translation files.

## 2.1.1 ##
* Refactored some of the code.
* Added Chinese translation files.

## 2.1.0 ##
* Added support for schema.org.
* Cover image in archives now links to related post.

## 2.0.2 ##
* Added Spanish translation files.
* Bug fixing - Fixed issue with URLs not saving for posts.

## 2.0.1 ##
* Bug fixing - Removed obsolete public-facing Javascript and CSS.

## 2.0 ##
* Added ability to auto-populate a book's details using the Google Books API.
* Added ability to format the Release Date.
* Refactored entire codebase.

## 1.9 ##
* Added Rating column to the manage posts screen.

## 1.8 ##
* Added support for custom post types.

## 1.7 ##
* Added ability to show book cover thumbnails and rating images in archives.

## 1.6 ##
* Bug fixing - Archives now work with multisite.

## 1.5 ##
* Added Italian translation files.

## 1.4 ##
* Added support for internationalization.
* Bug fixing - Ignore case when sorting archives by title.

## 1.3 ##
* Added a shortcode for showing archives by title or genre.

## 1.2 ##
* Added new *Review Box Position* setting.
* Renamed *Summary* field to *Synopsis* to reduce confusion.

## 1.1 ##
* New Fields
    * Added optional *Genre*, *Format* and *Pages* fields.
* Bug Fixing
    * Included the book info above the post's content on the home page if Full Text is being displayed.
    * Included the book info in the RSS feed.

## 1.0 ##
* Initial release.