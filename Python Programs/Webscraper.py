import csv
from urllib.request import urlopen, Request
from bs4 import BeautifulSoup


#Primary link to webscrape: https://pokemondb.net/pokedex/national

url = "https://pokemondb.net/pokedex/national/"

req = Request(url=url, headers={'user-agent': 'my-app/0.0.1'})

response = urlopen(req)

html = BeautifulSoup(response, "html.parser")
list_of_links = []
#gen_divs = html.findAll('div', class_="infocard-list infocard-list-pkmn-sm")
links = html.findAll('a')
for link in links:
    link_part = link['href']
    if 'pokedex' in link_part:
        new_link = "https://pokemondb.net" + link_part
        if new_link not in list_of_links:
            list_of_links.append(new_link)
            if 'calyrex' in new_link:
                break       #Calyrex is the last pokemon, so we stop the code there

#We still need to restrict the list of the links, as some <a>-tags also contain 'pokedex', but are not actually pokemon
#Luckily, this database is ordered (something which we also made us of when 'breaking' the web-grapper at mew), so we'll find
#the entry where bulbasaur is listed.
for i in range(len(list_of_links)):
    if 'bulbasaur' in list_of_links[i]:
        index_to_start = i
        break   #no need to continue
list_of_links = list_of_links[6:]

#Now we have all the links for the pokemons, which we'll then grab our data for the table from
#Trouble may still arise if the website were to update, say because of a new generation, but that should not change anything
#that would render our webgrapper unusable.

#Now we'll gather the names
image_links = []
tables = []
name_lists = [] 

for link in list_of_links:
    pokemon_name = link[30:]
    name_lists.append(pokemon_name)


##############Code by Teacher#########################


# TEST ONLY
#print(name_lists)

#Grabbing the wanted data
for link in list_of_links:
    req = Request(url=link, headers={'user-agent': 'my-app/0.0.1'})
    response = urlopen(req)
    html = BeautifulSoup(response, "html.parser")
    tables = html.findAll('table')
    # Only grab "Pokedex data"
    table = tables[0]
    table_string = str(table)
    # Name
    name = link[30:]
    print(name, end="")
    # Image
    print(", https://img.pokemondb.net/artwork/" + name + ".jpg", end="")
    # National number
    start_tag = "<td><strong>"
    start_index = table_string.find(start_tag) + len(start_tag)
    end_tag = "</strong></td>"
    end_index = table_string.find(end_tag)
    national_id = table_string[start_index:end_index]
    print(", " + national_id, end="")
    # Then type, species, height, weight, abilities
    for i in range(5):
        table_string = table_string[end_index + len(end_tag):] # Cuts the beginning of the string
        # for the type, there is an extra newline character
        if (i == 0):
            start_tag = "<td>\n"
        else:
            start_tag = "<td>"
        start_index = table_string.find(start_tag) + len(start_tag)
        end_tag = "</td>"
        end_index = table_string.find(end_tag)
        table_data = table_string[start_index:end_index]
        print(", " + table_data, end="")
    print("")

