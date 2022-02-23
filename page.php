<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Application Manga Anime</title>
	<link rel="icon" type="image/png" href="ed.png" />
	<link rel="stylesheet" type="text/css" href="style.css">
	
</head>
<body>
	<main>

	<!-- **************** SUR TOUTES LES PAGES **************** -->

		<header>
				<h1>Application Manga Anime</h1>
		</header>

		<?php

		

		//affichage('https://www.manga-sanctuary.com/bdd/series.html');
		// affichage('https://www.manga-sanctuary.com/bdd/series-lettre-A.html');
		//$array_ = creationList("https://www.manga-sanctuary.com/bdd/series.html");


	

		// $json = json_encode($array_, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );

		// $file = 'bddManga.txt';

		// $current = file_get_contents($file);

		// $current .= $json;

		// file_put_contents($file, $current);


		
		// $fp = fopen('bbdManga.csv', 'w');
		
		// foreach ($array_ as $fields) {
		//     fputcsv($fp, $fields);
		// }
		
		// fclose($fp);

		//echo var_dump($array_);


		//echo var_dump(getURLManga());

		//echo var_dump(getLettre());

		//echo (getURLManga());

		//echo var_dump(getManga());


		$html = file_get_html('https://www.beer-me.fr/tags/tag/brune/');
		// $test= $html->find("ul[class=pagination]");
		// echo $html->find("a[href=/beer/angelus-etiquette-brune-brasserie-brootcoorens-ale-belgique--JyYcvgqljAmYVJHwmCX]", 0)->innertext;
//$html->find("ul[class=pagination]", 0)->next_sibling()->children() as $beer
		
                foreach($html->find("div[class=well]", 0)->find("a[href]") as $beer){
					//echo "bouh";
                    //$a = $beer->find("a",1)->plaintext;
					//$li = $beer->find("li", 0)->plaintext;
					$beer1 = $beer->innertext;

                 	echo $beer1. "<br>";

                    echo "<br>------------------------<br><br>";

                }

		?>
	
	</main>


	
</body>
</html>