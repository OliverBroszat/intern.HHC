<?php
/**
 * Template Name: NameGame
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */
session_start();
get_header();
class RandomChar {

  public $url;
  public $rightName;
  public $wrongName1;
  public $wrongName2;
  public $wrongName3;
  public $randomNames;

  public function __construct($url, $rightName, $randomNames, $wrongName1, $wrongName2, $wrongName3) {
    $this->url = $url;
    $this->rightName = $rightName;
    $this->randomNames = $randomNames;
	$this->wrongName1 = $wrongName1;
    $this->wrongName2 = $wrongName2;
	$this->wrongName3 = $wrongName3;
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
  $randomRowIndex2 = rand(0, sizeof($allImageRows) - 1);
  while($randomRowIndex == $randomRowIndex2){$randomRowIndex2 = rand(0, sizeof($allImageRows) - 1);}
  $randomRowIndex3 = rand(0, sizeof($allImageRows) - 1);
  while($randomRowIndex == $randomRowIndex3 or $randomRowIndex3 == $randomRowIndex2){$randomRowIndex3 = rand(0, sizeof($allImageRows) - 1);}
  $randomRowIndex4 = rand(0, sizeof($allImageRows) - 1);
  while($randomRowIndex4==$randomRowIndex or $randomRowIndex4==$randomRowIndex2 or $randomRowIndex4==$randomRowIndex3){$randomRowIndex4 = rand(0, sizeof($allImageRows) - 1);}
  
  
  $image = $allImageRows[$randomRowIndex]->getValueForKey('image');
  $rightName = $allImageRows[$randomRowIndex]->getValueForKey('first_name') . ' ' . $allImageRows[$randomRowIndex]->getValueForKey('last_name');
  $wrongName1 = $allImageRows[$randomRowIndex2]->getValueForKey('first_name') . ' ' . $allImageRows[$randomRowIndex2]->getValueForKey('last_name');
  $wrongName2 = $allImageRows[$randomRowIndex3]->getValueForKey('first_name') . ' ' . $allImageRows[$randomRowIndex3]->getValueForKey('last_name');
  $wrongName3 = $allImageRows[$randomRowIndex4]->getValueForKey('first_name') . ' ' . $allImageRows[$randomRowIndex4]->getValueForKey('last_name');
  
  $url = wp_get_attachment_image_src($image)[0];
  return new RandomChar($url, $rightName, $allNames, $wrongName1, $wrongName2, $wrongName3);
}

// saves number of right answers in a row
function repeat() {	
	$number = $_SESSION['number'];
	$number++;
	echo $number." richtig beantwortet!";
	$_SESSION['number'] = $number;  
}

// number to switch the right answer
$randomAnswer = rand(1,4);

$char = getRandomCharAndNames();
?>

<h1>Name Game!</h1>
<main>
	<style>
		.button {
			background-color:#ff6600;
			padding: 5px;
			padding-left:20px;
			padding-right:20px;
			border-radius:20px;
			border-style:none;
			color:#fff;
			font-family:'Lato', 'Helvetica Neue', Arial, Helvetica, sans-serif;
			min-width:30%;
		}
		.btn-space {
	   	 margin-left: 25px;
			margin-right: 25px;
		}
	</style>
  <div class="outer small clearfix">
    <div class="ui segment">		
	  <div align="center">
		<?php
		// right answer was clicked
		if(isset($_POST['rightbtn'])){
			echo( "<p>Die Antwort ist richtig!</p>" );
			repeat();
		?>	
			<form action="index.php" method="POST">
				<input type="submit" class="button" name="startbtn" value="n&auml;chste Frage" />
			</form>
		<?php
		// false answer was clicked		
		} else if(isset($_POST['wrongbtn'])){
			echo( "<p>Die Antwort ist falsch!</p>" );
		?>
			<form action="index.php" method="POST">
				<input type="submit" class="button" name="neueRunde" value="Neue Runde?" />
			</form>
		<?php
		// game screen		
		} else if(isset($_POST['startbtn'])){
		?>	
			<img style="margin: 3rem auto; width: 400px; display: block;" src="<?=$char->url?>" alt="">
			<h3>
				<form action="index.php" method="POST">
					<?php
					switch ($randomAnswer){
						case 1:		
					?>
							<input type="submit" class="button btn-space" name="rightbtn" value="<?=$char->rightName?>" />
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName1?>" />
							</br></br>
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName2?>" />
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName3?>" />
					<?php
							break;
						case 2:
					?>	
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName1?>" />
							<input type="submit" class="button btn-space" name="rightbtn" value="<?=$char->rightName?>" />
							</br></br>
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName2?>" />
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName3?>" />
					<?php		
							break;
						case 3:
					?>
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName1?>" />
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName2?>" />
							</br></br>
							<input type="submit" class="button btn-space" name="rightbtn" value="<?=$char->rightName?>" />
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName3?>" />
					<?php		
							break;
						case 4:
					?>
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName1?>" />
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName2?>" />
							</br></br>
							<input type="submit" class="button btn-space" name="wrongbtn" value="<?=$char->wrongName3?>" />
							<input type="submit" class="button btn-space" name="rightbtn" value="<?=$char->rightName?>" />
					<?php		
							break;
					}
					?>
				</form>
			</h3>
		<?php 
		// start screen when no button is clicked  
		} else {
			session_unset(); 
		?>
			<form action="index.php" method="POST">
			<input type="submit" class="button" name="startbtn" value="Start Game" />
			</form>
		<?php
		}
		?>
	  </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>