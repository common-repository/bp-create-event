<?php

/**
 * Template for creating or editing Events on a member profile page
 * You can copy this file to your-theme/buddypress/members/single
 * and then edit the layout. 
 */

global $pp_ec;  // access to BP_Simple_Events_Create singleton
$required_fields = get_option( 'pp_events_required' );
?>
<style>
.img-wrap {
    position: relative;
    display: inline-block;
    border: 1px red solid;
    font-size: 0;
}
.img-wrap .close {
    position: absolute;
    top: 2px;
    right: 2px;
    z-index: 100;
    background-color: #FFF;
    padding: 5px 2px 2px;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    opacity: .2;
    text-align: center;
    font-size: 22px;
    line-height: 10px;
    border-radius: 50%;
}
.img-wrap:hover .close {
    opacity: 1;
}
</style>

<form id="profile-event-form" name="profile-event-form" method="post" action="" class="standard-form" enctype="multipart/form-data">

	<p>
		<label for="event-title"><?php echo __( 'Title', 'bp-simple-events' ); ?>: *</label>
		<input type="text" id="event-title" name="event-title" value="<?php echo $pp_ec->title; ?>" />
	</p>

	<p>
		<label for="event-description"><?php echo __( 'Description', 'bp-simple-events' ); ?>: *</label>
		<textarea id="event-description" name="event-description" ><?php echo $pp_ec->description; ?></textarea>
	</p>
<p><input type="checkbox" name="single_event" value="click for single day event" id="single_event">Click for single day event</p>
	<p>
		<label for="event-date"><?php echo __( 'Start Date', 'bp-simple-events' ); ?>: <?php if( in_array('date', $required_fields) ) echo __( '*', 'bp-simple-events' ); ?></label>
		<input type="text" id="event-date" name="event-date" placeholder="<?php echo __( 'Click to add Start Date...', 'bp-simple-events' ); ?>" value="<?php echo $pp_ec->date; ?>" />
	</p>
<p id="end_part">
		<label for="end-date"><?php echo __( 'End Date', 'bp-create-events' ); ?>: <?php if( in_array('date2', $required_fields) ) echo __( '*', 'bp-create-events' ); ?></label>
		<input type="text" id="end-date" name="end-date" placeholder="<?php echo __( 'Click to add End Date...', 'bp-create-events' ); ?>" value="<?php echo $pp_ec->date2; ?>" />
	</p>
	<p>
		<label for="event-time"><?php echo __( 'Time', 'bp-simple-events' ); ?>: <?php if( in_array('time', $required_fields) ) echo __( '*', 'bp-simple-events' ); ?></label>
		<input type="text" id="event-time" name="event-time" placeholder="<?php echo __( 'Click to add Time...', 'bp-simple-events' ); ?>" value="<?php echo $pp_ec->time; ?>" />
	</p>

	<p>
		<label for="event-location"><?php echo __( 'Location', 'bp-simple-events' ); ?>: <?php if( in_array('location', $required_fields) ) echo __( '*', 'bp-simple-events' ); ?></label>
		<input type="text" id="event-location" name="event-location" placeholder="<?php echo __( 'Start typing location name...', 'bp-simple-events' ); ?>" value="<?php echo $pp_ec->address; ?>" />
	</p>

	<p>
		<label for="event-url"><?php echo __( 'Url', 'bp-simple-events' ); ?>: <?php if( in_array('url', $required_fields) ) echo __( '*', 'bp-simple-events' ); ?></label>
		<input type="text" size="80" id="event-url" name="event-url" placeholder="<?php echo __( 'Add an Event-related Url...', 'bp-simple-events' ); ?>" value="<?php echo $pp_ec->url; ?>" />
	</p>
<label>Social Icons:</label>
	
	
	<p>
		<label for="event-facebook"><?php echo __( 'Facebook', 'bp-create-events' ); ?>: <?php if( in_array('facebook', $required_fields) ) echo __( '*', 'bp-create-events' ); ?></label>
		<input type="text" size="80" id="event-facebook" name="event-facebook" placeholder="<?php echo __( 'Add Facebook Url', 'bp-create-events' ); ?>" value="<?php echo $pp_ec->facebook; ?>" />
	</p>
<p>
		<label for="event-twitter"><?php echo __( 'Twitter', 'bp-create-events' ); ?>: <?php if( in_array('twitter', $required_fields) ) echo __( '*', 'bp-create-events' ); ?></label>
		<input type="text" size="80" id="event-twitter" name="event-twitter" placeholder="<?php echo __( 'Add Twitter Url...', 'bp-create-events' ); ?>" value="<?php echo $pp_ec->twitter; ?>" />
	</p>
<p>
		<label for="event-google"><?php echo __( 'Google', 'bp-create-events' ); ?>: <?php if( in_array('google', $required_fields) ) echo __( '*', 'bp-create-events' ); ?></label>
		<input type="text" size="80" id="event-google" name="event-google" placeholder="<?php echo __( 'Add Google Url...', 'bp-create-events' ); ?>" value="<?php echo $pp_ec->google; ?>" />
	</p>

<p>
		<label for="file">Banner Image:</label>

    	<input type="file" name="myfile" id="myfile" value="<?php echo $pp_ec->myfile; ?>"><br/><br/>
    	
    	<img src="<?php echo $pp_ec->myfile; ?>">
</p>
<p>
<label for="file">Gallery Images:</label>
<input type="file" name="upload_attachment[]" id="uploading" class="files" size="50" multiple="multiple"/>
<?php

// $pp_ec->upload_attachment; 
if($pp_ec->upload_attachment != '')
{
	if (count($pp_ec->upload_attachment)> 0){ ?>
   
                <div class="inner">
                    
                   <?php 
foreach ( $pp_ec->upload_attachment as $print )   {
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
                
 <div class="img-wrap" id="img-<?php echo $attachment_id;?>">
    <span class="close">&times;</span>
    <a data-id="<?php echo $attachment_id;?>"><?php echo wp_get_attachment_image( $attachment_id, 'homepage-thumb' ); ?></a> 
</div>
                       
  <?php
                }
        }
                    
         }           
	
	}
?>
                        
                    
                </div>
           
<?php }
	
				?>





<?php wp_nonce_field( 'upload_attachment', 'my_image_upload_nonce' ); ?>
</p>
	<?php 
		$args = array(
			'type'                     => 'post',
			'child_of'                 => 0, //get_cat_ID( 'Events' ),
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'category',
			'pad_counts'               => false
		);

		$categories = get_categories( $args );
	?>

	<?php if( ! empty( $categories ) ) : ?>

		<p>
			<label for="event-cats"><?php echo __( 'Categories', 'bp-simple-events' ); ?>: <?php if( in_array('categories', $required_fields) ) echo __( '*', 'bp-simple-events' ); ?></label>
			<?php
				foreach( $categories as $category ) {

					$checked = '';
					if( in_array( $category->term_id, $pp_ec->cats_checked ) )
						$checked = ' checked';

					echo '&nbsp;&nbsp;<input type="checkbox" name="event-cats[]" value="' . $category->term_id . '"' . $checked . '/> ' . $category->name . '<br/>';
				}
			?>
		</p>

	<?php endif; ?>	
	

	<input type="hidden" id="event-address" name="event-address" value="<?php echo $pp_ec->address; ?>" />
	<input type="hidden" id="event-latlng" name="event-latlng"  value="<?php echo $pp_ec->latlng; ?>" />
	<input type="hidden" name="action" value="event-action" />
	<input type="hidden" name="eid" value="<?php echo $pp_ec->post_id; ?>" />
	<?php wp_nonce_field( 'event-nonce' ); ?>

	<input type="submit" name="submit" class="button button-primary" value="<?php echo __(' SAVE ', 'bp-simple-events'); ?>"/>

</form>