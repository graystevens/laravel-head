<?php namespace Gwnobots\LaravelHead;

use Config;
 
class LaravelHead {

	protected $charset;

	protected $title;

	public function render()
	{
		return 
			$this->tagCharset().
			$this->tagTitle()
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

}