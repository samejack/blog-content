"use strict";

var page = require('webpage').create();

page.open('https://blog.toright.com', function() {
    // show title
    var title = page.evaluate(function() {
        return document.title;
    });
    console.log(title);

    // screen capture
    page.render('screenshot.png');
    phantom.exit();
});
