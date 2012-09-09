# Webcap for LaravelPHP #

This Laravel bundle provides a very simple way to capture website screenshots. It uses the headless webkit 'PhantomJS' as a capture-engine. 

Credits to the PhantomJS team for creating the awesome headless webkit. For more info about PhantomJS, check out: [http://phantomjs.org](http://phantomjs.org)

## Install ##

In ``application/bundles.php`` add:

```php
'webcap' => array('auto' => true),
```

The application uses the phantomjs file in the bundle's library folder. This is the OSX version of PhantomJS. If your machine needs an other file. Get the proper PhantomJS from the [website](http://phantomjs.org/download.html) and edit the sample config file in ``bundels/webcap/config/webcap.php`` and input the path to PhantomJS.

**Please note: the phantomjs file in the bundle's library folder should be executable. This can be done by setting the following permissions via the terminal: ``chmod 777 phantomjs``**

Please note that you might not be able to exec() a phantom file outside the bundle's library folder, due to safe mode restrictions. This can be solved by making a symbolic link to the actual phantomjs file.

## Usage ##

The bundle has a some basic functionalities. Take a look at the webcap.php in the library folder for detailed information.

## Example ##

```php
$capture = Webcap::open('http://www.xonaymedia.nl')
			->filetype('png')
			->size(1200,675)
			->capture();
				
if ($capture->file()) {
	return Response::download($capture->file());
} else {
	return $capture->error();
}
```

## Comments & Suggestion ##
Feel free to improve this bundle by using pull requests.