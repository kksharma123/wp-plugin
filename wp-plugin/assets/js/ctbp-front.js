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
           //self.upload_images();
        },
          initAdmin: function(){
			     var self = this;

			   self.cache.$document.on('click', '.submit_form', function(e){
              var formdata= $('#form_data').serialize();
               var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
               var email = $("input[name='email']").val();
               var zip = $("input[name='zip']").val();
               var phone = $("input[name='phone']").val();
               var dob = $("input[name='date_of_birth']").val();
               var street = $("input[name='street']").val();
               var isvalidphone = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
             //alert('fine');
                var isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/;
                if(isvalidphone.test(phone) && isValidZip.test(zip) && regex.test(email) && dob !=='' && street !==''){
              $('#form_data').submit();
            }
              // console.log(formdata);

              // var request = wp.ajax.post(_ctbp.action + '_submit_form', {
              //       nonce: _ctbp.nonce,
              //       data: formdata,
              //   });
              //   request.done(function(response){
                  
              //   })


             });

          },

    };
    ctbp.init();
})(jQuery);