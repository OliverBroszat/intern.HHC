<?php
/**
 * Template Name: NameGame
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

class RandomChar {

  public $url;
  public $firstName;
  public $lastName;
  public $randomNames;

  public function __construct($url, $firstName, $lastName, $randomNames) {
    $this->url = $url;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->randomNames = $randomNames;
  }
}

function getRandomCharAndNames() {
  $base = new BaseDataController();
  $allImageRows = $base->selectMultipleRowsByQuery("SELECT first_name, last_name, image FROM contact WHERE image IS NOT NULL");
  $allNames = [];
  foreach ($allImageRows as $value) {
    array_push($allNames, [$value->getValueForKey('first_name'), $value->getValueForKey('last_name')]);
  }

  $randomRowIndex = rand(0, sizeof($allImageRows) - 1);
  $image = $allImageRows[$randomRowIndex]->getValueForKey('image');
  $firstName = $allImageRows[$randomRowIndex]->getValueForKey('first_name');
  $lastName = $allImageRows[$randomRowIndex]->getValueForKey('last_name');
  $url = wp_get_attachment_image_src($image)[0];
  return new RandomChar($url, $firstName, $lastName, $allNames);
}

$char = getRandomCharAndNames();

?>
<h1>Name Game!</h1>
<main>
  <div class="outer small clearfix">
    <div class="ui segment">
        <img style="margin: 3rem auto; width: 400px; display: block;" src="<?=$char->url?>" alt="">
        <h3>This is: <?=$char->firstName?> <?=$char->lastName?></h3>
        <h4><?=arr_to_list($char->randomNames);?></h4>
    </div>
  </div>
</main>
<?php get_footer(); ?>
