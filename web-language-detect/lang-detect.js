try {
  var switchPage = function (language) {
    switch (language) {
      case 'zh-cn':
        console.log('/zh-CN' + window.location.pathname);
        window.location.href = '/zh-CN' + window.location.pathname;
        return true;
        break;

      case 'zh':
      case 'zh-tw':
        console.log('/zh-TW' + window.location.pathname);
        window.location.href = '/zh-TW' + window.location.pathname;
        return true;
        break;

      case 'en':
      case 'en-us':
        console.log('/en-US' + window.location.pathname);
        window.location.href = '/en-US' + window.location.pathname;
        return true;
        break;

      default:
    }
    return false;
  };

  // detect window.navigator.languages
  var found = false;
  if (typeof(window.navigator.languages) === 'object') {
    for (var index in window.navigator.languages) {
      console.log(window.navigator.languages[index].toLowerCase());
      found = switchPage(window.navigator.languages[index].toLowerCase());
      if (found) break;
    }
  }

  if (!found) {
    var lang = window.navigator.userLanguage || window.navigator.language;
    var relang = lang.toLowerCase();
    found = switchPage(relang);
  }

  if (!found) {
    window.location.href = '/en-US' + window.location.pathname;
  }
} catch (e) {
  window.location.href = '/en-US' + window.location.pathname;
}