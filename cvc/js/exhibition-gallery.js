(function ($) {
  function CVCGallery ($gallery) {
    // Make associations
    this.$featured = $gallery.find('article')
    this.$figure = this.$featured.find('figure')
    this.$descriptions = this.$featured.find('figcaption')

    this.$arrows = this.$featured.find('.fa')
    this.$leftArrow = this.$featured.find('.fa-angle-left')
    this.$rightArrow = this.$featured.find('.fa-angle-right')

    this.$gallery = $gallery

    this.$scrollLeft = this.$gallery.find('.gallery-wrap').find('.fa-angle-left')
    this.$scrollRight = this.$gallery.find('.gallery-wrap').find('.fa-angle-right')

    // Init with image index
    this.imgIndex = 0

    return this
  }

  CVCGallery.prototype.setImageClickHandlers = function () {
    const $parent = this
    const $gallery = this.$gallery
    const $figure = this.$figure
    const $descriptions = this.$descriptions
    const $featuredSec = $figure.add($descriptions)
    const imageIndex = this.imgIndex
    const $images = $gallery.find('img')

    $featuredSec.addClass('animated')

    $images.each(function (imgIndex) {
      const $this = $(this)
      const imgSrc = $this.attr('src')
      const data = $this.data()
      const id = data.attachmentId

      $this.click(function (e) {
        $images.removeClass('active')
        $this.addClass('active')

        $featuredSec.addClass('fade-in')
        setTimeout(function () {
          $featuredSec.removeClass('fade-in')
        }, 800)

        const meta = {}

        function createNewKey (matched) {
          return matched[1].toUpperCase()
        }

        for (const k in data.meta) {
          if (k.indexOf('wpcf') > -1) {
            const newKey = k.replace('wpcf', '').replace(/-[a-z]/g, createNewKey)
            meta[newKey] = data.meta[k]
          }
        }

        let descriptions = ''

        if (meta.ArtistName) {
          descriptions += '<h2>' + meta.ArtistName + '</h3>'
        }

        if (meta.WorkTitle) {
          descriptions += '<h4>' + meta.WorkTitle + '</h4>'
        }

        if (meta.WorkYear) {
          descriptions += '<h6 class="subheader">' + meta.WorkYear + ' • ' + meta.WorkMedium + '</p>'
        }

        // If no data add button to edit it
        if (Object.keys(meta).length === 0 && isLoggedIn) {
          descriptions += '<h6 class="subheader">No Metadata found</p>'
          // http://localhost/cvc/wp-admin/pos
          descriptions += '<a class="secondary button" href="http://cvc.pradadesigners.com/wp-admin/post.php?post=' + id + '&action=edit" target="blank">Edit Metadata</a>'
        }

        $figure.html('<img src="' + imgSrc + '"/>')

        $descriptions.html(descriptions)

        $parent.imgIndex = imgIndex

        $figure.find('img').imagesLoaded(function () {
          const $img = $figure.find('img')

          const newImgHeight = $img.height()
          const newImgWidth = $img.width()

          $parent.$arrows.css('line-height', newImgHeight + 'px')

          $descriptions.css({
            width: newImgWidth + 'px'
          })

          // Open full page image
          $img.click(function () {
            window.open($figure.find('img').attr('src'), '_blank')
          })
        })
      })

      if (imgIndex === imageIndex) {
        $this.trigger('click')
      }
    })
  }

  CVCGallery.prototype.setMainImageNavigationHandlers = function () {
    const $this = this

    function returnArrowClickHandler (direction) {
      return function (e) {
        e.preventDefault()

        if (direction == 'right') {
          $this.imgIndex++
        } else if (direction == 'left') {
          $this.imgIndex--
        }

        const $images = $this.$gallery.find('.images').find('img')
        const targetImg = $images.eq($this.imgIndex)

        $images.eq($this.imgIndex).trigger('click')
      }
    }

    this.$leftArrow.click(returnArrowClickHandler('left'))
    this.$rightArrow.click(returnArrowClickHandler('right'))
  }

  CVCGallery.prototype.setSecondaryImageNavigationHandlers = function () {
    const $images = this.$gallery.find('.images')

    $images.setXTransform = function (amount) {
      const transform = 'translateX(' + amount + 'px)'

      this.css('-webkit-transform', transform) // Chrome, Opera 15+, Safari 3.1+
      this.css('-ms-transform', transform) // Internet Exploer 9
      this.css('transform', transform) // Firefox 16+, IE 10+, Opera
    }

    let galleryWidth

    function moveSecondaryImagesOnClick (amount) {
      const images = []

      return function () {
        const transformMatrix = $images.css('transform') // such as "matrix(1, 0, 0, 1, -500, 0)"
        let newAmount

        if (!galleryWidth) {
          galleryWidth = 0

          $images.find('img').each(function () {
            galleryWidth += jQuery(this).width()
          })
        }

        if (transformMatrix === 'none') {
          newAmount = amount
        } else {
          const getXTransformRegex = /([-]?\d{1,})/g

          const currentXTransform = transformMatrix.match(getXTransformRegex)[4]
          const newXTransform = parseInt(currentXTransform) + amount

          newAmount = newXTransform
        }

        const $imageList = $images.find('img')

        // if ( -galleryWidth < newAmount && newAmount < 0) {
        // $images.setXTransform(newAmount);
        // }

        if (amount > 0) {
          // Left
          var $detached = $imageList.eq($imageList.length - 1).detach()
          $images.prepend($detached)
        } else {
          // Right
          var $detached = $imageList.eq(0).detach()
          $images.append($detached)
        }
      }
    }

    this.$scrollRight.click(moveSecondaryImagesOnClick(-100))
    this.$scrollLeft.click(moveSecondaryImagesOnClick(100))
  }

  CVCGallery.prototype.createGallery = function () {
    this.setImageClickHandlers()
    this.setMainImageNavigationHandlers()
    this.setSecondaryImageNavigationHandlers()
  }

  // Main

  const $galleries = $('.cvc-gallery')
  var isLoggedIn = $('body').hasClass('logged-in')

  $galleries.each(function (index) {
    const $gallery = $(this)
    const cvc = new CVCGallery($gallery)
    cvc.createGallery()
  })
})(jQuery)
