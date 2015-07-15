(function ($) {
  
  var $galleries = $('.cvc-gallery');
  var isLoggedIn = $('body').hasClass('logged-in');

  function cvcGallery ($gallery) {
    this.$gallery = $gallery;
    return this;
  }

  cvcGallery.prototype.createGallery = function () {
    var $gallery = this.$gallery;

    $gallery.find('img').each(function() {

      var $this = $(this);
      var data  = $this.data();
      var id    = data.attachmentId;

      $this.click(function (e) {
        if(isLoggedIn){
          // window.open('http://localhost/cvc/wp-admin/post.php?post='+id+'&action=edit', '_blank');
        }

        var $descriptions = $('<figcaption class="small-12 columns text-center"></figcaption>');

        var meta = {};

        function createNewKey (matched) {
          return matched[1].toUpperCase();
        }

        for (var k in data.meta) {

          if (k.indexOf('wpcf') > -1) {
            var newKey = k.replace('wpcf','').replace(/-[a-z]/g, createNewKey);
            meta[newKey] = data.meta[k];          
          }
        }

        if (meta.ArtistName) {
          $descriptions.append('<h2>'+meta.ArtistName+'</h3>');
        }

        if (meta.WorkTitle) {
          $descriptions.append('<h4>'+meta.WorkTitle+'</h4>');
        }

        if (meta.WorkYear) {
          $descriptions.append('<h6 class="subheader">'+meta.WorkYear+' • ' + meta.WorkMedium +'</p>');
        }

        $gallery.prepend($descriptions);


      });
    });
  };




  $galleries.each(function (index) {

    var $gallery = $(this);

    var cvc = new cvcGallery($gallery);
    cvc.createGallery();

  });

})(jQuery);