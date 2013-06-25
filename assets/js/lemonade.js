var page    = document.getElementsByName('page')[0],
    region  = document.getElementsByName('region')[0],
    form    = document.getElementById('content_form'),

    getContent = function() {
      CKEDITOR.ajax.post(BASE_URL + "content/getContent", {'ajax':1, 'id_page': page.value, 'id_region':region.value}, function(data){
        CKEDITOR.instances.data.setData(data);
      })
    },

    saveContent = function() {
    var data = CKEDITOR.instances.data.getData();

    CKEDITOR.ajax.post(BASE_URL + "content/save", {'ajax':1, 'page': page.value, 'region':region.value, 'data': data}, function(data){
      });

      return false;
    };

region.onchange   = getContent;
page.onchange     = getContent;
form.onsubmit  = saveContent;
