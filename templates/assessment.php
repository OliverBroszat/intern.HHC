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
$questionset_id = $wpdb->get_row("SELECT question_set FROM Application WHERE id=$applicationid")->question_set;

// Preparing data queries
$application_query = "SELECT * FROM Application WHERE id=$applicationid_encoded";
$name_query = "SELECT first_name, last_name FROM Contact WHERE id=$application->contact;";
$ratings_query = "SELECT * FROM Rates WHERE member=$memberid AND application=$applicationid_encoded;";
$questions_query = "SELECT * FROM Question JOIN (SELECT * FROM ContainsQuestion WHERE question_set=$applicationid_encoded) as CQ ON CQ.question=Question.id;";
$categories_query = "SELECT Category.id, Category.name FROM Category JOIN (SELECT * FROM Question JOIN (SELECT question FROM ContainsQuestion WHERE question_set=$questionset_id)  AS Questions ON Questions.question=Question.id) AS UsedQuestions ON UsedQuestions.category=Category.id GROUP BY Category.id;";

// Fetch data
$application = $wpdb->get_row($application_query);
$name = $wpdb->get_row($name_query);
$ratings = $wpdb->get_results($ratings_query);
$questions = $wpdb->get_results($questions_query);
$categories = $wpdb->get_results($categories_query);
?>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
		<form action="" method="POST" class="sheet">
			<h2>Bewertungsbogen <?php echo $name->first_name . " " . $name->last_name; ?></h2>

			<?php
				// List every category with respective questions
				foreach ($categories as $category) {

					echo "<div class='section'><h3>$category->name</h3>";
					foreach ($questions as $question) {
						if ($question->category != $category->id) {
							continue;
						}
						echo "<div class='section question'>$question->description<br>";
						$possible_replies = $wpdb->get_results("SELECT * FROM PossibleReplies WHERE question=$question->id");
						$selected_value = $wpdb->get_row("SELECT value FROM Rates WHERE (member=$memberid AND application=$applicationid AND question=$question->id);")->value;
						foreach ($possible_replies as $reply) {
							if ($reply->id == $selected_value) {
								echo "<input type='radio' name='$question->description' value='$reply->id' checked>$reply->description";
							}
							else {
								echo "<input type='radio' name='$question->description' value='$reply->id'>$reply->description";
							}
						}
						echo '</div><br>';
					}
					echo "</div>";
				}
			?>

			<button type="submit" name="btn" value="sub">Bewertung abschicken</button>
		</form>
	</div>
</div>

<?php get_footer(); ?>