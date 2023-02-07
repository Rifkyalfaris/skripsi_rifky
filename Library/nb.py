import pymysql
pymysql.install_as_MySQLdb()
import MySQLdb
import pandas as pd

from sklearn import preprocessing
from sklearn.model_selection import train_test_split
from sklearn.naive_bayes import GaussianNB, MultinomialNB
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
    name.append(row[1].replace(' ','_').replace(',','').replace('-','').replace('+',''))
    label.append(row[4])
    mar.append(row[5])
    apr.append(row[6])
    mei.append(row[7])

# print (features)

df = {
    'name': name,
    'label': label,
    'mar': mar,
    'apr': apr,
    'mei': mei
}
dataset = pd.DataFrame(df, columns=['name', 'label', 'mar', 'apr', 'mei'])

labelEncoder = preprocessing.LabelEncoder()
encname = labelEncoder.fit_transform(name)
encodedLbl = labelEncoder.fit_transform(label)

for i in range(len(name)):
    features.append([
        encname[i], 
        mar[i],
        apr[i], 
        mei[i],
    ])

x_train, x_test, y_train, y_test = train_test_split(features, encodedLbl, test_size=0.2)
# print (x_train)

model = GaussianNB()
# model = MultinomialNB()

# Train the model 
model.fit(x_train, y_train)

# Predict Output 
pred = model.predict(x_test)

# Plot Confusion Matrix
conf = confusion_matrix(y_test, pred)
# print(conf)

accuracy = accuracy_score(y_test, pred)*100
# accuracy = metrics.accuracy_score(pred , y_test)
# print(accuracy)

res = {
    'Confusion': conf, 
    'Accuracy': accuracy,
    'Predict': pred,
    'Dataset': dataset.to_json(orient ='records'),
}
print(pd.Series(res).to_json())