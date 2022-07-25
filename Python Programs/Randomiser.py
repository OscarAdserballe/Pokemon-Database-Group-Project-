#The purpose of this program is simply to randomise 50 different pokemon entries and insert them into a csv file to test for occasion noise in the cuteness judgements
#There is not a one "cuteness value" we can assign, but such a test of occasion noise is just to verify how consistent and systematic Samo's rankings were.
#This file is merely to create the csv file, the analysis of the difference in the values Samo assigned the pokemons is done in another excel file. 
#Because redoing all 898 pokemon is perhaps... a bit excessive, this program only tests 50 

import random
import pandas as pd #perhaps not necessary, but this module makes handling data very easy

file_list = (__file__.split('/')[:-1])      #These 5 lines gets the directory of the csv files containing the cuteness values
path_string = ""
for i in file_list:
    path_string += i + "//"
csv_file_string = path_string + 'cuteness_objectiveness_comparison.csv'

df = pd.read_csv(csv_file_string, sep=",", encoding="ANSI") #Transforms csv files into Pandas DataFrame
"""print(df.head())     #A brief check of the data, to make sure it seems correct and is of the correct shape
print(df.tail())
print(df.shape)
print(df.describe())"""

pokemon_names = []
pokemon_ids = []
pokemon_links = []
some_dict = {'ID' : pokemon_ids, 'Pokemon Names':pokemon_names, 'Link': pokemon_links, 'Cuteness Value': ''}    #Dictionaries are easily transformable into csv files using Pandas, so we store values in one
print(some_dict)
for i in range(50):     #Generates 50 different entries to test, containing the name, id and link to an image of said pokemon
    random_num = random.randint(1, 898)
    pokemon_names.append(df['Pokemon Names'][random_num])
    pokemon_ids.append(random_num+1)
    pokemon_links.append('https://img.pokemondb.net/artwork/large/'+df['Pokemon Names'][random_num].lower()+'.jpg') 


new_file = path_string + "objectivity_test1.csv"    #the file location of the new file
pd.DataFrame(some_dict).to_csv(new_file)    #Converts the dictionary into a csv file
