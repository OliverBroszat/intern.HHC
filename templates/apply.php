<?php
/**
 * Template Name: Bewerben
 */

get_header();

// require_once("$root/functions/kasAPI/mailinglist.php");
// $req = createMailingList('dynamisch', array('alexander.schaefer@hhc-duesseldorf.de', 'alexander.schaefer8193@gmail.com'));
// $req = getTargetsFromMailingList('mpr@hhc-duesseldorf.de');
//var_dump(explode(',',$req['Response']['ReturnInfo'][0]['mail_forward_targets']));

$root_uri = get_template_directory_uri();
$viewpath = $root_uri.'/views/application';

$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(
    	$viewpath,
    	array('extension' => '.html')
    )
));

$data['root'] = get_template_directory_uri();

echo $mustache->render('apply', $data );

?>
<script>
	function newApplicationTestData() {
		$("select[name='Contact-prefix']").val('Herr');
		$("input[name='Contact-first_name']").val('Max');
		$("input[name='Contact-last_name']").val('0000 Mustermann');
		$("input[name='Contact-birth_date']").val('2000-01-01');
		$("input[name='Contact-skype_name']").val('Test');
		
		$("input[name='Mail-description[]']").val('Test');
		$("input[name='Mail-address[]']").val('test@test.de');
		
		$("input[name='Phone-description[]']").val('Test');
		$("input[name='Phone-number[]']").val('12345678');
		
		$("input[name='Address-description[]']").val('Test');
		$("input[name='Address-street[]']").val('Test');
		$("input[name='Address-number[]']").val('123');
		$("input[name='Address-addr_extra[]']").val('Test');
		$("input[name='Address-postal[]']").val('12345');
		$("input[name='Address-city[]']").val('Test');

		$("select[name='Study-status[]']").val('active');
		$("select[name='Study-degree[]']").val('b sc');
		$("input[name='Study-course[]']").val('Test');
		$("select[name='Study-school[]']").val('Heinrich-Heine-Universität');
		$("input[name='Study-focus[]']").val('Test');
		$("input[name='Study-start[]']").val('2001-01-01');
		$("input[name='Study-end[]']").val('2002-01-01');
		
		$("select[name='Member-active']").val('1');
		$("select[name='Member-ressort']").val('12');
		$("select[name='Member-position']").val('mitglied');
		$("input[name='Member-joined']").val('2001-01-01');
		$("input[name='Member-left']").val('2002-01-01');

		$("textarea[name='Contact-comment']").val('Test');

		placeholder_color();
	}
</script>
