<?php



function show_smileys_table()
{
	echo <<<EOF
		<table border="0" cellpadding="4" cellspacing="0">
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':-)')"><img src="./smileys/images/grin.gif" width="19" height="19" alt="grin" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':lol:')"><img src="./smileys/images/lol.gif" width="19" height="19" alt="LOL" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':cheese:')"><img src="./smileys/images/cheese.gif" width="19" height="19" alt="cheese" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':)')"><img src="./smileys/images/smile.gif" width="19" height="19" alt="smile" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(';-)')"><img src="./smileys/images/wink.gif" width="19" height="19" alt="wink" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':smirk:')"><img src="./smileys/images/smirk.gif" width="19" height="19" alt="smirk" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':roll:')"><img src="./smileys/images/rolleyes.gif" width="19" height="19" alt="rolleyes" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':-S')"><img src="./smileys/images/confused.gif" width="19" height="19" alt="confused" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':wow:')"><img src="./smileys/images/surprise.gif" width="19" height="19" alt="surprised" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':bug:')"><img src="./smileys/images/bigsurprise.gif" width="19" height="19" alt="big surprise" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':-P')"><img src="./smileys/images/tongue_laugh.gif" width="19" height="19" alt="tongue laugh" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('%-P')"><img src="./smileys/images/tongue_rolleye.gif" width="19" height="19" alt="tongue rolleye" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(';-P')"><img src="./smileys/images/tongue_wink.gif" width="19" height="19" alt="tongue wink" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':P')"><img src="./smileys/images/raspberry.gif" width="19" height="19" alt="raspberry" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':blank:')"><img src="./smileys/images/blank.gif" width="19" height="19" alt="blank stare" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':long:')"><img src="./smileys/images/longface.gif" width="19" height="19" alt="long face" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':ohh:')"><img src="./smileys/images/ohh.gif" width="19" height="19" alt="ohh" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':grrr:')"><img src="./smileys/images/grrr.gif" width="19" height="19" alt="grrr" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':gulp:')"><img src="./smileys/images/gulp.gif" width="19" height="19" alt="gulp" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('8-/')"><img src="./smileys/images/ohoh.gif" width="19" height="19" alt="oh oh" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':down:')"><img src="./smileys/images/downer.gif" width="19" height="19" alt="downer" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':red:')"><img src="./smileys/images/embarrassed.gif" width="19" height="19" alt="red face" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':sick:')"><img src="./smileys/images/sick.gif" width="19" height="19" alt="sick" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':shut:')"><img src="./smileys/images/shuteye.gif" width="19" height="19" alt="shut eye" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':-/')"><img src="./smileys/images/hmm.gif" width="19" height="19" alt="hmmm" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('>:(')"><img src="./smileys/images/mad.gif" width="19" height="19" alt="mad" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('>:-(')"><img src="./smileys/images/angry.gif" width="19" height="19" alt="angry" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':zip:')"><img src="./smileys/images/zip.gif" width="19" height="19" alt="zipper" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':kiss:')"><img src="./smileys/images/kiss.gif" width="19" height="19" alt="kiss" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':ahhh:')"><img src="./smileys/images/shock.gif" width="19" height="19" alt="shock" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolsmile:')"><img src="./smileys/images/shade_smile.gif" width="19" height="19" alt="cool smile" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolsmirk:')"><img src="./smileys/images/shade_smirk.gif" width="19" height="19" alt="cool smirk" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':coolgrin:')"><img src="./smileys/images/shade_grin.gif" width="19" height="19" alt="cool grin" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolhmm:')"><img src="./smileys/images/shade_hmm.gif" width="19" height="19" alt="cool hmm" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolmad:')"><img src="./smileys/images/shade_mad.gif" width="19" height="19" alt="cool mad" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolcheese:')"><img src="./smileys/images/shade_cheese.gif" width="19" height="19" alt="cool cheese" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':vampire:')"><img src="./smileys/images/vampire.gif" width="19" height="19" alt="vampire" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':snake:')"><img src="./smileys/images/snake.gif" width="19" height="19" alt="snake" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':exclaim:')"><img src="./smileys/images/exclaim.gif" width="19" height="19" alt="excaim" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':question:')"><img src="./smileys/images/question.gif" width="19" height="19" alt="question" style="border:0;" /></a></td></tr>
		</table>
EOF;
}

function parse_smileys($str = '', $image_url = '', $smileys = NULL)
{
	if ($image_url == '')
	{
		return $str;
	}

	if ( ! is_array($smileys))
	{
			return $str;
	}

	// Add a trailing slash to the file path if needed
	$image_url = preg_replace("/(.+?)\/*$/", "\\1/",  $image_url);

	foreach ($smileys as $key => $val)
	{
		$str = str_replace($key, "<img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" alt=\"".$smileys[$key][3]."\" style=\"border:0;\" />", $str);
	}

	return $str;
}

$smileys = array(

//	smiley			image name						width	height	alt

	':-)'			=>	array('grin.gif',			'19',	'19',	'grin'),
	':lol:'			=>	array('lol.gif',			'19',	'19',	'LOL'),
	':cheese:'		=>	array('cheese.gif',			'19',	'19',	'cheese'),
	':)'			=>	array('smile.gif',			'19',	'19',	'smile'),
	';-)'			=>	array('wink.gif',			'19',	'19',	'wink'),
	';)'			=>	array('wink.gif',			'19',	'19',	'wink'),
	':smirk:'		=>	array('smirk.gif',			'19',	'19',	'smirk'),
	':roll:'		=>	array('rolleyes.gif',		'19',	'19',	'rolleyes'),
	':-S'			=>	array('confused.gif',		'19',	'19',	'confused'),
	':wow:'			=>	array('surprise.gif',		'19',	'19',	'surprised'),
	':bug:'			=>	array('bigsurprise.gif',	'19',	'19',	'big surprise'),
	':-P'			=>	array('tongue_laugh.gif',	'19',	'19',	'tongue laugh'),
	'%-P'			=>	array('tongue_rolleye.gif',	'19',	'19',	'tongue rolleye'),
	';-P'			=>	array('tongue_wink.gif',	'19',	'19',	'tongue wink'),
	':P'			=>	array('raspberry.gif',		'19',	'19',	'raspberry'),
	':blank:'		=>	array('blank.gif',			'19',	'19',	'blank stare'),
	':long:'		=>	array('longface.gif',		'19',	'19',	'long face'),
	':ohh:'			=>	array('ohh.gif',			'19',	'19',	'ohh'),
	':grrr:'		=>	array('grrr.gif',			'19',	'19',	'grrr'),
	':gulp:'		=>	array('gulp.gif',			'19',	'19',	'gulp'),
	'8-/'			=>	array('ohoh.gif',			'19',	'19',	'oh oh'),
	':down:'		=>	array('downer.gif',			'19',	'19',	'downer'),
	':red:'			=>	array('embarrassed.gif',	'19',	'19',	'red face'),
	':sick:'		=>	array('sick.gif',			'19',	'19',	'sick'),
	':shut:'		=>	array('shuteye.gif',		'19',	'19',	'shut eye'),
	':-/'			=>	array('hmm.gif',			'19',	'19',	'hmmm'),
	'>:('			=>	array('mad.gif',			'19',	'19',	'mad'),
	':mad:'			=>	array('mad.gif',			'19',	'19',	'mad'),
	'>:-('			=>	array('angry.gif',			'19',	'19',	'angry'),
	':angry:'		=>	array('angry.gif',			'19',	'19',	'angry'),
	':zip:'			=>	array('zip.gif',			'19',	'19',	'zipper'),
	':kiss:'		=>	array('kiss.gif',			'19',	'19',	'kiss'),
	':ahhh:'		=>	array('shock.gif',			'19',	'19',	'shock'),
	':coolsmile:'	=>	array('shade_smile.gif',	'19',	'19',	'cool smile'),
	':coolsmirk:'	=>	array('shade_smirk.gif',	'19',	'19',	'cool smirk'),
	':coolgrin:'	=>	array('shade_grin.gif',		'19',	'19',	'cool grin'),
	':coolhmm:'		=>	array('shade_hmm.gif',		'19',	'19',	'cool hmm'),
	':coolmad:'		=>	array('shade_mad.gif',		'19',	'19',	'cool mad'),
	':coolcheese:'	=>	array('shade_cheese.gif',	'19',	'19',	'cool cheese'),
	':vampire:'		=>	array('vampire.gif',		'19',	'19',	'vampire'),
	':snake:'		=>	array('snake.gif',			'19',	'19',	'snake'),
	':exclaim:'		=>	array('exclaim.gif',		'19',	'19',	'excaim'),
	':question:'	=>	array('question.gif',		'19',	'19',	'question') // no comma after last item

		);
?>