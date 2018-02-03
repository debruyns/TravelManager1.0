$(document).ready(function(){

  $(function () {
    $('[data-toggle="popover"]').popover({html:true, trigger:'focus'});
  })

  $(this).on('input', '.date-format', function() {

    var input = $(this).val();
    if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
    var values = input.split('/').map(function(v) {
      return v.replace(/\D/g, '')
    });

    if (values[0]) values[0] = checkValue(values[0], 12);
    if (values[1]) values[1] = checkValue(values[1], 31);
    var output = values.map(function(v, i) {
      return v.length == 2 && i < 2 ? v + ' / ' : v;
    });

    $(this).val(output.join('').substr(0, 14));

  });

  $(this).on('blur', '.date-format', function() {

    var input = $(this).val();
    var values = input.split('/').map(function(v, i) {
      return v.replace(/\D/g, '')
    });
    var output = '';

    if (values.length == 3) {
      var year = values[2].length !== 4 ? parseInt(values[2]) + 2000 : parseInt(values[2]);
      var month = parseInt(values[0]) - 1;
      var day = parseInt(values[1]);
      var d = new Date(year, month, day);
      if (!isNaN(d)) {
        document.getElementById('result').innerText = d.toString();
        var dates = [d.getMonth() + 1, d.getDate(), d.getFullYear()];
        output = dates.map(function(v) {
          v = v.toString();
          return v.length == 1 ? '0' + v : v;
        }).join(' / ');
      };
    };
    $(this).val(output);

  });

  function checkValue(str, max) {
    if (str.charAt(0) !== '0' || str == '00') {
      var num = parseInt(str);
      if (isNaN(num) || num <= 0 || num > max) num = 1;
      str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
    };
    return str;
  };

});
