(function ($) {
    var ctbp = {
        cache: {},
        globals: {
            galleryimages: '',
            email: '',
            admincars: [],
            startDate: '',
            endDate: '',
        },
        init: function init() {
            this.cacheElements();
            this.bindEvents();
        },
        cacheElements: function cacheElements() {
            this.cache = {
                $window: $(window),
                $document: $(document)
            };
        },
        bindEvents: function () {
            var self = this;
           self.initAdmin();
           self.upload_images();
        },
          initAdmin: function(){
      var self = this;

         self.cache.$document.on('click', '.misha-upl', function(e){
          var button = $(this),
          custom_uploader = wp.media({
          title: 'Insert image',
          library : {
          // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
          type : 'image'
          },
          button: {
            text: 'Use this image' // button label text
          },
          multiple: 'add'
          }).on('select', function() { 
          // it also has "open" and "close" events
          var attachment = custom_uploader.state().get('selection').toJSON();
          self.globals.galleryimages = attachment;  
          //console.log(count(attachment));
          var count = Object.keys(attachment).length;
            console.log(count);
            $('.count').text('You have selected '+ count + ' images');
          jQuery('#misha-img').val()
          }).open();

          custom_uploader.on('open',function() {
            var selection = frame.state().get('selection');
            var ids_value = jQuery('#misha-img').val();
            if(ids_value.length > 0) {
              var ids = ids_value.split(',');
              ids.forEach(function(id) {
                attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
              });
            }
          });
             });

          },
        upload_images: function(){
          var self = this;
         var request;
           self.cache.$document.on('click', '.upload', function(e){
           //alert('testnutton');
           var title= $('input[name="gallery_title"]').val();
            //console.log(title);
            request = wp.ajax.post(_ctbp.action + '_create_gallery', {
                    nonce: _ctbp.nonce,
                    data: self.globals,
                    title: title,
                });
                request.done(function(response){
                  console.log(response.message);
                  if(response.message== true){
                    $('.count').text('Gallery Created Successfully');
                    location.reload();
                  }
                })
            });
        },
    };
    ctbp.init();
})(jQuery);