/*!
 * Amanda Responsive Bootstrap 4 Admin Template
 * Version: 2.0.0
 * Author: ThemePixels (@themepixels)
 * URL: http://themepixels.me/amanda
 *
**/

'use strict';

$(document).ready(function(){

  jQuery('.numberOnly').keyup(function () { 
    this.value = this.value.replace(/[^0-9.]/g,'');
    // this.value = this.value.replace(/[^0-9\.]/g,'');
  });

  // validation for character only section starts
  jQuery('.noSpecialCharcter').keyup(function () {
    this.value = this.value.replace(/[^a-z0-9.-\s]/gi, '').replace(/[_\s]/g, ' ');
  });

  $(document).on('mouseenter','[data-toggle="tooltip"]', function(){
      $(this).tooltip('show');
  });

  $(document).on('mouseleave','[data-toggle="tooltip"]', function(){
      $(this).tooltip('hide');
  });

});
