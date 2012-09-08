<?php
	
/**
 * Provides a very simple way to capture website screenshots.
 *
 * Credits to the PhantomJS team for creating the awsome headless webkit.
 * For more info about PhantomJS, check out: http://phantomjs.org
 *
 * @package Webcap
 * @version 1.0
 * @author Michael Teeuw
 * @link https://github.com/MichMich/laravel-webcap
 * @example
 *
 *		$capture = Webcap::open('http://www.xonaymedia.nl')
 *						->filetype('png')
 *						->size(1200,675)
 *						->capture()
 *						->save('my/desired/location.png');	
 */


class Webcap {
	/**
	 * Stores the temporary file location.
	 */
	private $filename;

	/**
	 * Stores the requested URL.
	 */
	private $url;

	/**
	 * Stores the error message.
	 */
	private $error;

	/**
	 * Stores the viewport dimensions.
	 */
	private $viewport = array(0,0);

	/**
	 * Stores the clipping coordinates.
	 */
	private $clipping = array(0,0,0,0);

	/**
	 * Stores the delay in milliseconds.
	 */
	private $delay = 100;

	/**
	 * Stores the filetype.
	 */
	private $filetype = 'png';

	/**
	 * Instantiates the Webcap and receives the url to the site we want to capture.
	 * @param string $url The url to the site we want to capture.
	 */
	function __construct($url = false) {
		$this->url = $url;
	}

	/**
	 * Sets the url to the site we want to capture.
	 * @param string $url The url to the site we want to capture.
	 * @return object Webcap object.
	 */
	public function url($url) {
		$this->url = $url;
		return $this;
	}

	/**
	 * Sets the desired filetype for the screenshot.
	 * @param string $type The file extension for the screenshot.
	 * @return object Webcap object. 
	 */
	public function filetype($type) {
		$supported = array('png','gif','jpeg','jpg','pdf');
		$type = strtolower($type);
		if (in_array($type, $supported)) $this->filetype = $type;
		return $this;
	}

	/**
	 * Sets the width and height of the captured section. Note that this doesnt scale the image.
	 * @param int $width The width of the capture.
	 * @param int $height The height of the capture.
	 * @return object Webcap object. 
	 */
	public function size($width, $height) {
		$this->viewport($width, $height);
		$this->clipping(0, 0, $width, $height);
		return $this;
	}

	/**
	 * Sets the width and height of capture viewport. Note that this doesnt scale the image.
	 * @param int $width The width of the viewport.
	 * @param int $height The height of the viewport.
	 * @return object Webcap object. 
	 */
	public function viewport($width, $height) {
		$this->viewport = array($width, $height);
		return $this;
	}

	/**
	 * Sets the clipping dimensions of the capture.
	 * @param int $top The top offset of the capture.
	 * @param int $left The left offset of the capture.
	 * @param int $width The width of the capture.
	 * @param int $height The height of the capture.
	 * @return object Webcap object. 
	 */
	public function clipping($top, $left, $width, $height) {
		$this->clipping = array($top, $left, $width, $height);
		return $this;
	}

	/**
	 * Sets the delay for the capture.
	 * @param int $millisec The delay in milliseconds.
	 * @return object Webcap object.
	 */
	public function delay($millisec) {
		$this->delay = $millisec;
		return $this;
	}

	/**
	 * Initiates capture.
	 * @return object Webcap object.
	 */
	public function capture() {
		if ($this->url) {
			$this->filename = sys_get_temp_dir() . md5($this->url . microtime()).'.'.$this->filetype;
			$result = exec($this->makeCommand());
			if ($result == "") {
				if (!file_exists($this->filename)) {
					$this->error = "Could not create file.";
				}
			} else {
				$this->error = "Capture error: ".$result;
			}
		} else {
			$this->error = "URL not set.";
		}
		return $this;
	}

	/**
	 * Returns location of the captured file.
	 * @return string Path of the captured image.
	 */
	public function file() {
		return (file_exists($this->filename)) ? $this->filename : false;
	}

	/**
	 * Copies the file to the desired path.
	 * @param string $target Path to the desired location, including filename.
	 * @return boolean
	 */
	public function save($target) {
		return copy($this->filename, $target);
	}

	/**
	 * Returns contents of the captured image.
	 * @return binairy
	 */
	public function contents() {
		return  (file_exists($this->filename)) ? File::get($this->filename) : false;
	}

	/**
	 * Returns the error message in case of an error.
	 * @return string Error message.
	 */
	public function error() {
		return $this->error;
	}

	/**
	 * Generates PhantomJS shell command.
	 * @return string Shell command.
	 */
	private function makeCommand() {

		$phantom_path = Config::get("webcap.phantom_path", __DIR__  . DS . 'phantomjs');

		$cmd = '';
		$cmd .= $phantom_path . ' ';
		$cmd .= __DIR__  . DS . 'render.js' . ' ';
		$cmd .= "'".$this->url."' "; //URL Must be between quest because of & in url's.
		$cmd .= $this->filename. ' ';
		$cmd .= $this->viewport[0].' '.$this->viewport[1].' ';
		$cmd .= $this->clipping[0].' '.$this->clipping[1].' '.$this->clipping[2].' '.$this->clipping[3].' ';
		$cmd .= $this->delay;
		return $cmd;
	}

	/**
	 * Static call, Laravel style.
	 * Returns a new Resizer object, allowing for chainable calls.
	 * @param string $url The url to the site we want to capture.
	 * @return object Webcap object.
	 */
	public static function open( $url ){
		return new Webcap( $url );
	}

}