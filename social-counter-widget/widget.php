<?php
/**
 * @package Social Counter
 */

class Social_Counter extends WP_Widget {

	function __construct() {
		parent::__construct(
			'Social_Counter',
			__( 'Social Counter' ),
			array( 'description' => __( 'Display count number of each social media' ) )
		);

		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_head', array( $this, 'css' ) );
		}
	}

	function css() {
		?>
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
		<style type="text/css">

		ul.subscribe {margin: 0; } ul.subscribe li {margin: 0 0 5px 0; padding: 0; border: none; list-style: none; } ul.subscribe li a {display: block; color: #fff !important; text-decoration: none; font-size: 13px; line-height: 18px; position: relative; } ul.subscribe li a span {display: block; height: 50px; } ul.subscribe li a span i {font-weight: 700; font-size: 16px; display: block; padding-top: 6px; } ul.subscribe li a:before {font-family: 'FontAwesome'; display: block; float: left; color: #FFF; width: 50px; height: 50px; line-height: 50px; text-align: center; font-size: 30px; margin: 0px 10px 0 0; } ul.subscribe li a:hover {color: #fff; background: #333 !important; } ul.subscribe li a:hover:before {color: #fff; } ul.subscribe li a, ul.subscribe li a:before {-webkit-transition: all 0.2s linear; -moz-transition: all 0.2s linear; -o-transition: all 0.2s linear; transition: all 0.2s linear; } ul.subscribe li.rss a {background: #f8bc2e; } ul.subscribe li.rss a:before {content: "\f09e"; } ul.subscribe li.twitter a {background: #45b0e3; } ul.subscribe li.twitter a:before {content: "\f099"; } ul.subscribe li.facebook a {background: #39599f ; } ul.subscribe li.facebook a:before {content: "\f09a"; } ul.subscribe li.YouTube a {background: #e70031; } ul.subscribe li.YouTube a:before {content: "\f167"; } ul.subscribe li.dribbble a {background: #d97aa5; } ul.subscribe li.dribbble a:before {content: "\f17d"; } ul.subscribe li.vimeo a {background: #7edde8; } ul.subscribe li.vimeo a:before {content: "\f194"; } ul.subscribe li.github a {background: #666 ; } ul.subscribe li.github a:before {content: "\f113"; }

		</style>

		<?php
	}

	function widget( $args, $instance ) {

		extract($args);
		$Social = new Social();


		echo $before_widget;

			if ( !empty($instance['title']) ){
				echo $before_title;
				echo $instance['title'];
				echo $after_title;
			}

			?>

			<ul class="subscribe">

			<?php if(isset($instance['dribbble'])):

				$dribbble = $Social->dribbble($instance['dribbble']);?>

				<li class="clearfix dribbble">
					<a href="<?php echo $instance['dribbble']; ?>" target="_blank">
						<span><i><?php echo @number_format( $dribbble ) ?></i> <?php _e('Followers') ?> </span>
					</a>
				</li>

			<?php endif; ?>


            <?php if(!empty(($instance['vimeo']) ) ):

				$vimeo = $Social->vimeo($instance['vimeo']);?>

				<li class="clearfix vimeo">
					<a href="<?php echo $instance['vimeo']; ?>" target="_blank">
						<span><i><?php echo @number_format( $vimeo ) ?></i> <?php _e('Subscribers') ?> </span>
					</a>
				</li>

			<?php endif; ?>


            <?php if(!empty( $instance['youtube'] )):

				$youtube = $Social->youtube($instance['youtube']);?>

				<li class="clearfix YouTube">
					<a href="<?php echo $instance['youtube']; ?>" target="_blank">
						<span><i><?php echo @number_format( $youtube ) ?></i> <?php _e('Subscribers') ?> </span>
					</a>
				</li>

			<?php endif; ?>


            <?php if( !empty( $instance['facebook'] ) ):

				$facebook = $Social->facebook($instance['facebook']);?>

				<li class="clearfix facebook">
					<a href="<?php echo $instance['facebook'];?>" target="_blank">
						<span><i><?php echo @number_format( $facebook ) ?></i> <?php _e('Fans') ?> </span>
					</a>
				</li>

			<?php endif; ?>

			<?php if( !empty( $instance['github'] ) ):

				$github = $Social->github($instance['github']);?>

				<li class="clearfix github">
					<a href="<?php echo $instance['github'] ?>" target="_blank">
						<span><i><?php echo @number_format( $github ) ?></i> <?php _e('Followers') ?></span>
					</a>
				</li>

			<?php endif; ?>


			</ul>


			<?php

		echo $after_widget;

	}


	function form( $instance ) {

		$defaults = array(
				'title' =>__( 'SUBSCRIBE WIDGET' ),
				'youtube' => '',
				'vimeo' => '',
				'facebook' => '',
				'dribbble' => '',
				'github'   => ''
			);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>">Facebook Page URL : </label>
			<input placeholder="http://www.facebook.com/username/" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php echo $instance['facebook']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>">Youtube Channel URL : </label>
			<input placeholder="http://www.youtube.com/user/username" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" value="<?php echo $instance['youtube']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'vimeo' ); ?>">Vimeo Channel URL : </label>
			<input placeholder="http://vimeo.com/channels/username" id="<?php echo $this->get_field_id( 'vimeo' ); ?>" name="<?php echo $this->get_field_name( 'vimeo' ); ?>" value="<?php echo $instance['vimeo']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'dribbble' ); ?>">dribbble Page URL : </label>
			<input placeholder="http://dribbble.com/username" id="<?php echo $this->get_field_id( 'dribbble' ); ?>" name="<?php echo $this->get_field_name( 'dribbble' ); ?>" value="<?php echo $instance['dribbble']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'github' ); ?>">github Page URL : </label>
			<input placeholder="http://github.com/username" id="<?php echo $this->get_field_id( 'github' ); ?>" name="<?php echo $this->get_field_name( 'github' ); ?>" value="<?php echo $instance['github']; ?>" class="widefat" type="text" />
		</p>

		<?php

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['facebook'] 	= strip_tags( $new_instance['facebook'] );
		$instance['youtube'] 	= strip_tags( $new_instance['youtube'] );
		$instance['vimeo'] 		= strip_tags( $new_instance['vimeo'] );
		$instance['dribbble'] 	= strip_tags( $new_instance['dribbble'] );
		$instance['github'] 	= strip_tags( $new_instance['github'] );
		$instance['prefix'] 	= strip_tags( $new_instance['prefix'] );
		$instance['title']		= strip_tags( $new_instance['title'] );
		$Social = new Social();
		$Social->reset();
		return $instance;
	}


}

function Social_register_widgets() {
	register_widget( 'Social_Counter' );
}

add_action( 'widgets_init', 'Social_register_widgets');
