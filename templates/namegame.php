<?php
/**
 * Template Name: NameGame
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

 // Single char
 class Char {
   public $firstName;
   public $lastName;
   public $imageUrl;
   public $ressortName;
   public $ressortDescription;
   public $id;

   public function __construct($contactRow) {
     $this->firstName = $contactRow->getValueForKey('first_name');
     $this->lastName = $contactRow->getValueForKey('last_name');
     $this->imageUrl = wp_get_attachment_image_src($contactRow->getValueForKey('image'))[0];
     $this->ressortName = $contactRow->getValueForKey('ressortName');
     $this->ressortDescription = $contactRow->getValueForKey('ressortDescription');
     $this->id = uniqid('', true);
   }
 }

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

 // Random chars
 class RandomChars {
   public $chars;

   public function __construct($chars, $count, $ressortName, $allRessortName) {
     $byRessortName = function ($var) use ($ressortName, $allRessortName) {
       return $ressortName === $allRessortName || $var->ressortName === $ressortName;
     };

     // Filter all chars by ressort name
     $chars = array_filter($chars, $byRessortName);

     // Not enough chars
     if(sizeof($chars) < $count) {
        $this->chars = NULL;
        return;
     }

     // Get random chars
     $randomChars = [];
     for ($i = 0; $i < $count; $i++) {
       $charIndex = rand(0, sizeof($chars) - 1);
       array_push($randomChars, $chars[$charIndex]);
       array_splice($chars, $charIndex, 1);
     }

     // Set random chars
     $this->chars = $randomChars;
   }

   public function getRandomChar() {
     if(is_null($this->chars)) {
       return NULL;
     }

     return $this->chars[rand(0, sizeof($this->chars) - 1)];
   }
 }

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

 // All chars
 class Chars {
   public $chars;

   public function __construct() {
     // Get new base data controller for fetching the database
     $base = new BaseDataController();

     // Get all contacts with an image as rows
     $contactsWithImageRows = $base->selectMultipleRowsByQuery("
     select *
     from
     	(select *
     	from
     		(select * from contact where image is not null) as t1
     		join
     		(select ressort, contact from member) as t2
     		on t1.id = t2.contact) as t3
     	join
     	(select name as ressortName, id as ressortID, description as ressortDescription from ressort) as t4
     	on t3.ressort = t4.ressortID
     ");

     // Create chars from rows
     $chars = [];
     foreach ($contactsWithImageRows as $value) {
       array_push($chars, new Char($value));
     }

     // Set chars
     $this->chars = $chars;
   }

   public function getRandomChars($count, $ressortName, $allRessortName) {
     return new RandomChars($this->chars, $count, $ressortName, $allRessortName);
   }
 }

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

 // Single ressort
 class Ressort {
   public $name;
   public $description;

   public function __construct($ressortRow) {
     $this->name = $ressortRow->getValueForKey('name');
     $this->description = $ressortRow->getValueForKey('description');
   }
 }

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

 // All ressorts
 class Ressorts {
   public $ressorts;

   public function __construct() {
     // Get new base data controller for fetching the database
     $base = new BaseDataController();

     // Get all ressorts as rows
     $ressortsRows = $base->selectMultipleRowsByQuery("select * from ressort where name != 'unbekannt'");

     // Create ressorts from rows
     $ressorts = [];
     foreach ($ressortsRows as $value) {
       array_push($ressorts, new Ressort($value));
     }

     // Set ressorts
     $this->ressorts = $ressorts;
   }
 }

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

 // Session manager
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

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

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

<style>
  .admin-bar {
    background: transparent;
  }
  nav {
    display: none;
  }
  .img-container {
     position: relative !important;
  }
  #msgBar {
    position: absolute !important;
    top: 0;
    left:  50%;
    transform: translate(-50%, 0);
    min-height: 3rem;
    padding: 0.5rem 1rem !important;
    width: 100%;
    max-width:  720px;
  }
</style>

<script>
function msgBar(title, content, color) {
  $('body').append(`
    <div class="ui message `+ color + `" id="msgBar">
      <div class="header">
        `+ title + `
      </div>
      <p>`+ content + `</p>
    </div>
  `);

  setTimeout(function() {
    $("#msgBar").fadeOut('400', function() {
      this.remove();
    });
  }, 2000);
}
</script>

<h1>Name Game</h1>
<main>
  <div class="outer small clearfix">
    <div class="ui segment">
  	  <div align="center">

        <!-- INIT -->
        <?php if($sessionManager->isStateInit()): ?>
          <!-- Load ressorts -->
          <?php
            $ressorts = (new Ressorts())->ressorts;
          ?>
          <script type="text/javascript">
          $(function() {
            $('.selection.dropdown').dropdown();
          });
          </script>
          <form class="ui form" action="." method="POST">
            <div class="field">
                <label>Wähle das Ressort!</label>
                <div class="ui selection dropdown">
                    <input type="hidden" name="ressort" required>
                    <i class="dropdown icon"></i>
                    <div class="default text">Ressort</div>
                    <div class="menu">
                      <!-- Print all ressorts as dropdown -->
                      <?php
                        echo '<div class="item" data-value="'.$allRessortName.'">Alle Ressorts</div>';
                        foreach ($ressorts as $ressort) {
                          echo '<div class="item" data-value="'.$ressort->name.'">'.$ressort->description.'</div>';
                        }
                      ?>
                    </div>
                </div>
            </div>
            <button type="submit" class="ui button green">Starten!</button>
          </form>

        <!-- STARTED -->
        <?php elseif($sessionManager->isStateStarted()): ?>
          <!-- Load new chars if necessary -->
          <?php
            if(!$sessionManager->areCharsLoaded()) {
              $sessionManager->loadChars($charCount, $allRessortName);
            }
          ?>

          <div class="ui segment">
            <span>Leben</span>
            <span class="ui red circular label"><?=$_SESSION['lifes']?></span>
            <span>Punkte</span>
            <span class="ui green circular label"><?=$_SESSION['points']?></span>
            <span>Ressort</span>
            <span class="ui blue circular label"><?=$_SESSION['ressort_description']?></span>
          </div>

          <form action="." method="POST">
            <button type="submit" class="ui button red" name="reset" value="true">Spiel <?=($sessionManager->isAlive() ? 'abbrechen!' : 'neustarten!')?></button>
          </form>

          <?php if($sessionManager->areTooFewChars()): ?>
            <span class="ui label large red">Ressort hat zu wenig Bilder!</span>
          <?php else: ?>
            <script>msgBar('Richtige Antwort!', 'Du hast einen Punkt dazu bekommen.', 'green')</script>
            <div class="img-container">
              <img style="margin: 3rem auto; width: 50%; display: block;" src="<?=$sessionManager->getSelectedChar()->imageUrl?>" alt="">
            </div>
            <form action="." method="POST">
            <?php
              foreach ($sessionManager->getChars() as $char) {
                echo '<button type="submit" class="ui button blue '.$sessionManager->getDisabledClassIfNotAlive().'" name="solution" value="'.$char->id.'" value="">'.$char->firstName.' '.$char->lastName.'</button>';
              }
            ?>
            </form>
          <?php endif; ?>

        <!-- WRONG -->
        <?php elseif($sessionManager->isStateWrong()): ?>
          <!-- Load new chars if necessary -->
          
          <script>msgBar('Falsche Antwort!', 'Klicke die richtige Antwort an, um fortzufahren.', 'red')</script>

          <div class="ui segment">
            <span>Leben</span>
            <span class="ui red circular label"><?=$_SESSION['lifes']?></span>
            <span>Punkte</span>
            <span class="ui green circular label"><?=$_SESSION['points']?></span>
            <span>Ressort</span>
            <span class="ui blue circular label"><?=$_SESSION['ressort_description']?></span>
          </div>

          <form action="." method="POST">
            <button type="submit" class="ui button red" name="reset" value="true">Spiel <?=($sessionManager->isAlive() ? 'abbrechen!' : 'neustarten!')?></button>
          </form>

          <?php if($sessionManager->areTooFewChars()): ?>
            <span class="ui label large red">Ressort hat zu wenig Bilder!</span>
          <?php else: ?>
            <div class="img-container">
              <img style="margin: 3rem auto; width: 50%; display: block;" src="<?=$sessionManager->getSelectedChar()->imageUrl?>" alt="">
            </div>
            <form action="." method="POST">
            <?php
              foreach ($sessionManager->getChars() as $char) {
                if($char->id === $sessionManager->getSelectedChar()->id) {
                  echo '<button type="submit" class="ui button green '.$sessionManager->getDisabledClassIfNotAlive().'" name="solution" value="'.$char->id.'" value="">'.$char->firstName.' '.$char->lastName.'</button>';
                } else {
                  echo '<button type="submit" class="ui button red '.$sessionManager->getDisabledClassIfNotAlive().'" name="solution" value="'.$char->id.'" value="">'.$char->firstName.' '.$char->lastName.'</button>';
                }
              }
            ?>
            </form>
          <?php endif; ?>
        <?php endif; ?>
  	  </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
