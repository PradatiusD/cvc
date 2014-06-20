				<?php wp_footer();?>
			</div>
		</div>
		<footer>
			<div class="row">
				<?php dynamic_sidebar('footer1'); ?>
				<?php dynamic_sidebar('footer2'); ?> 
				<?php dynamic_sidebar('footer3'); ?> 
				<?php dynamic_sidebar('footer4'); ?> 
			</div>
			<div class="row">
				<p class="text-center">
					<a href="<?php echo home_url();?>" class="footer-details">
						<img src="<?php echo get_stylesheet_directory_uri() ?>/img/cvc-logo.png"/>
						<span><?php bloginfo('name');?></span>
					</a><br>
					
					541 NW 27th St Miami, FL 33127 · 305-571-1415<br>
					<small>Copyright © 2014 Center for Visual Communication, Inc.</small>
				</p>
			</div>
		</footer>
	</body>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/global.min.js"></script>
	<script type="text/javascript">
		(function($){
			
			$(document).foundation();

			(function imageLightBox(){

				var activityIndicatorOn = function(){
						$( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo('body');
					}
				var activityIndicatorOff = function() {
						$( '#imagelightbox-loading' ).remove();
					}
				var overlayOn = function() {
						$( '<div id="imagelightbox-overlay"></div>' ).appendTo('body');
					}
				var overlayOff = function() {
						$( '#imagelightbox-overlay' ).remove();
					}
				var captionOn = function(){
					var description = $( 'a[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"] img' ).attr( 'alt' );
					if( description.length > 0 )
						$( '<div id="imagelightbox-caption">' + description + '</div>' ).appendTo('body');
				}
				var captionOff = function(){
					$( '#imagelightbox-caption' ).remove();
				}

				$('.lightbox').imagelightbox({
					onLoadStart: function() { captionOff(); activityIndicatorOn(); },
					onLoadEnd:	 function() { captionOn(); activityIndicatorOff(); },
					onEnd:		 function() { captionOff(); activityIndicatorOff(); }				
				});

			})();

		})(jQuery);
	</script>
</html>
