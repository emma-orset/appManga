<?php

//Requête trop longue et trop lourde
function getURLManga(){

    $urlBase = "https://www.manga-sanctuary.com";

    $manga = array();

    foreach(getLettre() as $lettre){
        ini_set('max_execution_time', 0);
        $html = file_get_html($lettre);
        

        foreach($html->find("tr[style]") as $tr){

            
            $a = $tr->find("a[href]",0);
           
            if ($a === null) {
                $a ="";
            }

            else{

                $a = $a->getAttribute('href');
            
            }

            array_push($manga, $urlBase . $a);

        }

   }

    return $manga;

}


//ancien test, pourra être supprimé
function affichage($url){

    $html = file_get_html($url);


            foreach($html->find("tr[style]") as $tr){

                $a = $tr->find("a",0)->innertext;
                $span = $tr->find("span", 0)->innertext;
                $td1 = $tr->find("td", 1)->innertext;
                $td2 = $tr->find("td", 2)->innertext;
                $td3 = $tr->find("td", 3)->innertext;

                $img = $tr->find("img" );

                echo $a. "<br>";
                echo $span. "<br>";
                echo $td1. "<br>";
                echo $td2. "<br>";
                echo $td3. "<br>";

                for($i=0; $i<sizeof($img); $i++){
                    echo $img[$i]->getAttribute('src'). "<br>";						
                }

                

                echo "<br>------------------------<br><br>";

            }
}

//ancien test, pourra être supprimé
function creationList($url){

    $html = file_get_html($url);

    $oeuvre = array();

    foreach($html->find("tr[style]") as $tr){

        $uneOeuvre = array();
       

        $a = $tr->find("a",0)->innertext;
        $span = $tr->find("span", 0)->innertext;
        $td1 = $tr->find("td", 1)->innertext;
        $td2 = $tr->find("td", 2)->innertext;
        $td3 = $tr->find("td", 3)->innertext;

        $img = $tr->find("img");

        if ($td3 == "***"){
            $td3_= "";
        }

        else{
            
            $a_ = $a;
            $span_ = $span;
            $td1_ = $td1;
            $td2_ = $td2;
            $td3_ = $td3;
        }

        for($i=0; $i<sizeof($img); $i++){
            $img_= $img[$i]->getAttribute('alt');						
        }

        array_push($uneOeuvre, $a_, $span_, $td1_, $td2_, $td3_,$img_ );
        array_push($oeuvre, $uneOeuvre);

        }
    return $oeuvre;
}

// A finir un jour ?
function afficheManga(){
    global $linkDB;
    $start 
    while()
    $requete = "select * from oeuvre limit {$start}, {$nb}";

    $stmt=mysqli_prepare($linkDB, $requete);

    mysqli_stmt_execute($stmt);


}


?>