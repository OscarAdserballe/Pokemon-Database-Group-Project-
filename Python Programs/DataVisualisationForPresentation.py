# Visualisation of noise test using matplotlib for our ppt presentation.

from matplotlib import pyplot as plt
import pandas as pd

file_list = (__file__.split('/')[:-1])
path_string = ""
for i in file_list:
    path_string += i + "//"

df = pd.read_csv(path_string+ 'objectivity_test_with_prev_values.csv')

print(df.head())

length = 0
difference = []
points = []

for i in range(len(df['Cuteness Value'])):
    #print(df['Cuteness Value'][i], df['Prev. Cuteness Values'][i])
    difference.append(abs(df['Cuteness Value'][i] - df['Prev. Cuteness Values'][i]))
    points.append((df['Cuteness Value'][i], df['Prev. Cuteness Values'][i]))
    length += 1


distribution = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
for j in difference:
    distribution[j] += 1

values = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]

ax = plt.axes()

plt.xlim([0, 10])
plt.ylim([0, 10])


for point in points:
    plt.scatter(point[0], point[1], marker="o", color="red")

plt.plot(ax.get_xlim(), ax.get_ylim(), color="blue", linestyle="dashed")


plt.ylabel("Previously assigned Cuteness Value")
plt.xlabel("Newly Assigned Cuteness Value")

some_list = [x for x in range(11)]

plt.xticks(some_list)
plt.yticks(some_list)
plt.grid(True)

plt.show()
#bar graph

