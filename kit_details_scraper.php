<?php

$main_menus = ["Main menu theme", "Main Menu Theme", "Main Menu"];
$round_start
$sections = ["Main menu theme", "Bomb planted", "10 seconds remaining on bomb timer", "Choose team", 
				"Deathcam", "Round Loss", "Round MVP anthem", "10 seconds left in round", "Round start",
				"Round Won"];
$kitsa = json_decode(file_get_contents("kits.json"), TRUE);
$kits = $kitsa["kits"];
$kit_id = 0;
$json;
$sections_index = 0;
foreach ($kits['kits'] as $kit) {
	$section = "Main menu theme";
	if (strcmp($kits['urls'][$kit_id], "skip") == 0) {
		$json['details'][$kit]['id'] = 0;
		$kit_id++;
		continue; 
	}
	$html = file_get_contents($kits['urls'][$kit_id]);
	foreach ($sections as $section) {
		$json['details'][$kit]['id'] = $kit_id;
		if (strcmp($section,"Main menu theme")) {
			if (str_contains($html, $sections[0])) {
				$a = explode($section, $html);
			} else if (str_contains($html, $sections[1])) {
				$a = explode($sections[1], $html);
			} else if (str_contains($html, $sections[2])) {
				$a = explode($sections[2], $html);
			}
		} else {
			$a = explode($section, $html);
		}
		$b = explode("</tr>", $a[1]);
		//Method A
		if (str_contains($b[0], '<td><audio id="')) {
			$c = explode('<source src="', $b[1]);
			$json['details'][$kit][$section] = explode('" ', $c[1])[0];
			$text = json_encode($json);
			file_put_contents("out.json", $text, TRUE);
		}
		//Method B
		else
		{
			$c = explode('<audio src="', $html);
			$json['details'][$kit][$section] = explode('" ', $c[1])[0];
			$text = json_encode($json);
			file_put_contents("out.json", $text, TRUE);
		}
	}
	$kit_id++;
}
?>
