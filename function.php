<?php 
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


    function getLettre(){

        $urlBase = "https://www.manga-sanctuary.com";

        $html = file_get_html($urlBase . "/bdd/");

        $lettre = array();

        foreach($html->find("ul[class=barre_nav_lettres]") as $ul){

            for($i=0; $i<=26; $i++){

                $a = $ul->find("a", $i)->getAttribute('href');
                array_push($lettre, $urlBase . $a);
            }
        }

       return $lettre;

    }

    function getURLManga(){

        $urlBase = "https://www.manga-sanctuary.com";

        $test = "https://www.manga-sanctuary.com/bdd/series-lettre-A.html";

        $manga = array();

        foreach(getLettre() as $lettre){
            
            $html = file_get_html($test);

            //$html = file_get_html($test);
            

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

    function getManga(){

        $manga = array();
        $urlBase = "https://www.manga-sanctuary.com";

        foreach(getURLManga() as $url){

            ini_set('max_execution_time', 0);
            
            $html = file_get_html($url);

            //$html = file_get_html("https://www.manga-sanctuary.com/bdd/manga/53529-29-sai-hitorimi-chuuken-boukensha-no-nichijou/");
            

            foreach($html->find("div[id=fiche-contenu]") as $div){

                $h1 ="";
                $img ="";


                $type = "";
                $categorie = "";
                $annee = "";
                $dessinateur = "";
                $scenariste = "";
                $genres = "";
                $editeur = "";

                $episode = "";
                $realisateur ="";
                $chara_designer ="";
                $createur_original ="";
                $animation ="";
                $musique ="";
                $studio = "";
                $photo = "";

                $statut = "";
                $tome = "";

                $rang ="";
                $note="";
                $prix="";
                $resume="";


                $h1 = $div->find("h1",0)->innertext;
                $img = $div->find("img", 0)->getAttribute("src");

                $rang = $div->find("ul[id=fiche-stats]",0)->first_child()->plaintext;
                $rang = (int) filter_var($rang, FILTER_SANITIZE_NUMBER_INT); 

                $note = $div->find("div[class=row m-3 text-center fiche-notes]",0)->first_child()->first_child()->first_child()->last_child()->prev_sibling ()->innertext;
                $note = (float) filter_var($note);

                $prix= $div->find("a[title=Acheter sur la FNAC]", 0)->last_child()->innertext;
                $prix = preg_replace('[,]', '.', $prix); 
                $prix = (float) filter_var($prix); 

                $resume=$div->find("div[id=barre-affiliation-fiche]", 0)->next_sibling()->find("p",0)->innertext;
                
                $span = $div->find("span");

                for($i=0; $i<sizeof($span); $i++){
                    $oneSpan = $div->find("span", $i)->innertext;
                    

                    $type = searchInfos($oneSpan, $div,"Type", $type, $i);

                    if($type == "Manga" and $div->children(0)->children(2)->find("ul",0)->last_child()->last_child()->innertext !== ''){

                            $statut = $div->children(0)->children(2)->find("ul",0)->last_child()->find("span",0)->plaintext;
                            $statut = preg_replace('[\<|\/|\i|\>| ]', '', $statut);  
                        
        
                            $tome = $div->children(0)->children(2)->find("ul",0)->last_child()->find("div",1)->innertext;
                            $tome = (int) filter_var($tome, FILTER_SANITIZE_NUMBER_INT); 
                        

                    }

                    $categorie = searchInfos($oneSpan, $div,"Catégorie", $categorie, $i);

                    $annee = searchInfos($oneSpan, $div,"Année", $annee, $i);
                    $dessinateur = searchInfos($oneSpan, $div,"Dessinateur", $dessinateur, $i);


                    $scenariste = searchInfos($oneSpan, $div,"Scénariste", $scenariste, $i);


                    $genres = searchInfos($oneSpan, $div,"Genres", $genres, $i);



                    if ($oneSpan == "Mag. prépub." and $div->find("span", $i+1)->innertext != " 								"){
                        
                        $editeur = $div->find("span", $i+1)->find("span",0)->innertext;
                        $editeur = preg_replace("[\(|\)]", '', $editeur);  
                        
   
                    }

                    $episode = searchInfos($oneSpan, $div,"Episodes", $episode, $i);
                    $realisateur = searchInfos($oneSpan, $div,"Réalisateur", $realisateur, $i);
                    $chara_designer = searchInfos($oneSpan, $div,"Chara-designer", $chara_designer, $i);
                    $createur_original = searchInfos($oneSpan, $div,"Créateur original", $createur_original, $i);
                    $animation = searchInfos($oneSpan, $div,"Directeur de l'animation", $animation, $i);
                    $musique = searchInfos($oneSpan, $div,"Musique", $musique, $i);
                    $studio = searchInfos($oneSpan, $div,"Studio", $studio, $i);
                    $photo = searchInfos($oneSpan, $div,"Directeur de la photographie", $photo, $i);




                }
                


                array_push($manga, 
                    array(
                        "Titre" => $h1, 
                        "Image" => $urlBase . $img, 
                        "Type" => $type, 
                        "Année" =>  $annee, 
                        "Catégorie" => $categorie, 
                        "Dessinateur" =>  $dessinateur, 
                        "Réalisateur"=> $realisateur, 
                        "Scénariste" =>  $scenariste, 
                        "Chara-designer"=>$chara_designer, 
                        "Créateur original" => $createur_original, 
                        "Directeur de l'animation"=>$animation, 
                        "Musique"=>$musique, 
                        "Studio"=>$studio, 
                        "Directeur de la Photographie" => $photo, 
                        "Genre" =>  $genres, 
                        "Editeur" => $editeur, 
                        "Statut" =>  $statut, 
                        "Tome" =>  $tome, 
                        "Episode" => $episode,
                        "Rang"=>$rang,
                        "Note"=>$note,
                        "Prix"=>$prix,
                        "Résumé"=>$resume)
                    );
    
            }
    
        }
        return $manga;
    }


    function searchInfos($span, $balise, $string, $attr, $i){
        if ($span ==  $string){
            $search = $balise->find("span", $i+1);
                for($j=0; $j<sizeof($search->children()); $j++){
                    if (sizeof($search->children())>1){
                        $attr = $search->children($j)->innertext . " " . $attr;
                    }
                    else{
                        $attr = $attr . $search->children($j)->innertext;
                    }
                        
                }      
        }

        return $attr;

    }

    

?>