(function ($) {
  
  var $galleries = $('.cvc-gallery');

  function cvcGallery ($gallery) {
    this.$gallery = $gallery;
    this.$span    = $gallery.find('span');
    return this;
  };

  cvcGallery.prototype.createImage = function (id, callback) {

    var $this = this;

    $.get("http://localhost/cvc/wp-content/themes/cvc/gallery.php?id="+id, function (data) {

      var img = document.createElement('img');
      img.src = data.src[0];

      var $img = $(img);
      $img.data('ID', id);

      // Append image data to src
      for (var p in data.custom) {
        if (p.indexOf('wpcf-') > -1) {
          $img.data(p.replace('wpcf',''), data.custom[p][0]);
        }
      }

      // Set image actions
      $this.setImageActions($img);

      // Return the new image
      return callback($img);
    });
  }


  cvcGallery.prototype.setImageActions = function ($img) {

    var $span = this.$span;
    var $newImg = $img.clone();

    $img.click(function (e) {
      $span.empty();

      $newImg.addClass('small-8 columns');

      $span.append($newImg);

      var $descriptions = $('<aside class="small-4 columns"></aside>');
      var d = $img.data();

      if (d.ArtistName) {
        $descriptions.append('<h2>'+d.ArtistName+'</h3>');
      }

      if (d.WorkTitle) {
        $descriptions.append('<h4>'+d.WorkTitle+'</h4>');
      }

      if (d.WorkYear) {
        $descriptions.append('<h6 class="subheader">'+d.WorkYear+' • ' + d.WorkMedium +'</p>');
      }

      $span.append($descriptions);
    });
  }


  cvcGallery.prototype.createGallery = function () {

    var $gallery = this.$gallery;
    var $span    = this.$span;
    var galleryText = this.$span.text();
    var ids = galleryText.split(',');

    // Hide the IDs that were unwanted
    // and make it visible
    this.$span.empty().removeClass('hidden').addClass('row');

    var isSet = false;

    function onCreateImage ($img) {

      if (!isSet) {
        var defaultImg = $img.clone();
        defaultImg.appendTo($span);
        isSet = true;
      }

      $img.appendTo($gallery);
    }

    for (var i = 0; i < ids.length; i++) {

      this.createImage(ids[i], onCreateImage);
    };
  }




  $galleries.each(function (index) {

    var $gallery = $(this);

    var cvc = new cvcGallery($gallery);
    cvc.createGallery();

  });

})(jQuery);