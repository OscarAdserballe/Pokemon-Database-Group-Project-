<!DOCTYPE html>

<HTml>


<head>
     <link rel= "stylesheet" href= "pokemonStyle.css">
      
     <link rel="shortcut icon" href="images/favicon.ico" type="image/type-icon">
     <script>
        /*TO OSCAR AND SAMO: I changed the name of the favicon so the code might make an error if you run it as is */
        function funct (g){
            console.log(g);
        }
     
     function theDrop (a, c){
          document.getElementById(a).classList.remove("myDropdown");
          document.getElementById(a).classList.add("Appear");
          document.getElementById(c).classList.remove("typeimg");
          document.getElementById(c).classList.add("myDropdown");
     }

     function theUp (b, d){
          document.getElementById(b).classList.remove("Appear");
          document.getElementById(b).classList.add("myDropdown");
          document.getElementById(d).classList.remove("myDropdown");
          document.getElementById(d).classList.add("typeimg");
     }
     var type1 = "";
     var type2 = "";
     /*type1 will always be the most recent checkbox the user has selected*/
     function uncheckType (x){
         if (type1 == x){
               /* If the user manually unchecks a checkbox*/
               type1= type2;
               type2 = "";
         }else if((type1 == "")&& (type2 == "")){
               /*If no checkboxes are checked and the user manually checks a checkbox*/
               type1 = x;
         }else if (type2 == ""){
               /*If one checkbox has already been checked and the user checks another different checkbox*/
               type2 = type1;
               type1 = x;
         }else if (type1 != "" && type2 != ""){
               /*If the user attempts to select a third checkbox, this will uncheck the least recent checkbox the user has selected*/
               document.getElementById(type2).checked = false;
               type2 = type1;
               type1 = x;
         }
     }
    </script>   
        <?php
        header("Content-Type: text/html; charset=UTF-8");
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $con = mysqli_connect($dbhost, $dbuser, $dbpass, 'pokemons');

         /*These variables guarantee we get the right order of the table rows. isset(x) just checks if x exists or not */ 
         /*$x = a ? b:c  means $x =b is a is true and $x=c if a is false. Hence the right most option, the 'c' for all option is the default order if you click on a column */
         /*b will only be the alternating order if you had already clicked on the given column */
        $order2 = isset($_GET['dir']) && ($_GET['dir'] == "" && $_GET['column'] == "name")? "DESC": "";/*deterlines the order if you click on name */

        $order3 = isset($_GET['dir']) && ($_GET['dir'] == "DESC"  &&  $_GET['column'] == "cuteness")? "": "DESC";/* determines the order if you click on cuteness */

        $order4 = isset($_GET['dir']) && ($_GET['dir'] == "DESC" && $_GET['column'] == "height")? "": "DESC";

        $order5 = isset($_GET['dir']) && ($_GET['dir'] == "DESC" && $_GET['column'] == "weight")? "": "DESC";

        $test =  isset($_GET['dir']) && $_GET['dir'] == "DESC"? 'DESC': "";
        if(isset($_GET['search'])){
             $intval = intval($_GET['search']);
        }
        if(isset($_GET['search']) AND $_GET['search'] != "") {
               $search = strval($intval) == $_GET['search'] ? " WHERE identifier LIKE " .  " '$intval%'": " WHERE name LIKE " .  "'$_GET[search]%'";
        } else{
               $search = "";
         }
          /*the default page will have no 'column=x' in the file directory so this just makes sure it defaults id in ascending order*/ 
          if(!isset($_GET['column']) && !isset($_GET['dir'])){
               $order2 = "";
               $order3 = "DESC";
               $order4 = "DESC";
               $order5 = "DESC";
          }
          
          $order1 = isset($_GET['dir']) && ($_GET['dir'] == "" && $_GET['column'] == "identifier")? "DESC": "";
          $typeContinue = "";
          $column = isset($_GET['column']) ? "ORDER BY " . $_GET['column'] ." " . $test : "";
          $searchType = "";
          $counting = 0;
         if(isset($_GET['type'])){
            foreach($_GET['type'] as $su){
               $sub = strtolower($su);
               if($counting == 0 && (!isset($search) || $search == "")){
                  /*If we initiate this condidition with WHERE when Search is a filled in value then the sql will of form SELECT * FROM pokemon WHERE x WHERE y which will produce an error*/
                 /*Also if we've already attributed the conditions for pokemon types then we want to avoid the same error, hence $counting changes in order to avoid this issue */
                  $searchType = $searchType . " WHERE (type1 =" . "\"$sub \" ". " OR type2 =" . "\"$sub\" " .")";
                  $counting = $counting +1;
               } else{
                  
                  $searchType = $searchType . " AND (type1 =" . "\"$sub \" ". " OR type2 =" . "\"$sub\" " .")";
               }
               $typeContinue = $typeContinue . "&type%5B%5D=" . "$su";
            }

         }

      $count = 1;
      #This if statement ensures that irrespective of which values are sent in whichever order
      if(isset($_GET['cuteness1']) && isset($_GET['cuteness2']) && $_GET['cuteness1'] != "" && $_GET['cuteness2'] != ""){
         if($_GET['cuteness1'] > $_GET['cuteness2']){
            $cute2 = $_GET['cuteness1'];
            $cute1 = $_GET['cuteness2'];
         } else if ($_GET['cuteness1'] <= $_GET['cuteness2']){
            $cute1 = $_GET['cuteness1'];
            $cute2 = $_GET['cuteness2'];
         }
         if($counting == 0 && (!isset($search) || $search == "" && (!isset($_GET['type'])))){
            $cutes = " WHERE cuteness BETWEEN $cute1 AND $cute2";
         } else{
            $cutes = " AND cuteness BETWEEN $cute1 AND $cute2";
         }
      } else {
            $cutes = "";
      }
      $sqlP = "SELECT * FROM `pokemons` " . $search .  $searchType . $cutes . $column;
      #$element caches the data subset of the all the data in the localhost as specified by the query $sqlP, it is not yet an array, it is a variable of type sqli_object
      $element = $con->query($sqlP); 

      echo "</head>
      <body>";
         echo "<div class='navbar'>
                  <div id='small_div'>
                     <a href='DBComp.php'>Database</a>
                     <img src='https://tse4.mm.bing.net/th?id=OIP.g9axD3al0AQXiIr98FOtGgHaHa&pid=Api'>
                     <a href='AboutUsPokemonDB_HTML.html'>About Us</a>
                     <a href='ShopPokemonDB_HTML.html'>Shop</a>
                  </div>
               </div>";
         echo"<div id=\"formDiv\"><form action = \"DBComp.php\" method=\"get\">

         <h2>Search:</h2>
         <div class = searchdiv> <input type=text name=search class=search></div>
         <h2>Types</h2>";
         $counting =0;
         $types = array('Normal','Fire','Fighting','Water','Flying','Grass','Poison','Electric','Ground','Psychic','Rock','Ice','Bug','Dragon','Ghost','Dark','Steel','Fairy');
         /* The code within the following if function is to check the checkboxes that the user selected in the form */
         if(isset($_GET['type'])){ /*This code will run an error if there is no'type= blah' in the directory*/
            foreach($_GET['type'] as $same){
               if($counting == 0){/*it will loop once if only one type was selected*/
                  $var1 = $same;
                  $var2 = "";
                  $counting = $counting +1;
               }else{/*second 'var' is given a correct value only if there are two types selected */
                  $var2 = $same;
               }
            }
          
            foreach($types as $t){
               $checker = $t == $var1 || $t == $var2 ? "checked": "unchecked" ;
               if($t == 'Psychic'){/* Typo in neocities images, this is just to correct it */
                  echo"<div width = 75px ><label> <img src=https://pokeguide.neocities.org/Pic/physicicon.png id=\"typeimg\"> </label><input type=checkbox name=type[]  id =$t class=type value=$t" . " $checker" ." onclick='uncheckType(\"$t\")'> </div>";
               } else{
                     echo"<div width = 75px ><label> <img src=https://pokeguide.neocities.org/Pic/" . strtolower("$t") . "icon.png id=\"typeimg\"> </label><input type=checkbox name=type[] id = $t class=type value=$t" . " $checker" ." onclick='uncheckType(\"$t\")'> </div>";
               }
            }
         }else {/* If no type has been selected then just print all the checkboxes unchecked */
            foreach($types as $t){
               if($t == 'Psychic'){
                  echo"<div width = 75px ><label> <img src=https://pokeguide.neocities.org/Pic/physicicon.png id=\"typeimg\"> </label><input type=checkbox name=type[] class=type value=$t id =$t unchecked onclick='uncheckType(\"$t\")'> </div>";
               } else{
                  echo"<div width = 75px ><label> <img src=https://pokeguide.neocities.org/Pic/" . strtolower("$t") . "icon.png id=\"typeimg\"> </label><input type=checkbox name=type[] class=type id=$t value=$t unchecked onclick='uncheckType(\"$t\")'> </div>";
               }
            }
         }
         echo"<select name= \"cuteness1\">
            <option value = \"\">-- </option>
            <option value = \"0\">0 </option>
            <option value = \"1\">1</option>
            <option value = \"2\">2 </option>
            <option value = \"3\">3 </option>
            <option value = \"4\">4 </option>
            <option value = \"5\">5 </option>
            <option value = \"6\">6 </option>
            <option value = \"7\">7 </option>
            <option value = \"8\">8 </option>
            <option value = \"9\">9 </option>
            <option value = \"10\">10 </option>
         </select>";
         echo"<select name= \"cuteness2\">
            <option value = \"\">-- </option>
            <option value = \"0\">0 </option>
            <option value = \"1\">1</option>
            <option value = \"2\">2 </option>
            <option value = \"3\">3 </option>
            <option value = \"4\">4 </option>
            <option value = \"5\">5 </option>
            <option value = \"6\">6 </option>
            <option value = \"7\">7 </option>
            <option value = \"8\">8 </option>
            <option value = \"9\">9 </option>
            <option value = \"10\">10 </option>
         </select>";
         echo "</form> </div>";
         echo "<table id=\"Compdb\">";
         echo "<th>Img</th><th><a href=\"DBComp.php?column=identifier&dir=$order1" . "$typeContinue" ."\" id=\"avisit2\"> Dex n° </a></th><th><a href=\"DBComp.php?column=name&dir=$order2" . "$typeContinue" ."\" id=\"avisit2\"> Name </a></th><th> Type</th> <th>Nature</th> <th><a href =\"DBComp.php?column=height&dir=$order4" . "$typeContinue" ."\" id=\"avisit2\"> Height</a></th> <th><a href =\"DBComp.php?column=weight&dir=$order5" . "$typeContinue" ."\" id=\"avisit2\"> Weight</a> </th><th><a href =\"DBComp.php?column=cuteness&dir=$order3" . "$typeContinue" ."\" id=\"avisit2\"> Cuteness</a></th>";
        while($row = $element->fetch_assoc())
        {
            $len = strlen("$row[nature]");
            $cutter = substr("$row[nature]",0, "$len"-4);
            $nature = "$cutter" . "é" . "mon";
            /*There is a typo in the site we're getting the psychic type icon from, this if statement just corrects it for us*/ 
            if("$row[type2]" == "psychic"){
               $type2 = "<img src=https://pokeguide.neocities.org/Pic/physicicon.png id=\"typeimg\">";
            } else {
               $type2 = "$row[type2]" != ""? "<img src=https://pokeguide.neocities.org/Pic/" . "$row[type2]" . "icon.png id=\"typeimg\">" : "";
            }
            if("$row[type1]" == "psychic"){
               $type1 = "physic";
            } else {
               $type1 = "$row[type1]";
            }
            /*dividing by 2 is convenient since for a score of 10, there will be 5 heart images*/
            $hearts = $row['cuteness']/2;
            /*The string we're going to add image tags to*/ 
            $heartI = "";
            /*Adds the right amoung of full hearts if cuteness > 1*/
            for ($x = 0; $x < floor($hearts); $x++){
               $heartI = $heartI . "<img src='images/heart.png' id=\"typeimg\"> ";
            }
            /*Half heart, there will always be one if one is needed and only when cuteness is an odd number, hence the if statement*/
            if (floor($hearts) - $hearts != 0){
               $heartI = $heartI . "<img src ='images/half-heart.png' id=\"typeimg\">";
            }
            /*Adds the right amount of full hearts if cuteness < 9*/
            for ($x = 0; $x < floor(5-$hearts); $x++){
               $heartI = $heartI . "<img src='images/empty-heart.png' id=\"typeimg\"> ";
            }
            $name = ucfirst($row['name']);
            $minImg = "https://img.pokemondb.net/sprites/sword-shield/icon/" . "$row[name]" . ".png";
            /*this if statement if just so that one row is grey and the other isn't, hence the different ids "row" and "row2", with the css this will change the colour*/
            /*The rest is very long but self explanatory, add all the values from the 'pokemon' table, some of it with help of other variables, e.g. $names instead of $row[names] just for capitalisation*/
            if(is_int($count/2)){
                    echo " <tr id=\"row2\" onmouseover = 'theDrop(\"$row[name]\", \"$row[identifier]\")' onmouseout = 'theUp(\"$row[name]\", \"$row[identifier]\")' >
                  <td> <div> <img src = $row[image_link] width = 200px class = \"myDropdown\" id = \"$row[name]\"></img>
                  <img src = $minImg class = \"typeimg\" id=\"$row[identifier]\"></div></td> <td> $row[identifier]</td> 
                  <td> <a href=\"IndividualPage.php?data=$row[name]\" id=\"avisit\">$name</a>
                  </td><td > <img src=https://pokeguide.neocities.org/Pic/" . "$type1" . "icon.png id=\"typeimg\">" .  " $type2</td> <td>$nature</td> <td> $row[height]</td>
                  <td> $row[weight]</td> <td> $heartI </td></tr>";
            }
            else{
                    echo "<tr id = \"row\" onmouseover = 'theDrop(\"$row[name]\", \"$row[identifier]\")' onmouseout = 'theUp(\"$row[name]\", \"$row[identifier]\")'>
                     <td> <div> <img src = $row[image_link] width = 200px class = \"myDropdown\" id = \"$row[name]\"></img>
                     <img src = $minImg class = \"typeimg\"   id=\"$row[identifier]\"></div></td> <td> $row[identifier]</td> <td>
                     <a href=\"IndividualPage.php?data=$row[name]\" id=\"avisit\">$name</a></td> <td>
                     <img src=https://pokeguide.neocities.org/Pic/" . "$type1" . "icon.png id=\"typeimg\">" . " $type2</td> <td>$nature</td>
                       <td> $row[height]</td> <td> $row[weight]</td> <td> $heartI</td> </tr>";
            }
            $count = $count +1;
        }
        echo "</table>";
        
        ?>
        </body>
      </HTml>        