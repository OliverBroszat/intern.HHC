<?php
/**
 * Template Name: Home
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

?>

<div class="outer">

	<h1 style="text-transform: none;">intern.HHC</h1>

	<div class="panel">
		Das ist die intern.HHC Startseite
		<?php
		if (is_user_logged_in()) {
			echo 'ID: ' . wp_get_current_user()->ID;
		}
		echo '<br><br>';
		class Test {
		    public $test;
		    
		    public function __construct($v) {
		        $this->test = $v;
		    }
		}
		$t = new Test(10);
		print_r(get_object_vars($t));
		print_r($t->test);
		unset($t->test);
		unset($t->asdf);
		print_r($t->test);
		print_r(get_object_vars($t));
		?>
	</div>

</div>


<?php get_footer(); ?>