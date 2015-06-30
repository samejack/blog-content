import csv
import random

# open csv file
with open('iris.csv', mode='r') as infile:
  reader = csv.reader(infile)
  csvlist = list(reader)

# create sample: [[answer, [feature 1, ...]]]
samples = list()
maxFeature = [0, 0, 0, 0];
for rows in csvlist:
  features = [rows[0], rows[1] , rows[2], rows[3]]
  maxFeature[0] = max([maxFeature[0], rows[0]]);
  maxFeature[1] = max([maxFeature[1], rows[1]]);
  maxFeature[2] = max([maxFeature[2], rows[2]]);
  maxFeature[3] = max([maxFeature[3], rows[3]]);
  if rows[4] == "setosa":
    samples.append([0.333333, features])
  elif rows[4] == "versicolor":
    samples.append([0.666666, features])
  else:
    samples.append([1.000000, features])

# normalization
i = 0
for rows in samples:
  j = 0
  for feature in samples[i][1]:
    samples[i][1][j] = float(feature) / float(maxFeature[j])
    j += 1
  i += 1

# random candidate
predictList = list()
i = 0
while i < 10:
  r = random.randint(0, len(samples))
  predictList.append((samples[r][0], samples[r][1]))
  del samples[r]
  i += 1

print 'Sample Length = ' + str(len(samples))

print 'Predict Length = ' + str(len(predictList))


from pyspark.mllib.regression import LabeledPoint
from pyspark.mllib.classification import NaiveBayes
from pyspark import SparkContext

sc = SparkContext("local", "Simple App")

# create labels
labels = list()
i = 0
for sample in samples:
  labels.append(LabeledPoint(sample[0], sample[1]))
  i += 1

# training model
data = sc.parallelize(labels)
model = NaiveBayes.train(data, 1.0)

# go go go
correct=0
i = 1
for predict in predictList:
  answer = model.predict(predict[1])
  print str(i) + ' -> ' + str(predict[0]) + ' = ' + str(answer)
  if answer == predict[0]:
    correct += 1
  i += 1
print 'Accuracy = ' + str(float(correct) / float(len(predictList)) * 100) + '%'

