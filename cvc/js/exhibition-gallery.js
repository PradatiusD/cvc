(function ($) {
  
  var $galleries = $('.cvc-gallery');
  var isLoggedIn = $('body').hasClass('logged-in');

  function cvcGallery ($gallery) {

    $gallery.prepend('<article class="featured row"><figure></figure><figcaption class="small-12 columns text-center"></figcaption></article>');

    this.$figure       = $gallery.find('article').find('figure');
    this.$descriptions = $gallery.find('article').find('figcaption');
    this.$gallery      = $gallery;

    return this;
  }

  cvcGallery.prototype.createGallery = function () {

    var $gallery      = this.$gallery;

    var $figure       = this.$figure;
    var $descriptions = this.$descriptions;
    var $featuredSec  = $figure.add($descriptions);

    $featuredSec.addClass('animated');

    $gallery.find('img').each(function (imgIndex) {

      var $this  = $(this);
      var imgSrc = $this.attr('src');
      var data   = $this.data();
      var id     = data.attachmentId;

      $this.click(function (e) {

        $featuredSec.addClass('fade-in');
        setTimeout(function (){
          $featuredSec.removeClass('fade-in');
        }, 800);


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

        var descriptions = '';

        if (meta.ArtistName) {
          descriptions += '<h2>'+meta.ArtistName+'</h3>';
        }

        if (meta.WorkTitle) {
          descriptions += '<h4>'+meta.WorkTitle+'</h4>';
        }

        if (meta.WorkYear) {
          descriptions += '<h6 class="subheader">'+meta.WorkYear+' • ' + meta.WorkMedium +'</p>';
        }

        // If no data add button to edit it
        if (Object.keys(meta).length === 0 && isLoggedIn) {
          descriptions += '<h6 class="subheader">No Metadata found</p>';
          descriptions += "<a class=\"secondary button\" href=\"http://localhost/cvc/wp-admin/post.php?post="+id+"&action=edit\" target=\"blank\">Edit Metadata</a>";
        }

        $figure.html('<img src="'+imgSrc+'"/>');

        $descriptions.html(descriptions);


      });

      if (imgIndex === 0) {
        $this.trigger('click');
      }
    });
  };


  $galleries.each(function (index) {

    var $gallery = $(this);
    var cvc = new cvcGallery($gallery);
    cvc.createGallery();

  });

})(jQuery);