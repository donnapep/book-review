# WordPress Book Review Plugin #
**Contributors:** donnapep  
**Tags:** book, review, rating, book review, book blog, book blogger, book blogging  
**Author URI:** http://donnapeplinskie.com  
**Plugin URI:** http://donnapeplinskie.com/wordpress-book-review-plugin/  
**Requires at least:** 3.5  
**Tested up to:** 4.0  
**Stable tag:** 2.1.4  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Add details such as title, author, cover photo, rating, purchase links and more to each of your book review posts. Show archives by title or genre.

## Description ##

The WordPress Book Review Plugin adds a *Book Info* section to regular posts and custom post types. Fill this out whenever you would like to show more information about a particular book. Note that, at a minimum, *Title* must be specified in order for the information to show at the top of the post, and any fields that you leave blank will not appear. These fields include:

* ISBN (Only visible if a Google API Key has been entered on the *Book Review Settings* page.)
* Title (required)
* Series
* Author
* Genre
* Publisher
* Release Date
* Format
* Pages
* Source
* Up to five customized URLS (as configured in the settings)
* Cover URL
* Synopsis
* Rating
* Include post in archives

If the *Book Info* section has been filled out, these details (with the exception of ISBN) will appear in the post.

**Features**
* Retrieve details about a book automatically from Google Books.
* Position the review box either above or below the post's content.
* Customizable background and border colours.
* Ability to optionally show ratings on the home page when summary text is used.
* Use the built-in star rating images or specify your own.
* Configure up to five links of your choosing. These can be text-based links or you can provide your own images.
* Open your custom links in the same tab or in a new one.
* Show an archive of your reviews by title or genre.

## Internationalization ##
This plugin supports the following translations: Arabic, Chinese, French, German, Italian, Russian, and Spanish.

If you would like to volunteer to translate this plugin into another language, or would like to update an existing file to include any missing translation text, please contact me at donnapep@gmail.com.

## Installation ##

1. Download the plugin and extract it.
1. Upload the `book-review` folder to the `/wp-content/plugins/` directory on your server.
1. Activate the plugin through the *Plugins* menu in WordPress.
1. Customize the settings by clicking on *Book Review* in the *Settings* menu.

## Frequently Asked Questions ##

### What is the Synopsis field for? ###

The *Synopsis* field is meant to hold the summary or description of the book. It is not where you are intended to write your review. You should write your review in the regular WordPress editor at the top of the page, although if you prefer you can certainly write it inside the *Synopsis* editor instead. Be aware that if you do that, the custom links will show at the very bottom of your post, rather than directly below the book's description and cover image.

### Why is my title sorting on "A", "An" or "The" in the title archives? ###

Titles that start with "A", "An" or "The" should be sorted using the second word in the title. For example, *The Hunger Games* would be shown as *Hunger Games, The*. If you have a post that is not sorting this way, try going back into that post and re-saving it.

### Why is my post not showing up in the genre archives? ###

Check to ensure that you have filled out the *Genre* field in the *Book Info* section of the post, and that the *Include post in archives* checkbox is selected.

### Why do the archives take a long time to load? ###

If you are showing thumbnails of the book covers in your archives, then you should be aware that thumbnails are only used in those themes that support Featured Images. If your theme does not support Featured Images, or if a post does not have a Featured Image set, then scaled-down versions of the full-size covers are used. The images are scaled down as per the *Thumbnail size* value in the *Media Settings*. Showing thumbnails in the archive(s) without using Featured Images will result in longer page load times. To aid in determining which images are thumbnails and which are scaled-down versions of the original image, you can click on any cover to see it shown at its true size. If the image is small, then you know a thumbnail is being used. Otherwise, you should go back into that particular post and set the Featured Image.

## Screenshots ##

###1. Book Review Settings###
![Book Review Settings](http://donnapeplinskie.com/wp-content/uploads/2014/05/screenshot-1.png)
###2. Book Info###
![Book Info](http://donnapeplinskie.com/wp-content/uploads/2014/05/screenshot-2.png)
###3. Book Info on a Sample Post###
![Book Info on a Sample Post](http://bookwookie.ca/wp-content/uploads/2013/08/Book_Review_Plugin.png)
###4. Archives by Title###
![Archives by Title](http://bookwookie.ca/wp-content/uploads/2013/08/archives-by-title.png)
###5. Archives by Genre###
![Archives by Genre](http://bookwookie.ca/wp-content/uploads/2013/08/archives-by-genre.png)

## Resources ##
* Full documentation for the WordPress Book Review Plugin can be found on my [blog](http://donnapeplinskie.com/wordpress-book-review-plugin/).
* Follow me on [Google+](https://plus.google.com/u/0/+DonnaPeplinskie/posts), [Twitter](https://twitter.com/donnapep) or [LinkedIn](http://www.linkedin.com/in/donnapeplinskie).
* If you have questions or suggestions, please post them in the forum that can be found on the Support tab.

## About Me ##
* I’m a front-end web developer with a fondness for WordPress. I blog about web development at [donnapeplinskie.com](http://donnapeplinskie.com/).
* I’m a developer advocate for [Rise Vision](http://risevision.com/).
* I’m author of the [WordPress Book Review Plugin](http://wordpress.org/plugins/book-review/) and [WordPress Date and Time Widget](http://wordpress.org/plugins/date-and-time-widget/).
* I’m founder and contributor of the [Book Wookie](http://bookwookie.ca) book blog.

## Changelog ##

### 2.1.4 ###
* Added Arabic & French translation files.
* Fixed bug with Google Books API text not translating.

### 2.1.3 ###
* Added German translation files.

### 2.1.2 ###
* Fixed some text not translating on the Book Review Settings page.
* Added Russian translation files.

### 2.1.1 ###
* Refactored some of the code.
* Added Chinese translation files.

### 2.1.0 ###
* Added support for schema.org.
* Cover image in archives now links to related post.

### 2.0.2 ###
* Added Spanish translation files.
* Bug fixing - Fixed issue with URLs not saving for posts.

### 2.0.1 ###
* Bug fixing - Removed obsolete public-facing Javascript and CSS.

### 2.0 ###
* Added ability to auto-populate a book's details using the Google Books API.
* Added ability to format the Release Date.
* Refactored entire codebase.

### 1.9 ###
* Added Rating column to the manage posts screen.

### 1.8 ###
* Added support for custom post types.

### 1.7 ###
* Added ability to show book cover thumbnails and rating images in archives.

### 1.6 ###
* Bug fixing - Archives now work with multisite.

### 1.5 ###
* Added Italian translation files.

### 1.4 ###
* Added support for internationalization.
* Bug fixing - Ignore case when sorting archives by title.

### 1.3 ###
* Added a shortcode for showing archives by title or genre.

### 1.2 ###
* Added new *Review Box Position* setting.
* Renamed *Summary* field to *Synopsis* to reduce confusion.

### 1.1 ###
* New Fields
    * Added optional *Genre*, *Format* and *Pages* fields.
* Bug Fixing
    * Included the book info above the post's content on the home page if Full Text is being displayed.
    * Included the book info in the RSS feed.

### 1.0 ###
* Initial release.