<?php
    header('Content-Type: text/html; charset=UTF-8');
    if(isset($_GET["data"]))    #Checks if there is data in the link.
    {
        $data = $_GET["data"];  #In case there is, it is stored in $data var
    }
    else {
        echo "Error! Please access this site by traditional means, huckster!";
    }
    $con = mysqli_connect('localhost', 'root', '', 'pokemons');   #connect to mysql database
    if ($con->connect_error) {  #testing connection
        die("Connection failed: " . $con->connect_error);   
      }
    $sql_request = "SELECT * FROM pokemons WHERE name=\"$data\";"; #sql request to get all data attached to individual pokemon
    $sql_data = $con->query($sql_request);  #make the sql request
    while($row = $sql_data->fetch_assoc()) {    #Storing all the data from sql request which needs to be reformatted into global pokemon_data var
        $pokemon_data = array($row['identifier'], $row['name'], $row['image_link'],
        $row['id_number'], $row['type1'], $row['type2'], $row['nature'],
        $row['height'], $row['weight'], $row['ability'],
        $row['second_ability'], $row['third_ability'], $row['cuteness']);
    }
    $con->close(); #closing connection to sql table; all the data needed has been extracted
    $capitalised_name = ucwords($pokemon_data[1]);  #capitalising the name for aesthetic purpose.


    #Despite mysql being encoded in utf-8 as well as declaring it twice over here in the php file, it still shows the é as a question mark
    $cutter = substr("$pokemon_data[6]",0, strlen("$pokemon_data[6]")-4);   #this code manually just replaces the e with an é. Relies on the fact that all natures end with the word pokemon
    $pokemon_data[6] = "$cutter" . "émon";

    function nb_hearts($some_int) {
        #some_int is some integer value between 0 and 10
        #Convert it to a number of hearts on website. A 9 becomes 4 and a half hearts
        #Three possible states for image: full heart, half-heart and empty heart
        #5 spaces that need to be filled out, each with three possible values, so this function will
        #store that number, 7, for example as [2, 2, 2, 1, 0]
        #To skip a step, instead of the numbers 2, 1 or 0, it'll just be the links to the pictures
        $hearts_array = array('', '', '', '', '');
        for ($i=0; $i<=4; $i++) {
            $some_int -= 2;
            if ($some_int >= 0) {
                $hearts_array[$i] = 'images/heart.png';
            }
            else if ($some_int == -1) {
                $hearts_array[$i] = 'images/half-heart.png';
            }
            else {
                $hearts_array[$i] = 'images/empty-heart.png';
            }
        }
        return $hearts_array;
    }
    $hearts = nb_hearts($pokemon_data[12]); #calling hearts function cuteness value from table

    if ($pokemon_data[5] == "") {
        $pokemon_data[5] = $pokemon_data[4]; #if pokemon has no second type, this just assigns the first type twice
        #looks better on website with two pictures, although the second one is redundant
    }
    #Copy pasted the website we made elsewhere inside echo statement. The sample page was based off of one pokemon, but generalised simply by replacing its individual characteristics (its name, abilites, type, etc.) with the data from SQL command passed previously for the pokemon they clicked on

    echo "<!DOCTYPE html>
    <html>
        <head>
            <title>$capitalised_name</title>
            <link rel='stylesheet' type='text/css' href='IndividualPage.css'>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
            <link rel='icon' type='image/ico' href='images/favicon.ico'>
        </head>
        <body>
        
        <div class='navbar'>
            <div id='small_div'>
                <a href='DBComp.php'>Database</a>
                <img src='https://tse4.mm.bing.net/th?id=OIP.g9axD3al0AQXiIr98FOtGgHaHa&pid=Api'>
                <a href='AboutUsPokemonDB_HTML.html'>About Us</a>
                <a href='ShopPokemonDB_HTML.html'>Shop</a>
            </div>
        </div>
            <div id='main_div'>
                <h1>$capitalised_name</h1>
                <hr id='divider'>
            
                <img id='picture' src='$pokemon_data[2]'>
                <div id='info'>
                <div id='cuteness'>
                    <ul>
                        <li><img src=$hearts[0]></li>   
                        <li><img src=$hearts[1]></li>
                        <li><img src=$hearts[2]></li>
                        <li><img src=$hearts[3]></li>
                        <li><img src=$hearts[4]></li>
                    </ul>
                </div>
                <hr>
    
                <table id='pokedex_data'>
                    <tr><td>National Pokedex Number&nbsp;&nbsp;&nbsp;</td><td class='bold'>$pokemon_data[3]</td></tr>
                    <tr id='type'><td>Type&nbsp;&nbsp;&nbsp;</td><td><img src='images/" . $pokemon_data[4] . "icon.png'></td><td><img src='images/" . $pokemon_data[5] . "icon.png'></td></tr>
                    <tr><td>Species&nbsp;&nbsp;&nbsp;</td><td class='bold'>$pokemon_data[6]</td></tr>
                    <tr><td>Height&nbsp;&nbsp;&nbsp;</td><td class='bold'>$pokemon_data[7]m</td></tr>
                    <tr><td>Weight&nbsp;&nbsp;&nbsp;</td><td class='bold'>$pokemon_data[8]kg</td></tr>
                    <tr><td>Ability 1&nbsp;&nbsp;&nbsp;</td><td class='bold'>$pokemon_data[9]</td></tr>";
                    if ($pokemon_data[10] != "") {  #Not all pokemons have more than one ability, so the code only displays the text saying "Ability 2" and 3, if there either one exists
                        echo "<tr><td>Ability 2&nbsp;&nbsp;&nbsp;</td><td class='bold'>$pokemon_data[10]</td></tr>";
                    }
                    if ($pokemon_data[11] != "") {
                        echo "<tr><td>Ability 3&nbsp;&nbsp;&nbsp;</td><td class='bold'>$pokemon_data[11]</td></tr>";
                    }
                    echo "
                </table>
                </div>
            </div>
           
        </body>
    </html>";
?>



