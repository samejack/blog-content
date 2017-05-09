var step = require('step');
var csv = require('csvtojson');
var fs = require('fs');
var NodeNeuralNetwork = require('node-neural-network');
var Neuron = NodeNeuralNetwork.Neuron,
    Layer = NodeNeuralNetwork.Layer,
    Network = NodeNeuralNetwork.Network,
    Trainer = NodeNeuralNetwork.Trainer,
    Architect = NodeNeuralNetwork.Architect;

// start
step(
//  function loadNetwork() {
//    fs.readFile('network.json', {encoding: 'utf-8'}, function(err, data){
//      if (!err) {
//        var perceptron = Network.fromJSON(JSON.parse(data));
//        var trainer = new Trainer(perceptron);
//        this(trainer, perceptron);
//      } else {
//        console.log(err);
//      }
//    }.bind(this));
//  },
  function initNN () {

    function Perceptron(input, hidden, output)
    {
      // create the layers
      var inputLayer = new Layer(input);
      var hiddenLayer = new Layer(hidden);
      var outputLayer = new Layer(output);

      // connect the layers
      inputLayer.project(hiddenLayer);
      hiddenLayer.project(outputLayer);

      // set the layers
      this.set({
        input: inputLayer,
        hidden: [hiddenLayer],
        output: outputLayer
      });
    }

    // extend the prototype chain
    Perceptron.prototype = new Network();
    Perceptron.prototype.constructor = Perceptron;

    var perceptron = new Perceptron(4,6,3);
    var trainer = new Trainer(perceptron);
    this(trainer, perceptron);

  },
  function loadCsv(trainer, perceptron) {
    var rows = [];
    csv({noheader:true})
      .fromFile('iris.csv')
      .on('json', function (row) {
        rows.push(row);
      }).on('done',function () {
        this(trainer, perceptron, rows);
      }.bind(this));
  },
  function normalization(trainer, perceptron, rows) {
    // compute max feature
    var maxFeatureMap = [0, 0, 0, 0];
    rows.map(function (row) {
      for (var i = 0; i < 4; i++) {
        maxFeatureMap[i] = (maxFeatureMap[i] < row.field1) ? row.field1 : maxFeatureMap[i];
      }
    });

    // normalization feature (0~1)
    var fixData = {
      0: [],
      1: [],
      2: []
    };
    rows.map(function (row) {
      var type = row.field5 === 'setosa' ? 0 : row.field5 === 'versicolor' ? 1 : 2;
      fixData[type].push({
        input: [
          row.field1 / maxFeatureMap[0],
          row.field2 / maxFeatureMap[1],
          row.field3 / maxFeatureMap[2],
          row.field4 / maxFeatureMap[3]
        ],
        output: [
          row.field5 === 'setosa' ? 1 : 0,
          row.field5 === 'versicolor' ? 1 : 0,
          row.field5 === 'virginica' ? 1 : 0
        ]
      });
    });

    // make train data and test data
    var reserved = 3;  // reserved for test
    var trainData = [];
    var testData = [];
    for (var type in fixData) {
      for (var i = 0; i < fixData[type].length; i++) {
        if (i < fixData[type].length - reserved) {
          trainData.push(fixData[type][i]);
        } else {
          testData.push(fixData[type][i]);
        }

      }
    }

    this(trainer, perceptron, trainData, testData);
  },
  function train(trainer, perceptron, trainData, testData) {
    var defaults = {
      iterations: 10000,
      log: false,
      shuffle: true,
      cost: Trainer.cost.MSE
    };
    trainer.train(trainData, defaults);
    this(trainer, perceptron, trainData, testData);
  },
  function test(trainer, perceptron, trainData, testData) {

    // test
    var hit = 0, miss = 0;

    for (var i = 0; i < testData.length; i++) {
      var result = perceptron.activate(testData[i].input);
      var prediction = 0;
      result.reduce(function(a, b, index) {
        prediction = a < b ? index : prediction;
        return Math.max(a, b);
      });

      console.log(testData[i].output, result);

      if (prediction === testData[i].output.indexOf(1)) {
        hit++;
      } else {
        miss++;
      }
    }

    // report
    console.log('Accuracy: ' + (hit / (hit + miss) * 100));
    this(perceptron);
  },
  function saveNetwork(perceptron) {
    fs.writeFile('network.json', JSON.stringify(perceptron.toJSON()), function(err) {
      if (err) {
        return console.log(err);
      }
      console.log('The network was saved!');
    });
  }
);

