import pymysql
pymysql.install_as_MySQLdb()
import MySQLdb
import pandas as pd, numpy as np, matplotlib.pyplot as plt

from sklearn import preprocessing
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, confusion_matrix
from sklearn.feature_extraction.text import CountVectorizer

database = MySQLdb.connect (host="localhost" , user="root" , passwd="" ,db="s_kmeans_nb")
cursor = database.cursor()

query = 'select * from tbl_dataset where mar is not NULL and apr is not NULL and mei is not NULL'
cursor.execute (query)

results = cursor.fetchall()
features, label, name, mar, apr, mei = [],[],[],[],[],[]

for row in results:
    # id = row[0]
    # row[1]
    # row[2]
    # row[3]
    # row[4]
    # row[5]
    # row[6]
    # row[7]
    # features.append([
    #     row[1],
    #     row[4],
    #     # row[5], 
    #     # row[6], 
    #     # row[7], 
    # ])
    name.append(row[1])
    label.append(row[4])
    mar.append(row[5])
    apr.append(row[6])
    mei.append(row[7])

# print (features)

labelEncoder = preprocessing.LabelEncoder()
encname = labelEncoder.fit_transform(name)
encodedLbl = labelEncoder.fit_transform(label)
df = {
    'name': name,
    'nameenc': encname,
    'label': label,
    'labelenc': encodedLbl,
    'mar': mar,
    'apr': apr,
    'mei': mei
}
dataset = pd.DataFrame(df, columns=['name', 'nameenc', 'label', 'labelenc', 'mar', 'apr', 'mei'])
newDataset = dataset.drop(['name','label'], axis='columns')
newDataset = newDataset.dropna()
# print(dataset)

for i in range(len(name)):
    features.append([
        encname[i], 
        mar[i],
        apr[i], 
        mei[i],
    ])

x_train, x_test, y_train, y_test = train_test_split(newDataset, encodedLbl, test_size=0.2)

scaled_dataset = StandardScaler().fit_transform(newDataset)

#instantiate the k-means class, using optimal number of clusters
kmeans = KMeans(init="random", n_clusters=3, n_init=10, random_state=1)

#fit k-means algorithm to data
kmeans.fit(scaled_dataset)

predict = kmeans.predict(x_test)
conf = confusion_matrix(y_test, predict)
accuracy = accuracy_score(y_test, predict)*100

#view cluster assignments for each observation
# kmeans.labels_
newDataset['cluster'] = kmeans.labels_

merged = pd.merge(dataset, newDataset, how='inner', left_index=True, right_index=True)
print(merged)
# result = pd.concat([dataset, newDataset], axis=1, join='inner')
# display(result)
# print(result)

# print(dataset)
# print(pd.Series(dataset).to_json())
# print(dataset.to_json(orient ='records'))

res = {
    'Confusion': conf, 
    'Accuracy': accuracy,
    'Predict': predict,
    'Dataframe': merged.to_json(orient ='records'),
}
print(pd.Series(res).to_json())
