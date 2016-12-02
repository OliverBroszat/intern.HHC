<?php
/**
 * Template Name: NameGame
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */
// Use sessions
if(session_id() == '') {
  session_start();
}

// Number of lifes
$defaultLifes = 3;

// Number of chars
$charCount = 4;

// Check how many lifes we have, set if unset
if(!isset($_SESSION['lifes'])) {
  // Start with one life more
  $_SESSION['lifes'] = $defaultLifes;
  $_SESSION['points'] = 0;
  $_SESSION['state'] = 'init';
}

// Define game modes
$init = $_SESSION['state'] === 'init';
$started = $_SESSION['state'] === 'started';

// Decide whether get or post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if($init) {
    $_SESSION['ressort_name'] = $_POST['ressort'];
    $_SESSION['ressort_description'] = $_POST['ressort'];
    $_SESSION['state'] = 'started';
  } else if($started) {
    if($_POST['retry']) {
      unset($_SESSION['lifes']);
      unset($_SESSION['points']);
      unset($_SESSION['ressort_name']);
      unset($_SESSION['ressort_description']);
      unset($_SESSION['state']);
      unset($_SESSION['randomChars']);
      unset($_SESSION['selectedChar']);
    } else if($_SESSION['lifes'] > 0 && isset($_SESSION['selectedChar']) && $_SESSION['selectedChar']->id === $_POST['solution']) {
      // Increase points
      $_SESSION['points']++;

      // Clear chars
      unset($_SESSION['randomChars']);
      unset($_SESSION['selectedChar']);
    } else if ($_SESSION['lifes'] > 0) {
      // Reduce life
      $_SESSION['lifes']--;

      // Clear Chars
      unset($_SESSION['randomChars']);
      unset($_SESSION['selectedChar']);
    }
  }

  // Go back to GET
  wp_redirect('.');
  exit();
}

// Get header
get_header();

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

// Random chars
class RandomChars {
  public $chars;

  public function __construct($chars, $count) {

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
    return $this->chars[rand(0, sizeof($this->chars) - 1)];
  }
}

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

  public function getRandomChars($count) {
    return new RandomChars($this->chars, $count);
  }
}

// Single ressort
class Ressort {
  public $name;
  public $description;

  public function __construct($ressortRow) {
    $this->name = $ressortRow->getValueForKey('name');
    $this->description = $ressortRow->getValueForKey('description');
  }
}

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

// Check if game mode
if($init) {
  // Query ressorts
  $ressorts = (new Ressorts())->ressorts;
} else if($started) {

  // Check, if we are alive!
  $alive = $_SESSION['lifes'] > 0;

  // Create new random chars if they do not exist yet
  if(!isset($_SESSION['randomChars']) || !isset($_SESSION['selectedChar'])) {
    // Get all chars in the database
    $chars = new Chars();

    // Select random chars
    $randomChars = $chars->getRandomChars($charCount);

    // Select one char for the game
    $selectedChar = $randomChars->getRandomChar();

    // Remember last query
    $_SESSION['randomChars'] = $randomChars;
    $_SESSION['selectedChar'] = $selectedChar;
  }
}
?>

<h1>Name Game</h1>
<main>
  <div class="outer small clearfix">
    <div class="ui segment">
  	  <div align="center">
        <?php if($init): ?>
          Wähle das Ressort!
          <script type="text/javascript">
          $(function() {
            $('.selection.dropdown').dropdown();
          });
          </script>
          <form class="ui form" action="." method="POST">
            <div class="field">
                <label>Ressort</label>
                <div class="ui selection dropdown">
                    <input type="hidden" name="ressort" required>
                    <i class="dropdown icon"></i>
                    <div class="default text">Ressort</div>
                    <div class="menu">
                      <?php
                        echo '<div class="item" data-value="#all#">Alle Ressorts</div>';
                        foreach ($ressorts as $ressort) {
                          echo '<div class="item" data-value="'.$ressort->name.'">'.$ressort->description.'</div>';
                        }
                      ?>
                    </div>
                </div>
            </div>
            <button type="submit" class="ui button green">Starten!</button>
          </form>
        <?php elseif($started): ?>
          <div class="ui segment">
            <span>Leben</span>
            <span class="ui red circular label"><?=$_SESSION['lifes']?></span>
            <span>Punkte</span>
            <span class="ui green circular label"><?=$_SESSION['points']?></span>
            <span>Ressort</span>
            <span class="ui blue circular label"><?=$_SESSION['ressort_description']?></span>
          </div>

          <?php if(!$alive): ?>
            <form action="." method="POST">
              <button type="submit" class="ui button red" name="retry" value="true">Neu starten!</button>
            </form>
          <?php endif; ?>

          <img style="margin: 3rem auto; width: 50%; display: block;" src="<?=$_SESSION['selectedChar']->imageUrl?>" alt="">

          <h3>
              <form action="." method="POST">
              <?php
                foreach ($_SESSION['randomChars']->chars as $char) {
                  echo '<button type="submit" class="ui button blue '.($alive ? '' : 'disabled').'" name="solution" value="'.$char->id.'" value="">'.$char->firstName.' '.$char->lastName.'</button>';
                }
              ?>
              </form>
          </h3>
        <?php endif; ?>
  	  </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
