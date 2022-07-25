#Overall, this file is very similar to the "Randomiser.py" file, and even uses a lot of the same code.
# After Samo completed the re-measurement of the 50 randomised, this was quickly programmed to collate the two files: the one with the previous cuteness values and the one with the new cuteness values
# However, the from the file containing the previous cuteness values we only need to select the 50 that were re-measured.


# Same code as in Randomiser.py (see comments there)
import random   
import pandas as pd

file_list = (__file__.split('/')[:-1])
path_string = ""
for i in file_list:
    path_string += i + "//"
csv_file_string = path_string + 'cuteness_objectiveness_comparison.csv'

df_prev = pd.read_csv(csv_file_string, sep=",", encoding="ANSI")
df_new = pd.read_csv(path_string + 'objectivity_test.csv', sep=",", encoding="ANSI")
prev_cute_values=[]

#New code starts here

for id in df_new['ID']: #Looks at the ids of the 50 pokemon in the new file with cuteness socres
    print(df_prev['Cuteness Values'][id-1], df_prev['Pokemon Names'][id-1]) #A brief test
    prev_cute_values.append(df_prev['Cuteness Values'][id-1])   #Stores the previous cuteness value that was assigned by Samo to a list

df_new['Prev. Cuteness Values'] = prev_cute_values  #List containing all previous cuteness values of the 50 pokemon (from for loop)
print(df_new.head())    #Printing out part of dataframe as a brief test

df_new.to_csv(path_string + 'objectivity_test_with_prev_values.csv')    #Finally, it converts the dataframe to a new csv file containing both the new and previous cuteness values, so we can see how consistent they were using Excel.