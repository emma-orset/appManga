<?php 
    
    //Permet de récupérer une liste d'URL des lettres de l'alphabet
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

    

    //A n'executer qu'une seule fois pour remplir la BDD
    //Récupère et insert tous les URLS des oeuvres
    function insertURL(){

        $urlBase = "https://www.manga-sanctuary.com";

        $manga = array();
        global $linkDB;

        $url="";

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

                $requete = "INSERT INTO url(url) VALUES (?)";

                $url = $urlBase . $a;

                $stmt=mysqli_prepare($linkDB, $requete);
                mysqli_stmt_bind_param($stmt, "s", $url);

                mysqli_stmt_execute($stmt);

                
    
            }
    
       }

    }

        //Récupère la liste des URL des oeuvres dans la BDD
        function loadURL() {
            global $linkDB;
            $sql = "SELECT * FROM `url`";
            $result = mysqli_query($linkDB, $sql);
        
            $list = array();
            while ($row = mysqli_fetch_assoc($result))
                $list[]=$row;
            
            return $list;
        }

    //A n'executer qu'une seule fois après avoir executé insertURL
    //Supprime les liens morts de la BDD
    //Requête longue
    function deleteURL(){

        global $linkDB;
        $table_urls = loadURL();

        foreach($table_urls as $url){
            ini_set('max_execution_time', 0);
            $ceURL = $url['url'];
            //$ceURL="https://www.manga-sanctuary.com/bdd/produitderive/62541-ki-oon-big-3/";
            echo "<br>OK pour ". $ceURL;

            if (file_get_contents($ceURL) === false) {

                echo '<br>Je delete ' . $ceURL;
                mysqli_query($linkDB, "DELETE FROM url WHERE url = '" .$ceURL . "'");
            }
           
        }
    }

    //A n'executer qu'une seule fois après avoir executé insertURL et deleteURL
    //Rempli la BDD de toutes les oeuvres
    //Requête longue
    function insertOeuvre(){
        global $linkDB;

        $table_urls = loadURL();
       
        $urlBase = "https://www.manga-sanctuary.com";


        foreach($table_urls as $url){

            ini_set('max_execution_time', 0);
            
            $html = file_get_html($url['url']);
            
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
                $note = (double) filter_var($note);

                $prix= $div->find("a[title=Acheter sur la FNAC]", 0)->last_child()->innertext;
                $prix = preg_replace('[,]', '.', $prix); 
                $prix = (double) filter_var($prix); 

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
                    $annee = (int) filter_var($annee); 
                    $dessinateur = searchInfos($oneSpan, $div,"Dessinateur", $dessinateur, $i);


                    $scenariste = searchInfos($oneSpan, $div,"Scénariste", $scenariste, $i);


                    $genres = searchInfos($oneSpan, $div,"Genres", $genres, $i);



                    if ($oneSpan == "Mag. prépub." and $div->find("span", $i+1)->innertext != " 								"){
                        
                        $editeur = $div->find("span", $i+1)->find("span",0)->innertext;
                        $editeur = preg_replace("[\(|\)]", '', $editeur);  
                        
   
                    }

                    $episode = searchInfos($oneSpan, $div,"Episodes", $episode, $i);
                    $episode = (int) filter_var($episode); 

                    $realisateur = searchInfos($oneSpan, $div,"Réalisateur", $realisateur, $i);
                    $chara_designer = searchInfos($oneSpan, $div,"Chara-designer", $chara_designer, $i);
                    $createur_original = searchInfos($oneSpan, $div,"Créateur original", $createur_original, $i);
                    $animation = searchInfos($oneSpan, $div,"Directeur de l'animation", $animation, $i);
                    $musique = searchInfos($oneSpan, $div,"Musique", $musique, $i);
                    $studio = searchInfos($oneSpan, $div,"Studio", $studio, $i);
                    $photo = searchInfos($oneSpan, $div,"Directeur de la photographie", $photo, $i);




                }
            }

            echo "<br>J'insert " . $h1;
            $requete = "INSERT INTO oeuvre(titre,image,type,annee,categorie,dessinateur,realisateur,scenariste,chara_designer,createur,directeur_animation,musique,studio,directeur_photographie,genre,editeur,statut,tome,episode,rang,note,prix,resume) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                $img = $urlBase .$img;
                $stmt=mysqli_prepare($linkDB, $requete);
                mysqli_stmt_bind_param($stmt, "sssisssssssssssssiiidds", $h1, 
                $img, 
                $type, 
                $annee, 
                $categorie, 
                $dessinateur, 
                $realisateur, 
                $scenariste, 
                $chara_designer, 
                $createur_original, 
                $animation, 
                $musique, 
                $studio, 
                $photo, 
                $genres, 
                $editeur, 
                $statut, 
                $tome, 
                $episode,
                $rang,
                $note,
                $prix,
                $resume);

                mysqli_stmt_execute($stmt);
    
        }
    }

    //Allègement du code quand il s'agit de chercher des champs aux caractéristiques similaires
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