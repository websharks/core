(function () {

  /*
   * Mixins.
   */
  _.mixin({

    sha1Color: function (str, adjust) {
      var hex = '#' + _.sha1(str).substr(0, 6);
      return adjust ? _.adjustColorLuminosity(hex, adjust) : hex;
    },

    adjustColorLuminosity: function (hex, adjust) {
      hex = _.lTrim(hex, '#');
      if (hex.length !== 6) {
        hex = hex.replace(/(.)/g, '$1$1');
      }
      for (var adjustedHex = '', _dec, _hex, _i = 0; _i < 3; _i++) {
        _dec = parseInt(hex.substr(_i * 2, 2), 16);
        _hex = Math.round(Math.min(Math.max(0, _dec + (_dec * adjust)), 255)).toString(16);
        adjustedHex += ('00' + _hex).substr(_hex.length);
      }
      return '#' + adjustedHex;
    }

  });

})();
