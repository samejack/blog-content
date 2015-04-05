'use strict';

/**
 * Universal JavaScript package PLUS!
 * JavaScript, NodeJS Module and RequireJS Module Supported
 */
(function (name, definition) {
  if (typeof(module) !== 'undefined' && module.exports) {
    module.exports = definition;
  } else if (typeof(define) === 'function') {
    define(definition);
  } else {
    this[name] = definition();
  }
}('FoxDriveAPI', function (initServerHostname, initLogger, request) {
  var privateVar = 'I_AM_PRIVATE_VAR';

  var privateFn = function () {
    console.log('I am private function.');
  };

  // constructor
  var constructor = function () {
    console.log(privateVar);
    privateFn();
  }();

  var publicFn = function () {
    console.log('I am public function.');
  };

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
}));
