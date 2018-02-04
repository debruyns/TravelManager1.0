$(document).ready(function(){

  $(function () {

    // Language Popover
    $('[data-toggle="popover"]').popover({html:true, trigger:'focus'});

  });

  // Date Selector
  $('.date-format').datepicker({
    dateFormat: 'dd/mm/yy',
    constrainInput: true
  });


});
