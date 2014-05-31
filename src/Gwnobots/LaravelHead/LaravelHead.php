<?php namespace Gwnobots\LaravelHead;

use App;
use Config;
 
class LaravelHead {

	protected $charset;

	protected $title;

	protected $meta = array();

	public function render()
	{
		return 
			$this->tagCharset().
			$this->tagTitle().
			$this->tagMeta()
		;
	}

	public function setCharset($charset)
	{
		$this->charset = $charset;
	}

	protected function tagCharset()
	{
		$charset = ($this->charset) ? $this->charset : Config::get('laravel-head::charset');
		
		if ($charset)
		{
			return '<meta charset="'.$charset.'">' . "\n\t";
		}
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function doSitename()
	{
		Config::set('laravel-head::title.sitename', true);
	}

	public function noSitename()
	{
		Config::set('laravel-head::title.sitename', false);
	}

	protected function renderTitle()
	{
		$sitename = Config::get('laravel-head::title.sitename');

		$title = ($this->title) ? $this->title : $sitename;
				
		$separator = Config::get('laravel-head::title.separator');

		$show = Config::get('laravel-head::title.show_sitename');

		$first = Config::get('laravel-head::title.first');

		if ($show)
		{
			if ($sitename == $title)
			{
				return $sitename;
			}
			
			elseif (!$sitename)
			{
				return $title;
			}
		
			elseif (!$title)
			{
				return $sitename;
			}
		
			else
			{
				return ($first) ? $title.$separator.$sitename : $sitename.$separator.$title;
			}
		}

		else
		{
			return $title;
		}
	}

	protected function tagTitle()
	{
		$title = $this->renderTitle();

		if ($title)
		{
			return '<title>'.$title.'</title>' . "\n\t";
		}
	}

	public function addMeta($meta = array())
	{
		$this->meta = array_merge($this->meta, $meta);
	}

	protected function tagMeta()
	{
		$html = '';

		$this->addRobots();

		$this->addDescription();

		$this->addIeEdge();

		$this->addResponsive();

		foreach ($this->meta as $type => $val)
		{
			if ($type == 'name' || $type == 'http-equiv' || $type == 'property')
			{
				foreach ($val as $value => $content)
				{
					if ($value && $content)
					{
						$html .= '<meta '.$type.'="'.$value.'" content="'.$content.'">' . "\n\t";
					}
				}
			}
		}

		return $html;
	}

	public function addOneMeta($type, $value, $content)
	{
		$this->meta[$type][$value] = $content;
	}

	protected function addRobots()
	{
		if (!App::environment('production'))
		{
			$this->addOneMeta('name', 'robots', 'none');
		}
	}

	public function setDescription($description)
	{
		$this->addOneMeta('name', 'description', $description);
	}

	protected function addDescription()
	{
		if (!array_key_exists('name', $this->meta) || !array_key_exists('description', $this->meta['name']))
		{
			$this->addOneMeta('name', 'description', Config::get('laravel-head::description'));
		}
	}

	protected function addIeEdge()
	{
		if (Config::get('laravel-head::ie_edge'))
		{
			if (!array_key_exists('http-equiv', $this->meta) || !array_key_exists('X-UA-Compatible', $this->meta['http-equiv']))
			{
				$this->addOneMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
			}
		}
	}

	public function noIeEdge()
	{
		Config::set('laravel-head::ie_edge', false);
	}

	public function doIeEdge()
	{
		Config::set('laravel-head::ie_edge', true);
	}

	protected function addResponsive()
	{
		if (Config::get('laravel-head::responsive'))
		{
			if (!array_key_exists('name', $this->meta) || !array_key_exists('viewport', $this->meta['name']))
			{
				$this->addOneMeta('name', 'viewport', 'width=device-width, initial-scale=1.0');
			}
		}
	}

	public function doResponsive()
	{
		Config::set('laravel-head::responsive', true);
	}

	public function noResponsive()
	{
		Config::set('laravel-head::responsive', false);
	}

}