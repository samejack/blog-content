'use strict';

/**
 * Universal JavaScript package
 * JavaScript, NodeJS Module and RequireJS Module Supported
 */
var Universal = function () {

  var privateVar = 'I_AM_PRIVATE_VAR';

  var privateFn = function () {
    console.log('I am private function.');
  };

  // constructor
  var constructor = function () {
    console.log(privateVar);
    privateFn();
  };

  var publicFn = function () {
    console.log('I am public function.');
  };

  constructor();

  return {
    /**
     * Public defined
     */
    publicVar: 'I_AM_PUBLIC_VAR',

    /**
     * Public function
     */
    publicFn: publicFn
  };
};