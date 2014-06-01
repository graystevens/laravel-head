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