(function ($) {
  
  var $galleries = $('.cvc-gallery');
  var isLoggedIn = $('body').hasClass('logged-in');

  function cvcGallery ($gallery) {

    var featuredHTML = '<article class="featured row">' +
                         '<figure></figure>' +
                         '<a href="javascript:void(0);" class="fa fa-angle-left"></a>' +
                         '<a href="javascript:void(0);" class="fa fa-angle-right"></a>' +
                         '<figcaption class="small-12 columns text-center"></figcaption>' +
                       '</article>';

    // Featured HTML
    $gallery.prepend(featuredHTML);

    // Make associations
    this.$featured     = $gallery.find('article');
    this.$figure       = this.$featured.find('figure');
    this.$descriptions = this.$featured.find('figcaption');

    this.$arrows       = this.$featured.find('.fa');
    this.$leftArrow    = this.$featured.find('.fa-angle-left');
    this.$rightArrow   = this.$featured.find('.fa-angle-right');
    
    this.$gallery      = $gallery;

    // Init with image index
    this.imgIndex = 0;


    return this;
  }

  cvcGallery.prototype.setImageClickHandlers = function () {

    var $parent       = this;
    var $gallery      = this.$gallery;
    var $figure       = this.$figure;
    var $descriptions = this.$descriptions;
    var $featuredSec  = $figure.add($descriptions);
    var imageIndex    = this.imgIndex;
    var $images       = $gallery.find('img');

    $featuredSec.addClass('animated');

    $images.each(function (imgIndex) {

      var $this  = $(this);
      var imgSrc = $this.attr('src');
      var data   = $this.data();
      var id     = data.attachmentId;

      $this.click(function (e) {

        $images.removeClass('active');
        $this.addClass('active');

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
          // http://localhost/cvc/wp-admin/pos
          descriptions += "<a class=\"secondary button\" href=\"http://cvc.pradadesigners.com/wp-admin/post.php?post="+id+"&action=edit\" target=\"blank\">Edit Metadata</a>";
        }

        $figure.html('<img src="'+imgSrc+'"/>');

        $descriptions.html(descriptions);

        var newImgHeight = $figure.find('img').height() + 'px';

        $parent.imgIndex = imgIndex;
        $parent.$arrows.css({'line-height':newImgHeight});

      });

      if (imgIndex === imageIndex) {
        $this.trigger('click');
      }
    });
  };


  cvcGallery.prototype.setArrowClickHandlers = function () {

    var $this = this;

    function returnArrowClickHandler (direction) {

      return function (e) {
        e.preventDefault();

        if (direction == 'right') {
          $this.imgIndex++;
        } else if (direction == 'left') {
          $this.imgIndex--;
        }

        var $images   = $this.$gallery.find('.images').find('img');
        var targetImg = $images.eq($this.imgIndex);

        $images.eq($this.imgIndex).trigger('click');

      };
    }

    this.$leftArrow.click(returnArrowClickHandler('left'));
    this.$rightArrow.click(returnArrowClickHandler('right'));
  };

  cvcGallery.prototype.createGallery = function () {
    this.setImageClickHandlers();
    this.setArrowClickHandlers();
  };


  $galleries.each(function (index) {

    var $gallery = $(this);
    var cvc = new cvcGallery($gallery);
    cvc.createGallery();

  });

})(jQuery);