<?php

$main_menu = array("Main menu theme", "Main Menu Theme", "Main Menu", "Main menu theme ", "Main menu theme");
$bomb_planted = array("Bomb planted", "Bomb planted ", "Bomb Planted ", "Bomb Planted", "Bomb planted", "Bomb planted");
$bomb_timer = array("10 seconds remaining on bomb timer", "10 seconds remaining on the bomb timer", "Bomb 10 Seconds Countdown", "Bomb 10 Second Countdown");
$choose_team = array("Choose team", "Choose Team");
$death_cam = array("Deathcam", "Death Cam");
$loss = array("Round Loss", "Lost Round", "Round loss");
$mvp = array("Round MVP anthem", "Round MVP anthem ", "Round MVP Anthem", "MVP Anthem", "MVP Anthem ");
$left_round = array("10 seconds left in round", "Round 10 Seconds Countdown", "Round 10 Second Countdown");
$round_start = array("Round start", "Start Round (var. 1)", "Start Round (Variation 1)");
$round_won = array("Round Won", "Won Round");
$sections = array($main_menu, $bomb_planted, $bomb_timer, $choose_team, 
				$death_cam, $loss, $mvp, $left_round, $round_start,
				$round_won);
$kitsa = json_decode(file_get_contents("kits.json"), TRUE);
$kits = $kitsa["kits"];
$kit_id = 0;
$json;
$sections_index = 0;
$img_sect = 'portable-infobox pi-background';

//$html = file_get_contents($kits['urls'][61]);
//echo $html;

foreach ($kits['kits'] as $kit) {
	//$section = "Main menu theme";
	if (strcmp($kits['urls'][$kit_id], "skip") == 0) {
		$json['details'][$kit]['id'] = 0;
		$kit_id++;
		continue; 
	}
	$json['details'][$kit]['id'] = $kit_id;
	$html = file_get_contents($kits['urls'][$kit_id]);
	if (str_contains($html, $img_sect)) {
		//echo 'contains yes';
		$img_a = explode($img_sect, $html);
		$img_b = explode('<img src="', $img_a[1]);
		//echo "settings img: " .  explode('" ', $img_b[1])[0];
		$json['details'][$kit]['img'] = explode('" ', $img_b[1])[0];
	}
	
	foreach ($sections as $section) {
		//echo $html;
		$set = false;
		foreach ($section as $sub_section) {
			if (str_contains($html, $sub_section)) {
				$a = explode($sub_section, $html);
				$set = true;
			}
		}
		if (!$set) { $json['details'][$kit][array_values($section)[0]] = ""; continue; }
		$b = explode("</tr>", $a[1]);
		
		//$what['what'][$kit][$section[0]] = $b[0];
		//Method A
		if (str_contains($b[0], '<td><audio id="')) {
			$c = explode('<source src="', $b[0]);
			$json['details'][$kit][array_values($section)[0]] = explode('" ', $c[1])[0];
			$text = json_encode($json);
			file_put_contents("out.json", $text, TRUE);
		}
		//Method B
		else
		{
			$c = explode('<audio src="', $b[0]);
			$json['details'][$kit][array_values($section)[0]] = explode('" ', $c[1])[0];
			$text = json_encode($json);
			file_put_contents("out.json", $text, TRUE);
		}
	}
	$kit_id++;
}
//file_put_contents("test.txt", json_encode($what), TRUE);
?>
