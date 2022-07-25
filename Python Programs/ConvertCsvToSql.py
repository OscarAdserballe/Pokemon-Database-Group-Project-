#The purpose of this program is to print out a text that could make our entire SQL databse in one command
from csv import reader  #using this module to read csv file where the data from the webscraper is placed
#Line of code to create the table itself:
print("CREATE TABLE IF NOT EXISTS pokemons(identifier INTEGER PRIMARY KEY AUTO_INCREMENT,\
 name TEXT NOT NULL,\
 image_link TEXT NOT NULL,\
 id_number INTEGER NOT NULL,\
 type1 TEXT NOT NULL,\
 type2 TEXT NOT NULL,\
 nature TEXT NOT NULL,\
 height FLOAT NOT NULL,\
 weight FLOAT NOT NULL,\
 ability TEXT NOT NULL,\
 second_ability TEXT NOT NULL,\
 third_ability TEXT NOT NULL,\
 cuteness INTEGER NOT NULL);")

#Array containing all the different types. Using a for loop later to quickly scan through the huge string in the type entry in the csv file to clean it.
#Originally, we would just copy the code straight into the php site directly, but that wasn't really possible with the design we wanted, and this just makes the SQL table slightly nicer and readable
pokemon_types = ["bug", "dark", "dragon", "electric", "fairy", "fighting", "fire", "flying", "ghost", "grass", "ground", "ice", "normal", "poison", "psychic", "rock", "steel", "water"]   
# open file in read mode
with open("pokemons.csv", "r", encoding="utf-8") as read_obj:
    # pass the file object to reader() to get the reader object
    csv_reader = reader(read_obj)
    # Iterate over each row in the csv using reader object
    for row in csv_reader:
        # row variable is a list that represents a row in csv
        #row[5] is the height and row[6] the weight
        index_to_end = row[5][1:].find(" ")
        row[5] = row[5][1:index_to_end]   #height given now as a float in metres

        index_to_end = row[6][1:].find(".") + 3
        row[6] = row[6][1:index_to_end]   #weight given now as a float in kg
        
        #cleaning the string containing the type(s) of the pokemon:
        types = []
        for typing in pokemon_types:    #loops through list of types
            if typing in row[3]:    #if type is contained in the string with the typing in the csv file, row[3], it adds the type to the list of "types"
                types.append(typing)
        if len(types) > 1:  #if it has two types
            type1, type2 = types   
        else:
            type1 = types[0]
            type2 = ""  #Empty string if pokemon has no second type
        row[3] = type1  #Only one entry in the csv file is designated for the type, but some pokemon have two, so another entry is created in the next line containing the second type. If it has no second type, it still creates it, but leaves it blank
        row.insert(4, type2)

        

        #This next part is meant to get the abilities of the pokemon
        #This was by far the hardest part to clean, simply because of the way the data in the csv file is structured in the csv file
        #row[8] up to and including row[10] can all hypothetically contain the up to 3 abilities a given pokemon has
        #however, in many cases all two or three abilities were stored in row[8]
        #Some rows had row[8] as empty, and three abilities distributed between row[9] and row[10], so a lot of variability, hence the not so clean solution that follows.

        abilities = []  #list to store abilities
        
        for num in range(8, 11):        #Goes through all the columns where the abilites are stored. However, the data points are very variable. Some entries contain 3 abilites, some 2 etc.
            #As a result, we have to loop through the entire string making sure that we catch all of the abilities stored in the given entry/cell
            list_of_a_end_tags_indexes = [] #temporarily store the points where the </a> tags are. That's what all the abililities have in common: they are placed right before an </a>
            iterator = 0
            while iterator < len(row[num]):     #making sure we catch all the tags -> looping through the entire string
                new_index = row[num].find("</a>", iterator) #iterator is the start position in the string for the find function
                if new_index not in list_of_a_end_tags_indexes and new_index != -1:     #Because we loop through it, it finds the same </a> tag many times. This makes sure that we don't repeat it.
                    #If there are no </a> tags left, it returns -1, so we have to ignore that. In hindsight, it may have been wise to use 'break' here, as all the points where abilites are have been extracted.
                    list_of_a_end_tags_indexes.append(new_index)
                iterator += 1

            for i in list_of_a_end_tags_indexes:
                abilities.append(row[num][row[num].find(">", i-20, i)+1:i])     #Finds the '>' that comes right before the ability name. To make sure it doesn't pick up any random one before the ability, we have
                #arbitrarily chosen it to start 20 characters away from the ending position, which we identified with the end_tags_indexes. No ability names are longer than some 15 characters, so it doesn't raise any errors.
                #Much of this was chosen merely by trial and error, trying to find the best ways to handle the data. This does mean that in case of an update, it may not function properly.
            
            
            
        for j in range(len(abilities)): #Now that the list of abilities is complete, this for loop places it back into the row. If there is one ability, j stops after it was zero, thus only filling row[8]. With two, j is 1 and 2 and fills row[8] and row[9] etc.
            row[8+j] = abilities[j]

        #This way of cleaning the abilities, despite being the 3rd or 4th try, is not perfect. It does catch all of the abilities (at least all of the 20 or so pokemon I've crosschecked as a test), but some strings just aren't fully cleaned
        #Some of the SQL database, thereby was manually modified, but it was less than 1% of entries. 




        #Old code, although a bit worse at parsing the abilities (a lot of manual sifting through the data would have been necessary), is able to determine whether an ability is hidden or not
        #Ideally, if we had more time, snippets of this code would have been inserted into the above code to capture the detail of whether an ability was hidden or not.
        """if row[num] == "":
            continue
        else:
            if "(hidden ability)" in row[num]:
                point = row[num].find("(hidden ability)")
                if num+1 == 11:
                    num -= 1
                row[num+1] = row[num][point-25:point]
                row[num] = row[num][0: point-25]
                
                #print(row[num])
                row[num+1] = row[num+1][row[num+1].find(">")+1:row[num+1].find("<")] + " (hidden)"
                place_to_end = row[num].find("</a>")
                temp_string = row[num][place_to_end-15:place_to_end]
                #print(temp_string)
                row[num] = temp_string[temp_string.find(".\">")+3:]
                if verifyNoMoreThanOneSpace(row[num]):
                    row[num] = ""
                #print(row[num])
                break
            else:
                place_to_end = row[num].find("</a>")
                temp_string = row[num][place_to_end-20:place_to_end]
                row[num] = temp_string[temp_string.find("\">")+2:]
                if verifyNoMoreThanOneSpace(row[num]):
                    row[num] = ""
        
        if row[9] == "" and row[10] != "":
            row[9] = row[10]
            row[10] = ""
        """


                #print(row[num][index_to_start_at:index_to_end])
                #temp = row[num][row[num].find("</a></span>"):row[num].find("title")]    #This middlestep is necessary for enabling the next slicing, to finally get the ability name
                #row[num] = temp[temp.find("/")+1:temp.find("\"")]
        #print(row[9])
        
        


        #Finally, all the data in the row has been cleaned, so the individual entry insertion code for the given pokemon can finally be printed

        print("INSERT INTO pokemons(name, image_link, id_number, type1, type2, nature, height, weight, ability, second_ability, third_ability, cuteness) VALUES("\
+ "'" +row[0] + "' , '" + row[1] + "' , "\
+ row[2]+ ", '" + row[3] + "' , '" + row[4] + "', '"\
+ row[5] + "' , " + row[6][:-1] + ", "\
+ row[7] + ", '" + row[8].replace("'", "\\'") + "', '"\
+ row[9].replace("'", "\\'") + "', '" + row[10].replace("'", "\\'") + "', '" + row[11] + "');")



#Now, we just paste the output of this code into mySQL and create the table (the only addition we have to make manually is to enter what database we "USE" in mySQL)



