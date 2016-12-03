<?php
/**
 * Template Name: NameGame
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */


// Helper function to require files from the /views/namegame/ directory
function getTemplatePart($filename) {
  require_once( get_template_directory() . "/views/namegame/" . $filename . '.php' );
}

// Helper function to create notifications in php
function notification($title, $content, $color) {
  echo "<script>msgBar(`$title`, `$content`, `$color`)</script>";
}

// Use sessions
if(session_id() == '') {
  session_start();
}

// Static strings
$allRessortName = '#all#';
$allRessortDescription = 'Alle Ressorts';

// Number of lifes
$defaultLifes = 3;

// Number of chars
$charCount = 4;

// Create new session manager
$sessionManager = new SessionManager();

// Init if not valid
if(!$sessionManager->isValid()) {
  $sessionManager->init($defaultLifes);
}

// Decide whether get or post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if($sessionManager->isStateInit()) {

    // Determine ressort name
    if(!empty($_POST['ressort'])) {
      $ressortName = $_POST['ressort'];
    } else {
      $ressortName = $allRessortName;
    }

    // Determine ressort description
    if($ressortName === $allRessortName) {
      $ressortDescription = $allRessortDescription;
    } else {
      $ressorts = (new Ressorts())->ressorts;
      foreach ($ressorts as $value) {
        if($value->name === $ressortName) {
          $ressortDescription = $value->description;
        }
      }
    }

    $sessionManager->start($ressortName, $ressortDescription);
  } else if($sessionManager->isStateStarted() || $sessionManager->isStateWrong()) {
    if($_POST['reset']) {
      $sessionManager->reset();
    } else if($sessionManager->verify($_POST['solution'])) {
      $sessionManager->correct();
    } else {
      $sessionManager->wrong();
    }
  }

  // Go back to GET
  wp_redirect('.');
  exit();
}

// Get header
get_header();
?>

<link rel=stylesheet href="<?= get_template_directory_uri() ?>/styles/namegame.css">
<script src="<?= get_template_directory_uri() ?>/js/namegame.js"></script>

<?php
  getTemplatePart('wrapperStart');

  // INIT
  if ($sessionManager->isStateInit()):
    getTemplatePart('start');

  // STARTED
  elseif ($sessionManager->isStateStarted()):

    // Load new chars if necessary
    if (!$sessionManager->areCharsLoaded()) {
      $sessionManager->loadChars($charCount, $allRessortName);
    }

    getTemplatePart('interface');

    if ($sessionManager->areTooFewChars()):
      getTemplatePart('tooFewChars');
    else:
      if($sessionManager->getAndClearFlash() === 'correct') {
        notification('Richtige Antwort!', 'Du hast einen Punkt dazu bekommen.', 'green');
      }
      getTemplatePart('rightAnswer');
    endif;

  // WRONG
  elseif($sessionManager->isStateWrong()):
    getTemplatePart('interface');

    if ($sessionManager->areTooFewChars()):
      getTemplatePart('tooFewChars');
    else:
      if($sessionManager->getAndClearFlash() === 'wrong') {
        notification('Falsche Antwort!', 'Klicke die richtige Antwort an, um fortzufahren.', 'red');
      }
      getTemplatePart('wrongAnswer');
    endif;

  endif;

  getTemplatePart('wrapperEnd');

get_footer();
