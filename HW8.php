<?php 
header('Content-type: text/xml;charset=UTF-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

$searchTitle = $_GET["title"];  //get the text in search box
$searchExplode = explode(" ", $searchTitle);  //explode the text with " "
$title = $searchExplode[0];  //title get the first element
for($i=1; $i<count($searchExplode); $i++) {   //if there are more words, concatenate them to searchString
	$title = $title."+".$searchExplode[$i];
}
$title = htmlspecialchars($title);
$type = $_GET["type"];  //get the searched bype
$url = "http://www.allmusic.com/search/$type/$title";  //the url to search
$html = file_get_contents($url);  //get the contents

$regexp = "\<tr\sclass\=\"search\-result\s[^^]*\<\/tr\>";  //search for search-result
preg_match_all("/$regexp/U", $html, $out, PREG_PATTERN_ORDER);
if(count($out[0]) == 0) {  //if no discography is found
	echo "<results>\n<result />";
	echo "</results>\n";
}

else {  //if some discography is found
	
	if($type == "artists") {  //if the type is artist
		echo "<results>\n";
		$imageReg = "<img\ssrc=\"(.*)\"\sstyle";
		$nameReg = "class=\"name\">\n.*\">(.*)<\/a>";
		$genreReg = "class=\"info\">\n\s*([A-Za-z]+.*)\n\s*<br\/>";
		$yearReg = "<br\/>\n.*\n\s*([0-9]+.*)\n\s*<\/div>";
		$linkReg = "class=\"name\"[^^]*href=\"(.*)\"\sdata-tooltip";
		
		for($i=0;$i<5;$i++) {  //for every search-result
			preg_match_all("/$imageReg/", $out[0][$i], $image);
			preg_match_all("/$nameReg/", $out[0][$i], $name);
			preg_match_all("/$genreReg/", $out[0][$i], $genre);
			preg_match_all("/$yearReg/", $out[0][$i], $year);
			preg_match_all("/$linkReg/", $out[0][$i], $link);		
			
			echo "<result cover=";
			if(count($image[1]) == 0) echo "\"NA\"";
			else {
				$image[1][0] = htmlspecialchars($image[1][0]);
				echo "\"".$image[1][0]."\"";
			}
			echo " name=";
			if(count($name[1]) == 0) echo "\"NA\"";
			else {
				$name[1][0] = htmlspecialchars($name[1][0]);
				echo "\"".$name[1][0]."\"";
			}
			echo " genre=";
			if(count($genre[1])==0) echo "\"NA\"";			
			else {
				$genre[1][0] = htmlspecialchars($genre[1][0]);
				echo "\"".$genre[1][0]."\"";
			}
			echo " year=";
			if(count($year[1])==0) echo "\"NA\"";
			else {
				$year[1][0] = htmlspecialchars($year[1][0]);
				echo "\"".$year[1][0]."\"";
			}
			echo " details=";
			if(count($link[1])==0) echo "\"NA\"";
			else {
				$link[1][0] = htmlspecialchars($link[1][0]);
				echo "\"".$link[1][0]."\"";
			}

			echo " />\n";
		}
		echo "</results>\n";
	}


	else if($type == "albums") {  //if the type is albums
		echo "<results>\n";
		$imageReg = "<img\ssrc=\"(.*)\"\sstyle";
		$titleReg = "class=\"title\">\n.*\">(.*)<\/a>";
		$artistRegRaw = "class=\"artist\">\n(.*)<\/div>";
		$artistReg = ">([^\<]*)<\/a>";
		$genreReg = "<br\/>\n\s*([A-Za-z]+.*)<\/div>";
		$yearReg = "class=\"info\">\n\s*([0-9]+.*)<br";	
		$linkReg = "class=\"title\">\n.*href=\"(.*)\"\sdata-tooltip";

		for($i=0;$i<5;$i++) {
			preg_match_all("/$imageReg/", $out[0][$i], $image);
			preg_match_all("/$titleReg/", $out[0][$i], $title);		
			preg_match_all("/$genreReg/", $out[0][$i], $genre);
			preg_match_all("/$yearReg/", $out[0][$i], $year);
			preg_match_all("/$linkReg/", $out[0][$i], $link);
			
			echo "<result cover=";
			if(count($image[1]) == 0) echo "\"NA\"";
			else {
				$image[1][0] = htmlspecialchars($image[1][0]);
				echo "\"".$image[1][0]."\"";
			}
			echo " title=";
			if(count($title[1]) == 0) echo "\"NA\"";
			else {
				$title[1][0] = htmlspecialchars($title[1][0]);
				echo "\"".$title[1][0]."\"";}
			
			echo " artist=";
			preg_match_all("/$artistRegRaw/", $out[0][$i], $artistRawOut);
			
			if(count($artistRawOut[0]) == 0) {echo "\"NA\"";}
			else {
				echo "\"";
				preg_match_all("/$artistReg/", $artistRawOut[1][0], $artist);
				
				if(count($artist[0]) == 0) {
					$artistSpecialReg = "([^\<]*)<\/div>";
					preg_match_all("/$artistSpecialReg/", $artistRawOut[1][0], $artistSpecial);
					if(count($artistSpecial[0]) == 0) {
						echo "NA";
					}
					else {		
						
						$artistSpecial[1][0] = htmlspecialchars($artistSpecial[1][0]);
						echo $artistSpecial[1][0];				
						for($j=1; $j<(count($artistSpecial[1]));$j++) {
							$artistSpecial[1][$j] = htmlspecialchars($artistSpecial[1][$j]);
							echo "/".$artistSpecial[1][$j];
						}	
					}
				} 
				else{
					$artist[1][0] = htmlspecialchars($artist[1][0]);
					echo $artist[1][0];				
					for($j=1; $j<(count($artist[1]));$j++) {
						$artist[1][$j] = htmlspecialchars($artist[1][$j]);
						echo "/".$artist[1][$j];
					}	
				}			
				echo "\"";
			}		
			
			echo " genre=";
			if(count($genre[1]) == 0) echo "\"NA\"";
			else {
				$genre[1][0] = htmlspecialchars($genre[1][0]);
				echo "\"".$genre[1][0]."\"";
			}
			echo " year=";
			if(count($year[1]) == 0) echo "\"NA\"";
			else {
				$year[1][0] = htmlspecialchars($year[1][0]);
				echo "\"".$year[1][0]."\"";
			}
			echo " details=";
			if(count($link[1]) == 0) echo "\"NA\"";
			else {
				$link[1][0] = htmlspecialchars($link[1][0]);
				echo "\"".$link[1][0]."\"";}
			
			echo " />\n";
		}
		echo "</results>\n";
	}


	else if($type == "songs") {  //if the type is songs
		echo "<results>\n";
		$linkToSampleReg = "href=\"(.*)\"\stitle=\"play\ssample\"";
		$titleReg = "title=\"[^^]*\&quot\;(.*)\&quot\;\<\/a\>";	
		$performerRegRaw = "class=\"performer\"[^^]*<\/span>";
		$performerReg ="\">([^\<]*)<\/a>";
		$composerRegRaw = "Composed\sby.*<\/div>";
		$composerReg = "\"\>([^\<]*)\<\/a\>";
		$linkToSongReg = "class=\"title\"\>\n.*href=\"(.*)\"\>\&quot";

		for($i=0;$i<5;$i++){
			
			preg_match_all("/$linkToSampleReg/", $out[0][$i], $linkToSample);
			preg_match_all("/$titleReg/", $out[0][$i], $title);
			preg_match_all("/$performerReg/", $out[0][$i], $performer);
			preg_match_all("/$linkToSongReg/", $out[0][$i], $linkToSong);
			
			echo "<result sample=";
			if(count($linkToSample[1]) == 0) echo "\"NA\"";
			else {
				$linkToSample[1][0] = htmlspecialchars($linkToSample[1][0]);
				echo "\"".$linkToSample[1][0]."\"";
			}
			echo " title=";
			if(count($title[1]) == 0) echo "\"NA\"";
			else {
				$title[1][0] = htmlspecialchars($title[1][0]);
				echo "\"".$title[1][0]."\"";
			}

			echo " performer=";			
			preg_match_all("/$performerRegRaw/", $out[0][$i], $performerRawOut);
			if(count($performerRawOut[0]) == 0) {echo "\"NA\"";}
			else {
				preg_match_all("/$performerReg/", $performerRawOut[0][0], $performer);
				echo "\"";
				$performer[1][0] = htmlspecialchars($performer[1][0]);
				echo $performer[1][0];
				for($j=1; $j<(count($performer[1]));$j++) {
					$performer[1][$j] = htmlspecialchars($performer[1][$j]);
					echo "/".$performer[1][$j];
				}				
				echo "\"";
			}
			
			echo " composer=";
			preg_match_all("/$composerRegRaw/", $out[0][$i], $composerRawOut);
			if(count($composerRawOut[0]) == 0) {echo "\"NA\"";}
			else {
				preg_match_all("/$composerReg/", $composerRawOut[0][0], $composer);
				echo "\"";
				$composer[1][0] = htmlspecialchars($composer[1][0]);
				echo $composer[1][0];
				for($j=1; $j<(count($composer[1]));$j++) {
					$composer[1][$j] = htmlspecialchars($composer[1][$j]);
					echo "/".$composer[1][$j];
				}				
				echo "\"";
			}
			
			echo " details=";
			if(count($linkToSong[0]) == 0) echo "\"NA\"";
			else {
				$linkToSong[1][0] = htmlspecialchars($linkToSong[1][0]);
				echo "\"".$linkToSong[1][0]."\"";}
			
			echo " />\n";
		}
		echo "</results>\n";
	}
}
die;
?>
