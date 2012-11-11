
tinyMCE.init({
    // General options
    mode : "textareas",
    theme : "advanced",
    plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

    // Theme options
    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,

    // Example content CSS (should be your site CSS)
    content_css : "css/content.css",

    save_onsavecallback : "save"

  });

/*
* Send ajax data and save the page content to the database
*/
function save()
{
  var id_page = $("select[name='page'] option:selected").val();
    var id_region = $("select[name='region'] option:selected").val();
    var ed = tinyMCE.get('data');
    ed.setProgressState(1);
    $.post(BASE_URL + 'content/save', { 'page': id_page, 'region': id_region, 'data': ed.getContent()}, function(data){
      ed.setProgressState(0);
    });  
}

/**
 * Call function to save data in database
 */
$(document).ready(function() 
{
  $("form").submit(function(e) {
    save();
    return false;
  });

  /*
  * Get the content of the selected page and region and present it in the textarea
  */
  $("select").change(function () {
    var id_page = $("select[name='page'] option:selected").val();
    var id_region = $("select[name='region'] option:selected").val();
    var ed = tinyMCE.get('data');

    ed.setProgressState(1);
    $.post(BASE_URL + 'content/get_content', { 'id_page': id_page, 'id_region':id_region } , function(data){
      ed.setProgressState(0);
      ed.setContent(data);
    });
  })
   .trigger('change');
});
