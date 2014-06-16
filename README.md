laravel-head
============

This package automate and facilitate management of the `<head></head>` section for your HTML5 layouts with Laravel. It provides utilities for:
 - Meta tags.
 - Link tags.
 - Stylesheets.
 - Scripts.
 - Charset, favicon, title and description tags.
 - Search engines.
 - Responsive design.
 - Internet Explorer compatibility.
 - Facebook's Open Graph protocol.
 - Twitter Card.
 - Google's Universal Analytics.

## Installation

Require this package in your composer.json and run composer update:

    "gwnobots/laravel-head": "dev-master"

After updating composer, add the ServiceProvider to the providers array in app/config/app.php:

    'Gwnobots\LaravelHead\LaravelHeadServiceProvider',

You need to publish the config of this package:

	$ php artisan config:publish gwnobots/laravel-head

You do not need to add an Alias in app/config/app.php, as it is already registered within the ServiceProvider (see posts from [Philip Brown](http://culttt.com/2013/06/24/creating-a-laravel-4-package/) and [Chris Fidao](http://fideloper.com/create-facade-laravel-4)).

## Usage

You can see a summary of all available methods in the [cheatsheet](https://github.com/gwnobots/laravel-head/blob/master/CHEATSHEET.md).

#### Rendering

To display all custom tags in the `<head></head>` section, simply add in your layout:

    <head>
    	<?php echo Head::render(); ?>
    </head>

    // or with a blade layout
    <head>
    	{{ Head::render() }}
    </head>

#### Basic settings

In package's config.php, you can set some default values like charset, sitename, description, favicon... (see comments in config.php for more explanations). These settings will be used if you don't override them for current requests with special methods (in your Routes or Controllers):

    // define encoding for <meta charset=""> tag
    Head::setCharset('charset');

    // define a title for <title> tag
    Head::setTitle('title');

    // de|activate the addition of default sitename to title
    Head::noSitename();
    Head::doSitename();

    // define description for <meta name="description"> tag
    Head::setDescription('description');

    // define a favicon for <link rel=""> tags (relative to public path, without extension)
    Head::setFavicon('favicon');

You can also remove a tag by filling it with blank, for example:

    Head::setFavicon('');

#### Multiple layouts

###### Settings

You can manage different settings for the `<head></head>` section with as many layouts as you need in package's config.php. You can override one ore several settings by adding an array at the end of the config file (see also comments in config.php), like:

    'mylayout' => array(
        'charset' => 'ISO-8859-1',
        'twitter' => array(
            'image' => 'an-image.jpg',
        ),
    ),

Here you would override default values only for meta charset and twitter card image for the layout called 'mylayout'. Default values will be used for other settings.

You should also respect path structure:

    'layouts' => array(
        'custom' => array(
            'charset' => 'ISO-8859-1',
        ),
    ),

In this example, you will override meta charset default value for a layout named 'custom' in the `app/views/layouts` directory.

###### Register a custom layout

To make use of custom settings, you need to tell your app that you are using a specific layout which may call other settings than just default ones.

In your Controller or Blade layout, call:

    Head::setLayout('mycustomlayout');

An easy way to automatically manage custom layouts with Laravel's utilities is to add a constructor in BaseController.php like:

    <?php

    class BaseController extends Controller {

        public function __construct()
        {
            if ( ! is_null($this->layout))
            {
                Head::setLayout($this->layout);
            }
        }
    
        /**
        * Setup the layout used by the controller.
        *
        * @return void
        */
        protected function setupLayout()
        {   
            if ( ! is_null($this->layout))
            {
                $this->layout = View::make($this->layout);
            }
        }
    }

Like this, when setting a layout in your Controllers with `protected $layout = 'mylayout';`, you will also automatically register your layout for this package's methods.

#### Special utilities

###### Search engines

By default, the `Head::render()` method will prevent your site from being crawled and indexed when not in production mode, by adding in your `<head></head>` section:

    <meta name="robots" content="none">

###### Internet Explorer compatibilty

The `Head::render()` method can also automatically renders two commonly used tags for forcing IE compatibility. Set default values (boolean) in config.php for `'ie_edge' => true|false,` and `'html5_shiv' => true|false,` to display:

    // ie_edge is true
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    // html5_shiv is true
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

You can also override default settings for current requests with:

    // de|activate ie_edge
    Head::noIeEdge();
    Head::doIeEdge();

    // de|activate html5_shiv
    Head::noShiv();
    Head::doShiv();

###### Responsive design

A commonly used tag for responsive design can automatically be displayed if set to true in config.php (`'responsive' => true|false,`). It will render:

    // responsive set to true
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

You can override default setting for current requests with:

    // de|activate responsive
    Head::noResponsive();
    Head::doResponsive();

#### Meta tags

###### Basic usage

You can set as many meta tags as you need in your Routes or Controllers:

    Head::addMeta(array('type' => array('value' => 'content')));

For example

    Head::addMeta(array(
    	'name' => array(
    		'copyright' => 'My Company',
    		'author' => 'Me',
    	),
    	'http-equiv' => array(
    		'pragma' => 'no-cache',
    	),
    	'property' => array(
    		'og:title' => 'Title for Open Graph',
    	),
    ));

will render:

    <meta name="copyright" content="My Company">
    <meta name="author" content="Me">
    <meta http-equiv="pragma" content="no-cache">
    <meta property="og:title" content="Title for Open Graph">

If you need to add only one meta tag, you can also use:

    Head::addOneMeta('type', 'value', 'content');

###### Open Graph

The `Head::render()` method can automatically display a bunch of meta tags for Facebook's Open Graph protocol if you activate it in config.php. You can also set some default values. No tag will be displayed if a value is not defined or if a file does not exist.

    // rendered HTML in <head></head> section if facebook's active is set to true in config.php
    <meta property="fb:page_id" content="set in config.php">
    <meta property="fb:app_id" content="set in config.php">
    <meta property="fb:admins" content="set in config.php">
    <meta property="og:image" content="set in config.php">
    <meta property="og:url" content="current url">
    <meta property="fb:type" content="website">
    <meta property="og:site_name" content="default sitename set in config.php">
    <meta property="og:title" content="same as title tag">
    <meta property="og:description" content="same as description meta tag">

You can de|activate Open Graph for current requests with:

    Head::noFacebook();
    Head::doFacebook();

Deactivation will also remove Open Graph's tags that you manually defined with the `Head::addMeta()` and `Head::addOneMeta()` methods.

###### Twitter Card

The `Head::render()` method can automatically display a bunch of meta tags for Twitter Card if you activate it in config.php. You can also set some default values. No tag will be displayed if a value is not defined or if a file does not exist.

    // rendered HTML in <head></head> section if twitter's active is set to true in config.php
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="same as title tag">
    <meta name="twitter:description" content="same as description meta tag">
    <meta name="twitter:image:src" content="set in config.php">
    <meta name="twitter:site" content="set in config.php">
    <meta name="twitter:creator" content="set in config.php">
    <meta name="twitter:url" content="current url">

You can de|activate Twitter Card for current requests with:

    Head::noTwitter();
    Head::doTwitter();

Deactivation will also remove Twitter Card's tags that you manually defined with the `Head::addMeta()` and `Head::addOneMeta()` methods.

###### Overriding

You can override any value of any meta tag by redeclaring it with the `Head::addMeta()` or the `Head::addOneMeta()` method. It will as well override default values set in config.php. For example, you can define a default value for a meta tag in the constructor of a Controller, and override it in one of its actions for a particular request like:

    <?php

    class FrontController extends BaseController {

    	protected $layout = 'mylayout';

    	function __construct()
    	{
    		Head::addOneMeta('name', 'author', 'Me');
    	}

    	public function index()
    	{
    		return View::make('home');
    	}

    	public function anotherPage()
    	{
    		Head::addOneMeta('name', 'author', 'Another Guy');
    		return View::make('anotherpage');
    	}

    }

It is possible to remove a meta tag for a particular request by filling it with blank, for example doing:

    Head::addOneMeta('name', 'author', '');

Overriding also works with special utilities like `<meta name="viewport">` or `<meta http-equiv="X-UA-Compatible">`, but not with `<meta name="description">` that works apart.

#### Link tags

You can set as many link tags as you need in your Routes or Controllers:

    Head::addLink(array(array('rel', 'href', 'type', array('attr' => 'value'), 'condition')));

Type, Attributes and Condition are optionals. Condition stands for conditional comments (see [Stylesheets](https://github.com/gwnobots/laravel-head#stylesheets) for more explanations).

For example

    Head::addLink(array(
    	array('canonical', 'http://domain.com/whatyouwant'),
    	array('alternate', 'http://url-to-your-feed', 'application/rss+xml', array('title' => 'RSS')),
    ));

will render:

    <link rel="canonical" href="http://domain.com/whatyouwant">
    <link rel="alternate" content="http://url-to-your-feed" type="application/rss+xml" title="RSS">

If you need to add only one link tag, you can also use:

    Head::addOneLink('rel', 'href', 'type', array('attr' => 'value'), 'condition');

You cannot override a link tag contrary to meta tags.

#### Stylesheets

###### Basic usage

You can set as many stylesheets as you need in your Routes or Controllers:

    Head::addCss(array('file' => 'media'));

By default, if you let it blank, media is set to 'all'. If you need conditional comment, you can also use:

    Head::addCss(array('file' => array('media', 'condition')));

For example

    Head::addCss(array(
    	'firstfile' => 'screen and (min-width:480px)',
    	'secondfile' => array('', 'lt IE 9'),
    ));

will render

    <link rel="stylesheet" media="screen and (min-width:480px)" href="http://domain.com/firstfile.css">
    <!--[if lt IE 9]><link rel="stylesheet" media="all" href="http://domain.com/secondfile.css"><![endif]-->

You can also add only one stylesheet:

    Head::addOneCss('file', 'media', 'condition');

where media and condition are optional. If file does not exist, the tag will not be displayed.

###### Settings and external resources

In config.php, you can define a default path for .css files (assets -> paths -> css), relative to public path. So when adding a stylesheet you should use the file's path and name (without extension) from this default one.

You can also load external resources defined in config.php (assets -> cdn). You must use the same name for cdn's in config.php and when adding a stylesheet.

You cannot override a stylesheet: stylesheets are rendered only if they have not already been added, so be careful with dependencies.

#### Scripts

Scripts are managed the same way as stylesheets:

    Head::addScript(array('file' => 'load'));
    Head::addScript(array('file' => array('load', 'condition')));
    Head::addOneScript('file', 'load', 'condition');

Load only accept blank, 'defer' or 'async' as values. Load and condition are optional. You can also set path and cdn's in config.php.

#### Google's Universal Analytics

You can automatically add new Google's Universal Analytics at the end of the `<head></head>` section by setting analytics' active to true in config.php. Don't forget to add your Product ID. The script will not be displayed if not in production mode. By default, Universal Analytics script load asynchronously.

You can also override the script by filling analytics' script in config.php, for example if you use custom methods, or another service provider: paste the complete script, without the `<script></script>` tags.

You can de|activate the script for current requests with:

    Head:noAnalytics();
    Head:doAnalytics();

#### Miscellaneous

You can add any additional tags, or custom code like comments, at the end of the `<head></head>` section with:

    // several additions
    Head::addMisc(array('First additional code', 'Second additional code', ...));
    // or only one addition
    Head::addMisc('My additional code');

## Cheat Sheet

Go to a summary of all [available methods](https://github.com/gwnobots/laravel-head/blob/master/CHEATSHEET.md).
