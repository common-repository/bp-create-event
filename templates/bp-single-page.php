<?php

 /** 
 * Template for displaying a single Event
 * You can copy this file to your-theme
 * and then edit the layout.
 */

wp_register_script( 'google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false' );

get_header();?>
<style type="text/css">
.banner_thumb{
height:300px !important;	
width:600px !important;
	}
#thumbnail-slider ul li.active {
    border-color: #000;
}
#thumbnail-slider1 {
    
    margin: 0 auto;
    max-width: 600px;
    padding: 20px 0;
    }
#thumbnail-slider1 div.inner {
    margin: 0;
    overflow: hidden;
    padding: 2px 0;
    position: relative;
}
#thumbnail-slider1 div.inner ul {
    float: left !important;
    font-size: 0;
    height: auto !important;
    left: 0;
    list-style: none outside none;
    margin: 0;
    padding: 0;
    position: relative;
    top: 0;
    white-space: nowrap;
    width: auto !important;
}
#thumbnail-slider1 ul li {
    backface-visibility: hidden;
    border: 3px solid #000000;
    display: inline-block;
    list-style: none outside none;
    margin: 0 10px 0 0;
    padding: 0;
    position: relative;
    text-align: center;
    transition: border-color 0.3s ease 0s;
    vertical-align: middle;
}
</style>
<?php 

function pp_single_map_css() {
	echo '<style type="text/css"> .single_map_canvas img { max-width: none; } </style>';
}
add_action( 'wp_head', 'pp_single_map_css' );

wp_print_scripts( 'google-maps-api' );

?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); 
$meta = get_post_meta($post->ID ); ?>
			<div class="entry-content">
<?php 
				
				if( ! empty( $meta['event-myfile'][0] ) )
				{?>
					

				<img  class="banner_thumb" src="<?php echo $meta['event-myfile'][0];?>">
			<?php }
			
			else{
				?>
				<img src="<?php  echo plugin_dir_url(__FILE__); ?>/banner.png">
				
		<?php		}	?>
				<br/>
				<h2 class="entry-title">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
					<?php the_title(); ?></a>
				</h2>

				<?php
				$author_id = get_the_author_meta('ID');
				$author_name = get_the_author_meta('display_name');
				$user_link = bp_core_get_user_domain( $author_id );
				if( get_current_user_id() == $author_id )
					$is_author = true;
				else
					$is_author = false;
				?>

				<?php
				if( $is_author || is_super_admin() ) :

					$edit_link = wp_nonce_url( $user_link . 'events/create?eid=' . $post->ID, 'editing', 'edn');

					$delLink = get_delete_post_link( $post->ID );

				?>

								

				<?php endif; ?>

				<br/>

				<a href="<?php echo bp_core_get_user_domain( $author_id ); ?>">
				<?php echo bp_core_fetch_avatar( array( 'item_id' => $author_id, 'type' => 'thumb' ) ); ?>
				&nbsp;<?php echo $author_name; ?></a>
<br/>

				
				<?php the_content(); ?>

				<?php
				

				if( ! empty( $meta['event-date'][0] ) )
					echo __( 'Start Date', 'bp-simple-events' ) . ':&nbsp;' . $meta['event-date'][0];

if( ! empty( $meta['end-date'][0] ) )
					echo '<br/>' . __( 'End Date', 'bp-simple-events' ) . ':&nbsp;' . $meta['end-date'][0];
				if( ! empty( $meta['event-time'][0] ) )
					echo '<br/>' . __( 'Time', 'bp-simple-events' ) . ':&nbsp;' . $meta['event-time'][0];

				if( ! empty( $meta['event-address'][0] ) )
					echo '<br/>' . __( 'Location', 'bp-simple-events' ) . ':&nbsp;' . $meta['event-address'][0];

				if( ! empty( $meta['event-url'][0] ) )
					echo '<br/>' . __( 'Url', 'bp-simple-events' ) . ':&nbsp;' . bp_event_convert_url( $meta['event-url'][0] );

				?>

				<br/>
				Category: <?php the_category(', ');?>
					<br/><br/>
			<?php	if( ! empty( $meta['event-facebook'][0] ) )
				{
					?>
				<a href="<?php echo $meta['event-facebook'][0];?>"	><img class="attachment-100x100 size-100x100 wp-post-image" alt="Pierce the Veil" src="<?php  echo plugin_dir_url(__FILE__); ?>/icon-talk.jpg"></a>
			<?php 		}
	if( ! empty( $meta['event-twitter'][0] ) )
				{
					?>
				<a href="<?php echo $meta['event-twitter'][0];?>"	>	<img class="attachment-100x100 size-100x100 wp-post-image" alt="Pierce the Veil" src="<?php  echo plugin_dir_url(__FILE__); ?>/twitter_icon_over.png"></a>
			<?php 		}
			
			if( ! empty( $meta['event-google'][0] ) )
				{
					?>
				<a href="<?php echo $meta['event-google'][0];?>"	>	<img class="attachment-100x100 size-100x100 wp-post-image" alt="Pierce the Veil" src="<?php  echo plugin_dir_url(__FILE__); ?>/index.png"></a>
			<?php 		}			
				
				
				?>
				<br/>
				

				<?php if( ! empty( $meta['event-latlng'][0] ) ) : ?>

					<br/>
					<div class="single_map_canvas" id="single_event_map" style="height: 225px; width: 450px;"></div>

					<script type="text/javascript">
					function initialize() {
					  var singleLatlng = new google.maps.LatLng(<?php echo $meta['event-latlng'][0]; ?>);
					  var mapOptions = {
					    zoom: 12,
					    center: singleLatlng
					  }
					  var map = new google.maps.Map(document.getElementById('single_event_map'), mapOptions);

					  var marker = new google.maps.Marker({
					      position: singleLatlng,
					      map: map
					  });
					}

					google.maps.event.addDomListener(window, 'load', initialize);
					</script>
<br/>
				<?php endif; 
				
//echo $post->ID;	
 $myrows = $wpdb->get_results( "SELECT id,post_title FROM wp_posts where post_parent=$post->ID" );
 
 
 
 foreach ( $myrows as $count )   {
 $id=$count->id;
$attachment_thumb = get_children(array('post_parent'=>$id));
$nbImg = count($attachment_thumb);


}


 if (count($myrows)> 0){ 
 
 
 if($nbImg<=5){?>
	<div id="thumbnail-slider1">
                <div class="inner">
                    <ul>
                   <?php 
foreach ( $myrows as $print )   {
	//echo $print;
	 	$id=$print->id;
//$title=$print->post_title;
	 	//echo $id;
	 	//echo $title;
                  $images = get_children( array( 'post_parent' => $id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) ); 





        if ( $images ) { 

                //looping through the images
                foreach ( $images as $attachment_id => $attachment ) {
                ?>
 <li>
          <a  href="<?php echo wp_get_attachment_url($attachment_id,'full'); ?>"><?php echo wp_get_attachment_image( $attachment_id, 'homepage-thumb' ); ?></a>               
                        </li>
                           
                           

                           
                           

                <?php
                }
        }
                    
         }           
                    
                    ?>
                        
                    </ul>
                </div>
            </div>
	
	<?php }
else{?>
	<div id="thumbnail-slider">
                <div class="inner">
                    <ul>
                   <?php 
foreach ( $myrows as $print )   {
	//echo $print;
	 	$id=$print->id;
//$title=$print->post_title;
	 	echo $id;
	 	//echo $title;
                  $images = get_children( array( 'post_parent' => $id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) ); 





        if ( $images ) { 

                //looping through the images
                foreach ( $images as $attachment_id => $attachment ) {
                ?>
 <li>
          <a  href="<?php echo wp_get_attachment_url($attachment_id,'full'); ?>"><?php echo wp_get_attachment_image( $attachment_id, 'homepage-thumb' ); ?></a>               
                        </li>
                           
                           

                           
                           

                <?php
                }
        }
                    
         }           
                    
                    ?>
                        
                    </ul>
                </div>
            </div>
	
<?php	}?>
    
<?php }
	
				?>

			</div>
			<br/>

	<br/>
			<div class="entry-content">
				<nav class="nav-single">
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'bp-simple_events' ) . '</span> %title' ); ?></span>
					&nbsp; &nbsp;
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'bp-simple_events' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->
			</div>
			

		<?php endwhile; ?>

	</div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>