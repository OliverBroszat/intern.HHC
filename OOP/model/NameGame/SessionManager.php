<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class SessionManager {
 public function isValid() {
   return isset($_SESSION['lifes']);
 }

 public function isAlive() {
   return $_SESSION['lifes'] > 0;
 }

 public function getDisabledClassIfNotAlive() {
   return $this->isAlive() ? '' : 'disabled';
 }

 public function areCharsLoaded() {
   return isset($_SESSION['randomChars']);
 }

 public function areTooFewChars() {
   return $this->areCharsLoaded() && is_null($_SESSION['selectedChar']);
 }

 public function getSelectedChar() {
   return $_SESSION['selectedChar'];
 }

 public function getChars() {
   return $_SESSION['randomChars']->chars;
 }

 public function isStateInit() {
   return $_SESSION['state'] === 'init';
 }

 public function isStateStarted() {
   return $_SESSION['state'] === 'started';
 }

 public function isStateWrong() {
   return $_SESSION['state'] === 'wrong';
 }

 public function reset() {
   unset($_SESSION['lifes']);
   unset($_SESSION['points']);
   unset($_SESSION['ressort_name']);
   unset($_SESSION['ressort_description']);
   unset($_SESSION['state']);
   $this->clearChars();
 }

 public function init($lifes) {
   $this->reset();

   // Start with one life more
   $_SESSION['lifes'] = $lifes;
   $_SESSION['points'] = 0;
   $_SESSION['state'] = 'init';
 }

 public function start($ressortName, $ressortDescription) {
   $_SESSION['ressort_name'] = $ressortName;
   $_SESSION['ressort_description'] = $ressortDescription;
   $_SESSION['state'] = 'started';
 }

 public function loadChars($charCount, $allRessortName) {
   $chars = new Chars();
   $randomChars = $chars->getRandomChars($charCount, $_SESSION['ressort_name'], $allRessortName);
   $selectedChar = $randomChars->getRandomChar();
   $_SESSION['randomChars'] = $randomChars;
   $_SESSION['selectedChar'] = $selectedChar;
 }

 public function clearChars() {
   unset($_SESSION['randomChars']);
   unset($_SESSION['selectedChar']);
 }

 public function verify($charId) {
   return $_SESSION['lifes'] > 0 &&
   isset($_SESSION['selectedChar']) && $_SESSION['selectedChar']->id === $charId;
 }

 public function wrong() {
   if($this->isAlive() && $this->isStateStarted()) {
     $_SESSION['lifes']--;
   }
   $_SESSION['state'] = 'wrong';
 }

 public function correct() {
   $this->clearChars();
   if($this->isStateStarted()) {
     $_SESSION['points']++;
   }
   $_SESSION['state'] = 'started';
 }
}