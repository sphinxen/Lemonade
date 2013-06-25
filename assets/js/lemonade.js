// $(document).ready(function() 
// {
//   'use strict';
//   console.log("loaded lemonade.js");
//   var id_page, id_region, textarea;

//   /*
//    * Set id for Textarea
//    */
//   //$(".markItUp").markItUp(mySettings);
//     // $('.wymeditor').wymeditor({skin:'compact'});
//   //  $('.wymeditor').html("");
    
//   /*
//   * Send ajax data and save the page content to the database
//   */
//   $("form[id='content']").submit(function() {
//      id_page = $("select[name='page'] option:selected").val()
//     ,id_region = $("select[name='region'] option:selected").val()
//     ,textarea = $("textarea[name='data']");

//     $.post(BASE_URL + 'content/save', { 'page': id_page, 'region': id_region, 'data': textarea.val()}, function(data){
//     });  
//     return false;
//   });

  /*
  * Get the content of the selected page and region and present it in the textarea
  */
//   $("#content select").change(function () {
//      id_page = $("select[name='page'] option:selected").val()
//     ,id_region = $("select[name='region'] option:selected").val()
//     ,textarea = $("textarea[name='data']");

//     $.post(BASE_URL + 'content/get_content', { 'id_page': id_page, 'id_region':id_region } , function(data){
//         textarea.html(data)
// console.log(data);
//         // CKEDITOR.replace( 'data' );
//         CKEDITOR.html(data);
//     });
//   })
//    .trigger('change');
// });
// 


// CKEDITOR.ajax.post = function( url, args, callback, getResponseText ) {
//       var async = !!callback;

//       var xhr = createXMLHttpRequest();

//       var urlEncodedString = "";

//         if ( !xhr )
//           return null;

//       xhr.open( 'POST', url, async );
//       xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

//       if ( async ) {
//         // TODO: perform leak checks on this closure.
//         xhr.onreadystatechange = function() {
//           if ( xhr.readyState == 4 ) {
//             callback( getResponseFn( xhr ) );
//             xhr = null;
//           }
//         };
//       }

//       if(typeof args === "object") {
//         for( var i in args ) {
//           if( urlEncodedString !== "" ) {
//             urlEncodedString += "&";
//           }

//           urlEncodedString += i + "=" + args[i];
//         }
//       }

//       xhr.send( urlEncodedString );

//         return async ? '' : getResponseFn( xhr );
//       };





var page    = document.getElementsByName('page')[0],
    region  = document.getElementsByName('region')[0],
    form    = document.getElementById('content_form'),
// config.FormatOutput = false;

    getContent = function() {
      CKEDITOR.ajax.post(BASE_URL + "content/getContent", {'ajax':1, 'id_page': page.value, 'id_region':region.value}, function(data){
        CKEDITOR.instances.data.setData(data);
      })
    },

    saveContent = function() {
    var data = CKEDITOR.instances.data.getData();

    CKEDITOR.ajax.post(BASE_URL + "content/save", {'ajax':1, 'page': page.value, 'region':region.value, 'data': data}, function(data){
console.log(data);
      });

      return false;
    };

region.onchange   = getContent;
page.onchange     = getContent;
content.onsubmit  = saveContent;

console.log(content);

