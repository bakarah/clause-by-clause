<!--
USAGE:
1. run script
2. copy and paste JSON in text editor
3. replace "Allah" with "God"
4. copy and paste into JSON Formatter
5. copy and paste into GitHub
-->

<?php
	$versePointer = [0,7,293,493,669,789,954,1160,1235,1364,1473,1596,1707,1750,1802,1901,2029,2140,2250,2348,2483,2595,2673,2791,2855,2932,3159,3252,3340,3409,3469,3503,3533,3606,3660,3705,3788,3970,4058,4133,4218,4272,4325,4414,4473,4510,4545,4583,4612,4630,4675,4735,4784,4846,4901,4979,5075,5104,5126,5150,5163,5177,5188,5199,5217,5229,5241,5271,5323,5375,5419,5447,5475,5495,5551,5591,5622,5672,5712,5758,5800,5829,5848,5884,5909,5931,5948,5967,5993,6023,6043,6058,6079,6090,6098,6106,6125,6130,6138,6146,6157,6168,6176,6179,6188,6193,6197,6204,6207,6213,6216,6221,6225,6230];
	$chapters = [7,286,200,176,120,165,206,75,129,109,123,111,43,52,99,128,111,110,98,135,112,78,118,64,77,227,93,88,69,60,34,30,73,54,45,83,182,88,75,85,54,53,89,59,37,35,38,29,18,45,60,49,62,55,78,96,29,22,24,13,14,11,11,18,12,12,30,52,52,44,28,28,20,56,40,31,50,40,46,42,29,19,36,25,22,17,19,26,30,20,15,21,11,8,8,19,5,8,8,11,11,8,3,9,5,4,7,3,6,3,5,4,5,6];

	$arabicJson = file_get_contents('quran-simple-enhanced.json');
	$arabicArray = json_decode($arabicJson, true); // decode the JSON into an associative array
	$arabicArray = $arabicArray["quran"]["quran-simple-enhanced"];

	$englishJson = file_get_contents('en.sahih.json');
	$englishArray = json_decode($englishJson, true); // decode the JSON into an associative array
	$englishArray = $englishArray["quran"]["en.sahih"];

	function countSentences($verse, $matches) {
		return preg_match_all('/([^,-\.\!\?]+[,-\.\?\!]*)/', $verse, $match);
	}

	/*$chapter = 1;
	$fromVerse = 1;
	$toVerse = 7;*/

	$chapter = 2;
	$fromVerse = 1;
	$toVerse = 286;

	/*$clausesArabic = [];
	$clausesArabicTemp["clause"] = "";
	array_push($clausesArabic, $clausesArabicTemp);
	array_push($clausesArabic, $clausesArabicTemp);
	array_push($clausesArabic, $clausesArabicTemp);
	array_push($clausesArabic, $clausesArabicTemp);*/

	/*$clausesEnglish = [];
	$clausesEnglishTemp["clause"] = "";
	array_push($clausesEnglish, $clausesEnglishTemp);
	array_push($clausesEnglish, $clausesEnglishTemp);
	array_push($clausesEnglish, $clausesEnglishTemp);
	array_push($clausesEnglish, $clausesEnglishTemp);*/

	foreach ($chapters as $index => $numVerses) {

		$chapter = $index + 1;
		$fromVerse = 1;
		$toVerse = $numVerses;

		$temp = [];
		$temp2 = [];
		$output = [];

		$counter = 1;

		for ($row = $fromVerse + $versePointer[$chapter-1]; $row <= $toVerse + $versePointer[$chapter-1]; $row++) {

			$numSentences = preg_match_all('/([^:;,-\.\!\?]+[:;,-\.\?\!]*)/', $englishArray[$row]['verse'], $match);


			$clausesEnglish = [];
			$clausesArabic = [];

			foreach ($match[0] as $key => $value) {
				$clausesEnglishTemp["clause"] = str_replace("Allah", "God", trim($value));
				array_push($clausesEnglish, $clausesEnglishTemp);

				$clausesArabicTemp["clause"] = "";
				if ($numSentences === 1) {
					$clausesArabicTemp["clause"] = $arabicArray[$row]['verse'];
				}

				array_push($clausesArabic, $clausesArabicTemp);
			}

			$temp["chapter"] = $chapter;
			$temp["verse-number"] = $counter;
			$temp["verse-arabic"] = $arabicArray[$row]['verse'];
			$temp["verse-english"] = $englishArray[$row]['verse'];
			$temp["clauses-arabic"] = $clausesArabic;
			$temp["clauses-english"] = $clausesEnglish;

			$temp2[$row] = $temp;

			$fromVerse = $fromVerse + 1;
			$counter = $counter + 1;
		}

		array_push($output, $temp2);

		$outputJson = json_encode($output[0], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

		$my_file = 'chapter' . $chapter . '.json';
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		$data = $outputJson;
		fwrite($handle, $data);
		fclose($handle);

		//echo($outputJson);
	}
?>