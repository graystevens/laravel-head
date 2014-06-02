#### Available methods for Laravel Head management package

###### Render all tags
	<head>
		<?php echo Head::render(); ?>
		// or
		{{ Head::render() }}
	</head>

###### Encoding
	Head::setCharset('charset');

###### Title
	Head::setTitle('Title');
	Head::doSitename();
	Head::noSitename();

###### Description
	Head::setDescription('This is the description.');

###### Favicon
	// without extension, relative to public path
	Head::setFavicon('favicon');

###### IE Compatibility
	Head::doIeEdge();
	Head::noIeEdge();
	Head::doShiv();
	Head::noShiv();

###### Responsive design
	Head::doResponsive();
	Head::noResponsive();

###### Meta Tags
	// 'type' can be 'name', 'http-equiv' or 'property'
	Head::addMeta(array(
		'type' => array('value' => 'content'),
	));
	
	Head::addOneMeta('type', 'value', 'content');

###### Open Graph
	Head::doFacebook();
	Head::noFacebook;

###### Twitter Card
	Head::doTwitter();
	Head::noTwitter();

###### Link Tags
	// 'type', 'attr' array and 'condition' are optional
	Head::addLink(array(
		array('rel', 'href', 'type', array('attr' => 'value', ...), 'condition')
	));

	Head::addOneLink('rel', 'href', 'type', array('attr' => 'value', ...), 'condition');

###### Stylesheets
	// 'media' can be blank, 'condition' is optional
	// 'file' is relative to .css path, without extension
	Head::addCss(array(
		'file' => array('media', 'condition'),
		'file' => 'media',
	));

	// 'media' and 'condition' are optional
	Head::addOneCss('file', 'media', 'condition');

###### Scripts
	// 'load' can be blank, 'defer' or 'async', 'condition' is optional
	// 'file' is relative to .js path, without extension
	Head::addScript(array(
		'file' => array('load', 'condition'),
		'file' => 'load',
	));

	// 'load' and 'condition' are optional
	Head::addOneScript('file', 'load', 'condition');

###### Analytics
	Head::doAnalytics();
	Head::noAnalytics();

###### Miscellaneous
	Head::addMisc(array(
		'First additional item',
		'Second additional item',
	));
	or
	Head::addMisc('One additional item');
