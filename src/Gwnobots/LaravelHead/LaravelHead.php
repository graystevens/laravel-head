<?php namespace Gwnobots\LaravelHead;

use App;
use Config;
use File;
use URL;
 
class LaravelHead {

/**
	 * Define current layout
	 *
	 * @var string
	 */
	protected $layout;

	/**
	 * The value for meta charset tag
	 * 
	 * @var string
	 */ 
	protected $charset;

	/**
	 * The value for title tag
	 * 
	 * @var string
	 */ 
	protected $title;

	/**
	 * The value for description meta tag
	 * 
	 * @var string
	 */ 
	protected $description;

	/**
	 * The name of favicon file(s)
	 * 
	 * @var string
	 */ 
	protected $favicon;

	/**
	 * Contains all meta tags
	 * 
	 * @var array
	 */ 
	protected $meta = array();

	/**
	 * Contains all link tags
	 * 
	 * @var array
	 */
	protected $link = array();

	/**
	 * Contains all stylesheets
	 * 
	 * @var array
	 */ 
	protected $stylesheets = array();

	/**
	 * Contains all scripts
	 * 
	 * @var array
	 */ 
	protected $scripts = array();

	/**
	 * Contains all additional items
	 * 
	 * @var array
	 */ 
	protected $misc = array();


/* =============================
	CONSTRUCTOR
 ============================ */

	function __construct()
	{
		// Initialize meta array
		$this->meta['name'] = array();
		$this->meta['http-equiv'] = array();
		$this->meta['property'] = array();
	}


/* =============================
	DEFINE CURRENT LAYOUT
 ============================ */

	/**
	 * Set a value for layout to use other settings than default for current request.
	 *
	 * @return void
	 */ 
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}


/* =============================
	GET A CONFIG ITEM
 ============================ */

	/**
	 * Get an item from config, returning default one if custom is null. Blank is considered as not null.
	 *
	 * @return string|bool
	 */ 
	protected function getConfig($item)
	{
		// Get config item value for a custom layout if it exists, even if blank
		if (!is_null(Config::get('laravel-head::'.$this->layout.'.'.$item)))
		{
			return Config::get('laravel-head::'.$this->layout.'.'.$item);
		}

		// Fallback to default config item value if custom one does exist
		else
		{
			return Config::get('laravel-head::'.$item);
		}
	}


/* =============================
	SET A CONFIG ITEM
 ============================ */

	/**
	 * Set an item for current request in default config if custom is null, else in custom config.
	 *
	 * @return void
	 */ 
	protected function setConfig($item, $value)
	{
		// Set config item value for a custom layout if it exists
		if (!is_null(Config::get('laravel-head::'.$this->layout.'.'.$item)))
		{
			return Config::set('laravel-head::'.$this->layout.'.'.$item, $value);
		}

		// Set default config item value if custom one does exist
		else
		{
			return Config::set('laravel-head::'.$item, $value);
		}
	}


/* =============================
	RENDER ALL TAGS IN HEAD
 ============================ */

	/**
	 * Render all tags manually or automatically defined for filling head section.
	 * Empty values don't return any tag.
	 *
	 * @return string
	 */ 
	public function render()
	{
		return 
			$this->tagCharset().
			$this->tagTitle().
			$this->tagDescription().
			$this->tagMeta().
			$this->tagLink().
			$this->tagScript().
			$this->tagMisc();
	}


/* =============================
	ENCODING
 ============================ */

 	/**
	 * Render meta charset tag.
	 *
	 * @return string
	 */ 
	protected function tagCharset()
	{
		// Get the default value in config file if not manually defined
		$charset = ($this->charset) ? $this->charset : $this->getConfig('charset');
		
		// Don't return any tag if value is empty
		if ($charset)
		{
			return '<meta charset="'.$charset.'">' . "\n\t";
		}
	}

	/**
	 * Manually set a value for meta charset tag for the current request.
	 * 
	 * @param string $charset
	 *
	 * @return void
	 */ 
	public function setCharset($charset)
	{
		$this->charset = $charset;
	}


/* =============================
	TITLE TAG
 ============================ */

 	/**
	 * Render title tag.
	 *
	 * @return string
	 */ 
	protected function tagTitle()
	{
		// Get the complete value for title
		$title = $this->renderTitle();

		// Don't return any tag if value is empty
		if ($title)
		{
			return '<title>'.$title.'</title>' . "\n\t";
		}
	}

	/**
	 * Return the complete value for title.
	 *
	 * @return string
	 */ 
	protected function renderTitle()
	{
		// Get default value for site name
		$sitename = $this->getConfig('title.sitename');

		// Value for title, site name if not defined
		$title = ($this->title) ? $this->title : $sitename;
		
		// Get default separator value
		$separator = $this->getConfig('title.separator');

		// Should title show site name
		$show = $this->getConfig('title.show_sitename');

		// Get title position
		$first = $this->getConfig('title.first');

		// Show site name in title
		if ($show)
		{
			// Site name and title values are the same
			if ($sitename == $title)
			{
				return $sitename;
			}
			
			// No site name is defined
			elseif (!$sitename)
			{
				return $title;
			}
			
			// No title is defined
			elseif (!$title)
			{
				return $sitename;
			}
		
			// Get complete title
			else
			{
				return ($first) ? $title.$separator.$sitename : $sitename.$separator.$title;
			}
		}

		// Don't show site name in title
		else
		{
			return $title;
		}
	}

	/**
	 * Activate Site Name for the current request.
	 *
	 * @return void
	 */ 
	public function doSitename()
	{
		$this->setConfig('title.show_sitename', true);
	}

	/**
	 * Deactivate Site Name for the current request.
	 *
	 * @return void
	 */ 
	public function noSitename()
	{
		$this->setConfig('title.show_sitename', false);
	}

	/**
	 * Manually set a value for the title for the current request.
	 * 
	 * @param string $title
	 *
	 * @return void
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}


/* =============================
	DESCRIPTION META TAG
 ============================ */

	/**
	 * Render description meta tag.
	 *
	 * @return string
	 */
	protected function tagDescription()
	{

		// Get the default value in config file if not manually defined
		$description = ($this->description) ? $this->description : $this->getConfig('description');

		// Don't return any tag if value is empty
		if ($description)
		{
			return '<meta name="description" content="'.$description.'">' . "\n\t";
		}
	}

	/**
	 * Manually set a value for description for the current request.
	 * 
	 * @param string $description
	 *
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}


/* =============================
	META TAGS
 ============================ */

 	/**
	 * Render all meta tags.
	 *
	 * @return string
	 */ 
	protected function tagMeta()
	{
		// Initialize string
		$html = '';

		// Prevent robots from crawling and indexing the site if not in production mode
		$this->addRobots();

		// Force IE compatibility if option is active
		$this->addIeEdge();

		// Add viewport meta tag for responsive design if option is active
		$this->addResponsive();

		// Add tags for Open Graph if option is active
		$this->addFacebook();

		// Add tags for Twitter Card if option is active
		$this->addTwitter();

		// Add all manually defined meta tags
		foreach ($this->meta as $type => $val)
		{
			foreach ($val as $value => $content)
			{
				// Check if values are defined
				if ($value && $content)
				{
					// Link to a public file
					if (File::isFile(public_path($content)))
					{
						$content = asset($content);
					}

					// Return tag for each registered meta
					$html .= '<meta '.$type.'="'.$value.'" content="'.$content.'">' . "\n\t";
				}
			}
		}

		// Return complete string
		return $html;
	}

	/**
	 * Register several meta tags.
	 *
	 * @return void
	 */ 
	public function addMeta($meta = array())
	{
		foreach ($meta as $type => $value)
		{
			$this->meta[$type] = array_merge($this->meta[$type], $value);
		}
	}

	/**
	 * Register only one meta tag.
	 *
	 * @return void
	 */ 
	public function addOneMeta($type, $value, $content)
	{
		$this->addMeta(array($type => array($value => $content)));
	}

	/**
	 * Register robots meta tag.
	 *
	 * @return void
	 */ 
	protected function addRobots()
	{
		// Check for production mode
		if (!App::environment('production'))
		{
			$this->addOneMeta('name', 'robots', 'none');
		}
	}

	/**
	 * Register IE Edge meta tag.
	 *
	 * @return void
	 */ 
	protected function addIeEdge()
	{
		// Check if option is active
		if ($this->getConfig('ie_edge'))
		{
			// Don't override a value set manually
			if (!array_key_exists('X-UA-Compatible', $this->meta['http-equiv']))
			{
				$this->addOneMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
			}
		}
	}

	/**
	 * Activate IE Edge for the current request.
	 *
	 * @return void
	 */
	public function doIeEdge()
	{
		$this->setConfig('ie_edge', true);
	}

	/**
	 * Deactivate IE Edge for the current request.
	 *
	 * @return void
	 */
	public function noIeEdge()
	{
		$this->setConfig('ie_edge', false);
	}

	/**
	 * Register Viewport meta tag for responsive design.
	 *
	 * @return void
	 */ 
	protected function addResponsive()
	{
		// Check if option is active
		if ($this->getConfig('responsive'))
		{
			// Don't override a value set manually
			if (!array_key_exists('viewport', $this->meta['name']))
			{
				$this->addOneMeta('name', 'viewport', 'width=device-width, initial-scale=1.0');
			}
		}
	}

	/**
	 * Activate Viewport for the current request.
	 *
	 * @return void
	 */
	public function doResponsive()
	{
		$this->setConfig('responsive', true);
	}

	/**
	 * Deactivate Viewport for the current request.
	 *
	 * @return void
	 */
	public function noResponsive()
	{
		$this->setConfig('responsive', false);
	}

	/**
	 * Register Open Graph meta tags.
	 *
	 * @return void
	 */ 
	protected function addFacebook()
	{
		// Get default Site Name
		$sitename = $this->getConfig('title.sitename');

		// Get value for title tag
		$title = $this->renderTitle();

		// Get value of description
		$description = ($this->description) ? $this->description : $this->getConfig('description');

		// Get Default values in config file
		$admins = $this->getConfig('facebook.admins');
		$page_id = $this->getConfig('facebook.page_id');
		$app_id = $this->getConfig('facebook.app_id');
		$image = $this->getConfig('facebook.image');

		// Check if option is active
		if ($this->getConfig('facebook.active'))
		{
			// Don't override a value set manually
			if (!array_key_exists('fb:page_id', $this->meta['property']))
			{
				$this->addOneMeta('property', 'fb:page_id', $page_id);
			}

			// Don't override a value set manually
			if (!array_key_exists('fb:app_id', $this->meta['property']))
			{
				$this->addOneMeta('property', 'fb:app_id', $app_id);
			}

			// Don't override a value set manually
			if (!array_key_exists('fb:admins', $this->meta['property']))
			{
				$this->addOneMeta('property', 'fb:admins', $admins);
			}

			// Don't override a value set manually + check if file exists
			if (File::isFile(public_path($image)) && !array_key_exists('og:image', $this->meta['property']))
			{
				$this->addOneMeta('property', 'og:image', $image);
			}

			// Don't override a value set manually
			if (!array_key_exists('og:url', $this->meta['property']))
			{
				$this->addOneMeta('property', 'og:url', URL::current());
			}

			// Don't override a value set manually
			if (!array_key_exists('og:type', $this->meta['property']))
			{
				$this->addOneMeta('property', 'og:type', 'website');
			}

			// Don't override a value set manually
			if (!array_key_exists('og:site_name', $this->meta['property']))
			{
				$this->addOneMeta('property', 'og:site_name', $sitename);
			}

			// Don't override a value set manually
			if (!array_key_exists('og:title', $this->meta['property']))
			{
				$this->addOneMeta('property', 'og:title', $title);
			}

			// Don't override a value set manually
			if (!array_key_exists('og:description', $this->meta['property']))
			{
				$this->addOneMeta('property', 'og:description', $description);
			}
		}

		// Option is inactive
		else
		{
			$this->killFacebook();
		}
	}

	/**
	 * Remove all Open Graph tags set manually.
	 *
	 * @return void
	 */ 
	protected function killFacebook()
	{
		foreach ($this->meta as $type => $val)
		{
			foreach ($val as $value => $content)
			{
				if (starts_with($value, 'og:') || starts_with($value, 'fb:'))
				{
					$this->meta['property'] = array_except($this->meta['property'], array($value));
				}
			}
		}
	}

	/**
	 * Activate Open Graph for the current request.
	 *
	 * @return void
	 */
	public function doFacebook()
	{
		$this->setConfig('facebook.active', true);
	}

	/**
	 * Deactivate Open Graph for the current request.
	 *
	 * @return void
	 */
	public function noFacebook()
	{
		$this->getConfig('facebook.active', false);
	}

	/**
	 * Register Twitter Card meta tags.
	 *
	 * @return void
	 */ 
	protected function addTwitter()
	{
		// Get value for title tag
		$title = $this->renderTitle();

		// Get value of description
		$description = ($this->description) ? $this->description : $this->getConfig('description');

		// Get Default values in config file
		$image = $this->getConfig('twitter.image');
		$site = $this->getConfig('twitter.site');
		$creator = $this->getConfig('twitter.creator');

		// Check if option is active
		if ($this->getConfig('twitter.active'))
		{
			// Register default tag but don't override a value set manually
			if (!array_key_exists('twitter:card', $this->meta['name']))
			{
				$this->addOneMeta('name', 'twitter:card', 'summary');
			}

			// Don't override a value set manually
			if (!array_key_exists('twitter:title', $this->meta['name']))
			{
				$this->addOneMeta('name', 'twitter:title', $title);
			}

			// Don't override a value set manually
			if (!array_key_exists('twitter:description', $this->meta['name']))
			{
				$this->addOneMeta('name', 'twitter:description', $description);
			}

			// Don't override a value set manually + check if file exists
			if (File::isFile(public_path($image)) && !array_key_exists('twitter:image:src', $this->meta['name']))
			{
				$this->addOneMeta('name', 'twitter:image:src', $image);
			}

			// Don't override a value set manually
			if (!array_key_exists('twitter:site', $this->meta['name']))
			{
				$this->addOneMeta('name', 'twitter:site', $site);
			}

			// Don't override a value set manually
			if (!array_key_exists('twitter:creator', $this->meta['name']))
			{
				$this->addOneMeta('name', 'twitter:creator', $creator);
			}

			// Don't override a value set manually
			if (!array_key_exists('twitter:url', $this->meta['name']))
			{
				$this->addOneMeta('name', 'twitter:url', URL::current());
			}
		}

		// Option is inactive
		else
		{
			$this->killTwitter();
		}
	}

	/**
	 * Remove all Twitter Card tags set manually.
	 *
	 * @return void
	 */ 
	protected function killTwitter()
	{
		foreach ($this->meta as $type => $val)
		{
			foreach ($val as $value => $content)
			{
				if (starts_with($value, 'twitter:') || starts_with($value, 'twitter:'))
				{
					$this->meta['name'] = array_except($this->meta['name'], array($value));
				}
			}
		}
	}

	/**
	 * Activate Twitter Card for the current request.
	 *
	 * @return void
	 */
	public function doTwitter()
	{
		$this->setConfig('twitter.active', true);
	}

	/**
	 * Deactivate Open Graph for the current request.
	 *
	 * @return void
	 */
	public function noTwitter()
	{
		$this->setConfig('twitter.active', false);
	}


/* =============================
	LINK TAGS
 ============================ */

 	/**
	 * Render all link tags.
	 *
	 * @return string
	 */ 
 	protected function tagLink()
	{
		// Initialize string
		$html = '';

		// Add favicon link tags
		$this->addFavicon();

		// Add stylesheets link tags
		$this->addStyleSheets();

		foreach ($this->link as $link)
		{
			if (array_key_exists(1, $link) && $link[1] && array_key_exists(0, $link) && $link[0])
			{
				// Initialize starting string for conditional comments
				$start_cond = '';
				// Initialize ending string for conditional comments
				$end_cond = '';
				// Initialize string for type attribute
				$type = '';
				// Initialize string for other attributes
				$attr = '';

				// Check for conditional comments
				if (array_key_exists(4, $link) && $link[4])
				{
					// Set starting string for conditional comments
					$start_cond = '<!--[if '.$link[4].']>';
					// Set ending string for conditional comments
					$end_cond = '<![endif]-->';
				}

				// Check for attributes
				if (array_key_exists(3, $link) && is_array($link[3]))
				{
					foreach ($link[3] as $name => $content)
					{
						// Check if values are not empty
						if ($name && $content)
						{
							// Set strings for each attribute
							$attr .= ' '.$name.'="'.$content.'"';
						}
					}
				}

				// Check for type attribute
				if (array_key_exists(2, $link) && $link[2])
				{
					// Set string for type attribute
					$type = ' type="'.$link[2].'"';
				}

				// Return tag for each registered link
				$html .= $start_cond.'<link rel="'.$link[0].'" href="'.$link[1].'"'.$type.$attr.'>'.$end_cond . "\n\t";
			}
		}

		// Return complete string
		return $html;
	}

	/**
	 * Register several link tags.
	 *
	 * @return void
	 */ 
	public function addLink($link = array())
	{
		$this->link = array_merge($this->link, $link);
	}

	/**
	 * Register only one link tag.
	 *
	 * @return void
	 */ 
	public function addOneLink($rel, $href, $type = '', $attr = array(), $cond = '')
	{
		$this->addLink(array(array($rel, $href, $type, $attr, $cond)));
	}

	/**
	 * Register link tags for favicon.
	 *
	 * @return void
	 */ 
	protected function addFavicon()
	{
		// Get the default value in config file if not manually defined
		$favicon = ($this->favicon) ? $this->favicon : $this->getConfig('favicon');

		// Check if value is not empty
		if ($favicon)
		{
			// Check if .ico file exists
			if (File::exists(public_path($favicon.'.ico')))
			{
				$this->addOneLink('shortcut icon', asset($favicon.'.ico'));
				$this->addOneLink('icon', asset($favicon.'.ico'), 'image/x-icon');
			}

			// Check if .png file exists
			if (File::exists(public_path($favicon.'.png')))
			{
				$this->addOneLink('icon', asset($favicon.'.png'), 'image/png');
			}
		}
	}

	/**
	 * Manually set a value for favicon.
	 *
	 * @return void
	 */ 
	public function setFavicon($favicon)
	{
		$this->favicon = $favicon;
	}

	/**
	 * Register link tags for stylesheets.
	 *
	 * @return void
	 */ 
	protected function addStyleSheets()
	{
		// Initialize string for .css path
		$path = '';

		// Get .css path if defined in config file
		if ($this->getConfig('assets.paths.css'))
		{
			$path = $this->getConfig('assets.paths.css') . '/';
		}

		foreach ($this->stylesheets as $file => $options)
		{
			// Set default value for median attribute
			$media = 'all';

			// Set default value for conditional comments
			$cond = '';

			// Check in config file if stylesheet is an external resource
			$cdn = $this->getConfig('assets.cdn.'.$file);

			// Initialize string for file's url
			$href = '';

			// No conditional comment
			if (is_string($options) && $options)
			{
				$media = $options;
			}

			// Conditional comments are defined
			elseif(is_array($options))
			{
				// Check if value is not empty
				if (array_key_exists(1, $options) && $options[1])
				{
					// Get value for conditional comments
					$cond = $options[1];
				}

				// Check if value is not empty
				if (array_key_exists(0, $options) && $options[0])
				{
					// Get value for media attribute
					$media = $options[0];
				}
			}

			// Stylesheet is an external resource
			if ($cdn)
			{
				// Get file's url
				$href = $cdn;
			}

			// Stylesheet is a local resources + check if file exists
			elseif (File::exists(public_path($path.$file.'.css')))
			{
				// Get file's url
				$href = asset($path.$file.'.css');
			}

			// Check if url is not empty
			if ($href)
			{
				// Register link tag for this stylesheet
				$this->addOneLink('stylesheet', $href, '', array('media' => $media), $cond);
			}
		}
	}

	/**
	 * Register several stylesheets.
	 *
	 * @return void
	 */ 
	public function addCss($css = array())
	{
		foreach ($css as $file => $options)
		{
			$this->stylesheets = array_add($this->stylesheets, $file, $options);
		}
	}

	/**
	 * Register only one stylesheet.
	 *
	 * @return void
	 */ 
	public function addOneCss($file, $media = '', $cond = '')
	{
		$this->addCss(array($file => array($media, $cond)));
	}

/* =============================
	SCRIPTS
 ============================ */

 	/**
	 * Render all scripts.
	 *
	 * @return string
	 */ 
	protected function tagScript()
	{
		// Initialize string
		$html = '';

		// Initialize string for .js path
		$path = '';

		// Get .js path if defined in config file
		if ($this->getConfig('assets.paths.js'))
		{
			$path = $this->getConfig('assets.paths.js') . '/';
		}

		foreach ($this->scripts as $file => $options)
		{
			// Initialize starting string for conditional comments
			$start_cond = '';
			// Initialize ending string for conditional comments
			$end_cond = '';
			// Initialize string for load attribute
			$load = '';

			// Check in config file if stylesheet is an external resource
			$cdn = $this->getConfig('assets.cdn.'.$file);

			// Initialize string for file's url
			$src = '';

			// No conditional comment
			if (is_string($options) && ($options == 'defer' || $options == 'async'))
			{
				$load = ' ' . $options;
			}

			// Conditional comments are defined
			elseif(is_array($options))
			{
				// Check if value for conditional comments is not empty
				if (array_key_exists(1, $options) && $options[1])
				{
					// Set starting string for conditional comments
					$start_cond = '<!--[if '.$options[1].']>';
					// Set ending string for conditional comments
					$end_cond = '<![endif]-->';
				}

				// Check if load attribute is defined and is valid
				if (array_key_exists(0, $options) && ($options[0] == 'defer' || $options[0] == 'async'))
				{
					$load = ' ' . $options[0];
				}
			}

			// Script is an external resource
			if ($cdn)
			{
				// Get file's url
				$src = $cdn;
			}

			// Script is a local resource + check if file exists
			elseif(File::exists(public_path($path.$file.'.js')))
			{
				$src = asset($path.$file.'.js');
			}

			// Check if url is not empty
			if ($src)
			{
				// Return tag for each registered script
				$html .= $start_cond.'<script src="'.$src.'"'.$load.'></script>'.$end_cond . "\n\t";
			}
		}

		// Return complete string
		return $html;
	}

	/**
	 * Register several scripts.
	 *
	 * @return void
	 */ 
	public function addScript($script = array())
	{
		foreach ($script as $file => $options)
		{
			$this->scripts = array_add($this->scripts, $file, $options);
		}
	}

	/**
	 * Register only one script.
	 *
	 * @return void
	 */ 
	public function addOneScript($file, $load = '', $cond = '')
	{
		$this->addScript(array($file => array($load, $cond)));
	}


/* =============================
	SCRIPTS
 ============================ */

 	/**
	 * Render all additional items.
	 *
	 * @return string
	 */ 
	protected function tagMisc()
	{
		// Initialize string
		$html = '';

		// Add conditional script for IE compatibility with HTML5 if option is active
		$this->addShiv();

		// Add analytics script if option is active and mode is production
		$this->addAnalytics();

 		foreach ($this->misc as $line)
 		{
 			// Return each item
 			$html .= $line . "\n\t";
 		}

 		// Return complete string
 		return $html;
	}

	/**
	 * Register additional items.
	 *
	 * @return void
	 */ 
	public function addMisc($tag)
 	{
 		// Register several items
 		if (is_array($tag))
 		{
 			foreach ($tag as $line)
 			{
 				array_push($this->misc, $line);
 			}
 		}

 		// Register only one item
 		elseif (is_string($tag))
 		{
 			array_push($this->misc, $tag);
 		}
 	}

 	/**
	 * Register conditional script for IE compatibility with HTML5.
	 *
	 * @return void
	 */ 
 	protected function addShiv()
 	{
 		// Check if option is active
 		if ($this->getConfig('html5_shiv'))
 		{
 			$this->addMisc('<!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->');
 		}
 	}

 	/**
	 * Activate IE compatibility for HTML5 for the current request.
	 *
	 * @return void
	 */
 	public function doShiv()
	{
		$this->setConfig('html5_shiv', true);
	}

	/**
	 * Deactivate IE compatibility for HTML5 for the current request.
	 *
	 * @return void
	 */
 	public function noShiv()
	{
		$this->setConfig('html5_shiv', false);
	}

	/**
	 * Register analytics script.
	 *
	 * @return void
	 */ 
 	protected function addAnalytics()
 	{
 		// Get Product ID value from config file
 		$id = $this->getConfig('analytics.id');

 		// Get optional script that should override default one
 		$script = $this->getConfig('analytics.script');

 		// Check if mode is production and option is active
 		if (App::environment('production') && $this->getConfig('analytics.active'))
 		{
 			// Override default script with the one set in config file
 			if ($script)
 			{
 				$this->addMisc('<script>'.$script.'</script>');
 			}

 			// Register default script only if Product ID is defined
 			elseif ($id)
 			{
 				$this->addMisc("<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', '".$id."', 'auto');ga('send', 'pageview');</script>");
 			}
 		}
 	}

 	/**
	 * Activate analytics script for the current request.
	 *
	 * @return void
	 */
	public function doAnalytics()
	{
		$this->setConfig('analytics.active', true);
	}

	/**
	 * Deactivate analytics script for the current request.
	 *
	 * @return void
	 */
	public function noAnalytics()
	{
		$this->setConfig('analytics.active', false);
	}

}
