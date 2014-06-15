<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Encoding
	|--------------------------------------------------------------------------
	|
	| Set a default value for <meta charset=""> tag.
	|
	*/

	'charset' => 'utf-8',

	/*
	|--------------------------------------------------------------------------
	| Title
	|--------------------------------------------------------------------------
	|
	| Settings for <title> tag. You can define the default name of your site,
	| that will fill the <title> tag if no other title is defined. You can also 
	| choose to automatically add the sitename at a defined title in the <title>
	| tag for each page of your site. Define a separator to be displayed between
	| the sitename and the title, and if the title should be displayed first, like
	| <title>Title - Sitename</title>, or not (<title>Sitename - Title</title>).
	|
	*/

	'title' => array(

		'sitename' => '',

		'show_sitename' => true,

		'separator' => ' - ',

		'first' => true,

	),

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| Define a default description to fill the <meta name="description"> tag if
	| no other description has been defined.
	|
	*/

	'description' => '',

	/*
	|--------------------------------------------------------------------------
	| Favicon
	|--------------------------------------------------------------------------
	|
	| Define a default favicon if no other is defined. Set favicon's name and
	| path relative to public path, without extension. For example, 'favicon'
	| will render (if files exist):
	|
	| <link rel="shortcut icon" href="http::mydomain.com/favicon.ico">
	| <link rel="icon" href="http::mydomain.com/favicon.ico" type="image/x-icon">
	| <link rel="icon" href="http::mydomain.com/favicon.png" type="image/png">
	|
	*/

	'favicon' =>'',

	/*
	|--------------------------------------------------------------------------
	| Internet Explorer Compatibility
	|--------------------------------------------------------------------------
	|
	| Set to true to automatically display two commonly used utilities to force
	| IE compatibility.
	|
	| ie_edge:
	| <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	|
	| html5_shiv:
	| <!--[if lt IE 9]>
	|   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	| <![endif]-->
	|
	*/

	'ie_edge' => true,

	'html5_shiv' => true,

	/*
	|--------------------------------------------------------------------------
	| Responsive utility for viewport
	|--------------------------------------------------------------------------
	|
	| Set to true to automatically display a commonly used meta tag for
	| responsive design:
	|
	| <meta name="viewport" content="width=device-width, initial-scale=1.0">
	|
	*/

	'responsive' => true,

	/*
	|--------------------------------------------------------------------------
	| Facebook's Open Graph
	|--------------------------------------------------------------------------
	|
	| Set to true to automatically display a bunch of meta tags for Facebook's
	| Open Graph Protocol, and define default values. No blank tag will be
	| displayed. 'Image' is file's name with extension and path relative to
	| public path. No og:image will be displayed if file does not exist.
	|
	*/

	'facebook' => array(

		'active' => false,

		'page_id' => '',

		'app_id' => '',

		'admins' => '',

		'image' => '',

	),

	/*
	|--------------------------------------------------------------------------
	| Twitter Card
	|--------------------------------------------------------------------------
	|
	| Set to true to automatically display a bunch of meta tags for Twitter
	| Card, and define default values. No blank tag will be displayed. 'Image'
	| is file's name with extension and path relative to public path. No
	| og:image will be displayed if file does not exist.
	|
	*/

	'twitter' => array(

		'active' => false,

		'image' => '',

		'site' => '',

		'creator' => '',

	),

	/*
	|--------------------------------------------------------------------------
	| Assets: .css and .js files
	|--------------------------------------------------------------------------
	|
	| Set paths to .css and .js files, and url's for external resources. Default
	| ones are already defined for recent versions of jQuery and Twitter's
	| Bootsrap. The name of cdn's should be the same as the one defined
	| manually with the addCss() and addScript() methods.
	|
	*/

	'assets' => array(

		'paths' => array(

			'css' => '',

			'js' => '',

		),

		'cdn' => array(

			'jquery' => '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js',

			'bootstrap' => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',

		),
	
	),

	/*
	|--------------------------------------------------------------------------
	| Google's Universal Analytics
	|--------------------------------------------------------------------------
	|
	| Set to true to automatically display Google's new Universal Analytics 
	| script at the end of the <head> section. Set also your product id like
	| 'UA-XXXX-Y'.
	| 
	| The script will never be displayed if not in production mode.
	|
	| You can also override default script, for example if you have custom methods,
	| or for a script of another service provider. Paste it without <script>
	| </script> tag.
	|
	*/

	'analytics' => array(

		'active' => true,

		'id' => '',

		'script' => '',

	),

);