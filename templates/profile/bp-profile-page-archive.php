<style>
#event_search{
	display:none;
	
	
	}
</style>
<?php

/**
 * Template for looping through expired Events on a member profile page
 * You can copy this file to your-theme/buddypress/members/single
 * and then edit the layout. 
 */

$paged = ( isset( $_GET['ep'] ) ) ? $_GET['ep'] : 1;

$args = array(
	'post_type'      => 'event',
	'author'         => bp_displayed_user_id(),
	'order'          => 'ASC',
	'orderby'		 => 'meta_value_num',
	'meta_key'		 => 'event-unix',
	'paged'          => $paged,
	'posts_per_page' => 5,

	'meta_query' => array(
		array(
			'key'		=> 'event-unix',
			'value'		=> current_time( 'timestamp' ),
			'compare'	=> '<=',
			'type' 		=> 'NUMERIC',
		),
	),

);

$wp_query = new WP_Query( $args );

$user_link = bp_core_get_user_domain( bp_displayed_user_id() );

?>

<?php if ( $wp_query->have_posts() ) : ?>
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="text" name="s" id="s" value="Enter event ..." onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
	<select name="post_type" id="event_search">
		
		<option value="event">event</option>
	
	</select>
	<input type="submit" id="searchsubmit" value="Search" />
</form>
	<div class="entry-content"><br/>
		<?php echo bp_events_profile_pagination( $wp_query ); ?>
	</div>
<table id="current-events" cellspacing="10" cellpadding="10">
<thead>
<tr>
<th id="event-description" width="*">Event</th>
<th id="event-time" width="150">Date/Time</th>
<th id="" width="150">Actions</th>
</tr>
</thead>
<tbody>
<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); 	
$meta = get_post_meta($post->ID );
?>
<tr>

<td>
<div style="float:left; margin:0px 10px 0px 0px;">
<?php 
				
				if( ! empty( $meta['event-myfile'][0] ) )
				{?>
					

				<img class="attachment-100x100 size-100x100 wp-post-image" width="100" height="100" alt="Pierce the Veil" src="<?php echo $meta['event-myfile'][0];?>">
			<?php }
			
			else{
				?>
				<img class="attachment-100x100 size-100x100 wp-post-image" width="100" height="100" alt="Pierce the Veil" src="<?php  echo plugin_dir_url(__FILE__); ?>/fff.png">
				
		<?php		}	?>

</div>
<a title="Pierce the Veil" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
<br>
<i><?php the_excerpt(); ?></i>
</td>
<td>
<?php
if( ! empty( $meta['event-date'][0] ) )
				echo __( 'Date', 'bp-create-events' ) . ':&nbsp;' . $meta['event-date'][0];
?> 
<br>
<?php
	if( ! empty( $meta['event-time'][0] ) )
				echo '<br/>' . __( 'Time', 'bp-create-events' ) . ':&nbsp;' . $meta['event-time'][0];
?> 
</td>
<td>
<?php
			if( bp_is_my_profile() || is_super_admin() ) {

				$edit_link = wp_nonce_url( $user_link . 'events/create?eid=' . $post->ID, 'editing', 'edn');

				$delLink = get_delete_post_link( $post->ID );

			?>
<span class="view"><a href="<?php the_permalink(); ?>" title="Edit  Event">View</a></span>
&nbsp; &nbsp;
				<span class="edit"><a href="<?php echo $edit_link; ?>" title="Edit  Event">Edit</a></span>
				&nbsp; &nbsp;
				<span class="trash"><a onclick="return confirm('Are you sure you want to delete this Event?')" href="<?php echo $delLink; ?>" title="Delete Event" class="submit">Delete</a></span>
            
			<?php } ?>

</td>
</tr>
<?php endwhile; ?>

<?php wp_reset_query(); ?>
</table>

	
<?php else : ?>

	<div class="entry-content"><br/>There are no expired Events for this member.</div>


<?php endif; ?>
