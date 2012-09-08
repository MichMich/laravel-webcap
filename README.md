# Webcap for LaravelPHP #

This Laravel bundle provides a very simple way to capture website screenshots. It uses the headless webkit 'PhantomJS' as a capture-engine. 

Credits to the PhantomJS team for creating the awsome headless webkit. For more info about PhantomJS, check out: [http://phantomjs.org]()

## Install ##

In ``application/bundles.php`` add:

```php
'webcap' => array('auto' => true),
```

The application uses the phantomjs file in the bundle's library folder. This is the OSX version of PhantomJS. If your machine needs an other file. Get the proper PhantomJS from the [website](http://phantomjs.org/download.html) and edit the sample config file in ``bundels/webcap/config/webcap.php`` and input the path to PhantomJS.

## Usage ##

The bundle has a some basic functionalities. Take a look at the webcap.php in the library folder for detailed information.

## Example ##

```php
$capture = Webcap::open('http://www.xonaymedia.nl')
			->filetype('png')
			->size(1200,675)
			->capture();
				
if ($capture && !$capture->error()) {
	return Response::download($capture->file());
} else {
	return $capture->error();
}
```

## Comments & Suggestion ##
Feel free to improve this bundle by using pull requests.