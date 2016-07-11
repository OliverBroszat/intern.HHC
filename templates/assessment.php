<?php
/**
 * Template Name: Bewertungsbogen
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

require_once(get_template_directory()."/functions/main_functions.php");

get_header();

// Sample input data
$applicationid = 1;
$memberid = 266;

// Encode application ID (input data) to prevent SQL injection
$applicationid_encoded = $wpdb->prepare("%d", $applicationid);

// Set up id of used question set
$questionset_id = $wpdb->get_row("SELECT assessment_template
	 FROM Application WHERE id=$applicationid")->assessment_template;

// Preparing data queries
$application_query = "SELECT * FROM Application WHERE id=$applicationid_encoded";
$application = $wpdb->get_row($application_query);
$name_query = "SELECT first_name, last_name FROM Contact WHERE id=$application->contact;";
$ratings_query = "SELECT * FROM Rates WHERE member=$memberid AND application=$applicationid_encoded;";
$questions_query = "SELECT * FROM Question JOIN (SELECT * FROM ContainsQuestion WHERE assessment_template
	=$applicationid_encoded) as CQ ON CQ.question=Question.id;";
$categories_query = "SELECT Category.id, Category.name FROM Category JOIN (SELECT * FROM Question JOIN (SELECT question FROM ContainsQuestion WHERE assessment_template
	=$questionset_id)  AS Questions ON Questions.question=Question.id) AS UsedQuestions ON UsedQuestions.category=Category.id GROUP BY Category.id;";

// Fetch data
// $application = $wpdb->get_row($application_query);
$name = $wpdb->get_row($name_query);
$ratings = $wpdb->get_results($ratings_query);
$questions = $wpdb->get_results($questions_query);
$categories = $wpdb->get_results($categories_query);
?>

<style>
/*  SECTIONS  */
.section {
	clear: both;
	padding: 0px;
	margin: 0px;
}

/*  COLUMN SETUP  */
.col {
	display: block;
	float:left;
	margin: 1% 0 1% 1.6%;
}
.col:first-child { margin-left: 0; }

/*  GROUPING  */
.group:before,
.group:after { content:""; display:table; }
.group:after { clear:both;}
.group { zoom:1; /* For IE 6/7 */ }

/*  GRID OF FOUR  */
.span_4_of_4 {
	width: 100%;
}
.span_3_of_4 {
	width: 74.6%;
}
.span_2_of_4 {
	width: 49.2%;
}
.span_1_of_4 {
	width: 23.8%;
}

/*  GO FULL WIDTH BELOW 480 PIXELS */
@media only screen and (max-width: 960px) {
	.col {  margin: 0.5% 0 0.5% 0%; }
	.span_1_of_4, .span_2_of_4, .span_3_of_4, .span_4_of_4 { width: 50%; }
}

@media only screen and (max-width: 480px) {
	.col {  margin: 1% 0 1% 0%; }
	.span_1_of_4, .span_2_of_4, .span_3_of_4, .span_4_of_4 { width: 100%; }
}
</style>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
		<form action="#" method="POST" class="sheet">
			<input type='hidden' name='member' value='<?php echo "$memberid"; ?>'/>
			<input type='hidden' name='application' value='<?php echo "$applicationid"; ?>'/>
			<h2>Bewertungsbogen <?php echo $name->first_name . " " . $name->last_name; ?></h2>

			<?php
			if ($_POST['btn']) {
				var_dump($_POST);
			}
				// List every category with respective questions
				foreach ($categories as $category) {

					echo "<div class='section'><h3>$category->name</h3>";
					echo '<div class="section group">';
					foreach ($questions as $question) {
						if ($question->category != $category->id) {
							continue;
						}
						echo '<div class="col span_1_of_4">';
						echo "<div class='section question' style='padding: 10px;'>$question->description<br>";
						// Possible Replies
						if ($question->answerType == 'Y/N') {
							// WICHTIG
							// -1 bedeutet 'Nein' und wird auf Serverseite in den Wert 0 transformiert.
							$possibleReplies = array(-1, 5);
						}
						else if ($question->answerType == '1.3.5') {
							$possibleReplies = array(1,3,5);
						}
						else if ($question->answerType == '1-5') {
							$possibleReplies = array(1,2,3,4,5);
						}
						else {
							echo 'DATENBANKFEHLER!!!!!!<br>';
						}

						$selected_value = $wpdb->get_row("SELECT value FROM Rates WHERE (member=$memberid AND application=$applicationid AND question=$question->question);")->value;
						foreach ($possibleReplies as $reply) {
							if ($reply == $selected_value) {
								echo "<input type='radio' name='$question->id' value='$reply' checked/>$reply
								";
							}
							else {
								echo "<input type='radio' name='$question->id' value='$reply'/>$reply
								";
							}
							echo '<br>';
						}
						echo '</div></div>';
					}
					echo "</div></div>";
				}
			?>

			<button type="submit" name="btn" value="sub">Bewertung abschicken</button>
		</form>
	</div>
</div>

<?php get_footer(); ?>