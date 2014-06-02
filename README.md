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

You do not need to add a Facade in app/config/app.php, as this package's Facade is already registered within its ServiceProvider (see posts from [Philip Brown](http://culttt.com/2013/06/24/creating-a-laravel-4-package/) and [Chris Fidao](http://fideloper.com/create-facade-laravel-4).

## Usage

#### Rendering

To display all custom tags in the `<head></head>` section, simply add in your layout:

    <head>
    	<?php echo Head::render(); ?>
    </head>

    // or with a blade layout
    <head>
    	{{ Head::render() }}
    </head>

#### Basic Settings

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

#### Special utilities

###### Search Engines

By default, the `Head::render()` method will prevent your site from being crawled and indexed when not in production mode, by adding in your `<head></head>` section:

    <meta name="robots" content="none">

###### Internet Explorer Compatibilty

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
    		'author' => 'Me',,
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

###### Basic usage

You can set as many link tags as you need in your Routes or Controllers:

    Head::addLink(array(array('rel', 'href', 'type', array('attr' => 'value'), 'condition')));

Type, Attributes and Condition are optionals. Condition stands for conditional comments (see [Stylesheets](https://github.com/gwnobots/laravel-head#stylesheets) for more explanation).

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






