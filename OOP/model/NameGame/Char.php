<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class Char {
 public $firstName;
 public $lastName;
 public $imageUrl;
 public $imageData;
 public $imageMime;
 public $ressortName;
 public $ressortDescription;
 public $id;

 public function __construct($contactRow) {
   $this->firstName = $contactRow->getValueForKey('first_name');
   $this->lastName = $contactRow->getValueForKey('last_name');
   $this->imageUrl = wp_get_attachment_image_src($contactRow->getValueForKey('image'), $size='')[0];
   $this->imageData = $this->getImageDataByUrl($this->imageUrl);
   // $this->imageMime = $this->getImageMimeByUrl($this->imageUrl);
   $this->ressortName = $contactRow->getValueForKey('ressortName');
   $this->ressortDescription = $contactRow->getValueForKey('ressortDescription');
   $this->id = uniqid('', true);
 }

 private function getImageDataByUrl($url){
 	return base64_encode(file_get_contents($url));
 }

 private function getImageMimeByUrl($url) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	$mime =  finfo_file($finfo, $url);
	finfo_close($finfo);
	return $mime;
 }
}