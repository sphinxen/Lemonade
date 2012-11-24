$(document).ready(function() 
{
  'use strict';
  console.log("loaded lemonade.js");
  var id_page, id_region, textarea;

  /*
   * Set id for Textarea
   */
  //$(".markItUp").markItUp(mySettings);
    $('.wymeditor').wymeditor({skin:'compact'});
  //  $('.wymeditor').html("");
    
  /*
  * Send ajax data and save the page content to the database
  */
  $("form[id='content']").submit(function() {
     id_page = $("select[name='page'] option:selected").val()
    ,id_region = $("select[name='region'] option:selected").val()
    ,textarea = $("textarea[name='data']");

    $.post(BASE_URL + 'content/save', { 'page': id_page, 'region': id_region, 'data': textarea.val()}, function(data){
    });  
    return false;
  });

  /*
  * Get the content of the selected page and region and present it in the textarea
  */
  $("select").change(function () {
     id_page = $("select[name='page'] option:selected").val()
    ,id_region = $("select[name='region'] option:selected").val()
    ,textarea = $("textarea[name='data']");

    $.post(BASE_URL + 'content/get_content', { 'id_page': id_page, 'id_region':id_region } , function(data){
        textarea.val(data)
    });
  })
   .trigger('change');
});
