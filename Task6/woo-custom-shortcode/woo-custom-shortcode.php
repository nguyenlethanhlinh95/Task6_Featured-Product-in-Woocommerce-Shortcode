<?php 
	/**
	 * Plugin Name: Woo Custom Shortcode Plugin 
	 * Plugin URI: 
	 * Description: This is the Plugin for admin
	 * Version: 1.0.0
	 * Author: ThanhLinh
	 * Author URI: 
	 */

	/**
	 * summary
	 */
	class woo_custom_shortcode
	{
		//public $wptq_settings = '';
	    /**
	     * summary
	     */
	    public function __construct()
	    {
	    	add_shortcode( 'featured_product', array($this,'featured_product') );
	    	// add_shortcode('wporg', 'wporg_shortcode');
	    	/*-----------------------------Create menu admin-----------------------------*/
	        add_action( 'admin_menu', array($this,'create_menu_page') );
	        /*-----------------------------Admin for init-----------------------------*/
	        add_action( 'admin_init', array($this,'page_init') );

	        /*-----------------------------Add button for Product admin-----------------------------*/

	        add_action('media_buttons', array($this,'add_my_media_button'));

	        //add javascript file
	        add_action('admin_enqueue_scripts', array($this,'my_enqueue'));

	        /*-----------------------------Create menu admin-----------------------------*/

	        /*-----------------------------Create checkbox list for list product-----------------------------*/

	        /*-----------------SHOW THE FEATURED POSTS HEAD--------------------------*/
	        add_filter('manage_posts_columns', array($this, 'ST4_columns_head'));

	        /*-----------------SHOW THE FEATURED POSTS CONTENT--------------------------*/
			 add_action('manage_posts_custom_column', array($this, 'ST4_columns_content'), 10, 2);
			// add_theme_support('post-thumbnails');
			// add_image_size('featured_preview', 55, 55, true);

			/*-----------------ADD AJAX--------------------------*/
			add_action( 'admin_footer', array($this, 'my_action_javascript') ); // Write our JS below here

			/*-----------------Update check ajax--------------------------*/
			add_action('wp_ajax_featured_meta_ajax', array($this, 'featured_meta_ajax_function'));

			/*-----------------Add shortcode--------------------------*/
			//add_shortcode( 'featured_product', array($this, 'shortcode_add_featured_product') );
	   		
	   		/*-----------------------------End settings field-----------------------------*/
	    }

	    public function featured_product($atts = [], $content = null)
	    {
	    	$atts = shortcode_atts(
									array(
										'display'   => '',
										'cate'      => '',
										'sortingby' => '',
										'id'        => ""
									), $atts);
	        // do something to $content
	        //ob_start();
			$display 	= $atts['display'];
			$cate    	= $atts['cate'];
			$sortingby 	= $atts['sortingby'];
			$id 	 	= $atts['id'];

			if ($sortingby == "")
			{
				$sortingby = "asc";
			}
			if ($display == "")
			{
				$display = "list";
			}

			ob_start();
			// process cate/ if cate equal null then query for featured product
			if ($cate == "" && $id == "")
			{		
				$args = array( 
					    'meta_key'   => 'meta-checkbox',
					    'meta_value' => 'yes' ,
					    'post_type' => 'product',
					    'order'		=> $sortingby
					    ); 
				$getposts = new WP_Query($args);
				switch($display)
			    {
			    	case "grid":
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-grid.php');
			    		break;
			    	case "list":
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-list.php');
			    		break;

			    	case "carousel":
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-carousel.php');
			    		break;

			    	default:
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-list.php');
			    		break;
			    }
			}
			// cate have value
			else if ($cate != "" && $id == "")
			{
				$dem = 0;
				$args = array( 
					    'hide_empty' => 0,
					    'taxonomy'   => 'product_cat',
					    'order'		=> $sortingby
					    ); 
				$cates = get_categories( $args ); 
			    foreach ( $cates as $cat )
			    {
			    	if ($cat->term_id == $cate )
				 		{ 
				 			$dem = 1; 
				 			break; 
				 		}
			    }
				if ( $dem == 1)
				{
					$getposts = new WP_query(); $getposts->query("post_status=publish&post_type=product&=cate".$cate);
					$check_all_list = 1;
					switch($display)
					    {
					    	case "grid":
					    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-grid.php');
					    	break;

					    	case "list":
					    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-list.php');
					    	break;

					    	case "carousel":
					    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-carousel.php');
					    	break;

					    	default:
					    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-list.php');
					    }
				}
				else
				{
					echo '<h2>Category Not Found !</h2>';
				}
			}
			else if ($id != "" && $cate == "")
			{		

				$list_id = explode(',', $id);
				//print_r($list_id);
				$args = array( 
					    'post_type' => 'product',
					    'post__in'  => $list_id,
					    'order'		=> $sortingby
					    ); 
				$getposts = new WP_Query($args);
				$getposts = new WP_Query($args);
				switch($display)
			    {
			    	case "grid":
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-grid.php');
			    		break;
			    	case "list":
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-list.php');
			    		break;

			    	case "carousel":
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-carousel.php');
			    		break;

			    	default:
			    		include_once(plugin_dir_path( __FILE__ ). 'template-parts/show-list.php');
			    		break;
			    }

			}
			else{
				echo "not found!";
			}

			$list_post = ob_get_clean();
			$list_post .= do_shortcode($content);
			return $list_post;
	    	
	    }
	    


	    /*-------------------------settings for button product popup-------------------------*/
	    public function add_my_media_button(){
		 	echo '<a href="#TB_inline?&width=600&height=550&inlineId=my-content-id" class="button thickbox">Add shortcode</a>';
			//include_once( plugin_dir_path( __FILE__ ) . 'js/myscript.js' );
		 }

		
		/*-------------------------settings for button product popup-------------------------*/


		// javascript file for admin
		function my_enqueue($hook) {
		    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'js/myscript.js','jquery');
		}

		

	    /*-----------------------------Create menu admin-----------------------------*/
	    public function create_menu_page(){
		    add_menu_page( 
		    	'woo_custom_page_page', 
				'Woo Custom Shortcode Plugin',
				'manage_options', 
				'woo_custom_page_posts',
				array($this, 'woo_custom_shortcode_page_content')
		    );
		}

		public function woo_custom_shortcode_page_content(){
		    ?>
			    <div class="wrap">
			        <h1>Hello, welcome to Woocommerce Custom Shortcode Plugin !</h1>		        
			        <form method="post" action="options.php">
			            <?php
				            settings_fields('woo_custom_page_check');

							do_settings_sections('woo_custom_page');

							submit_button();
			            ?>		        	 
			        </form>
			    </div>
		   <?php
		}
		/*-----------------------------End create menu admin-----------------------------*/

		
		/*-----------------------------Page init-----------------------------*/
		public function page_init()
		{
			//add_settings_field('list_cate','Choose Category',array($this,'choose_cate_func'),'setting_page','Sharp_Section');
			add_settings_field('cate','Select Category', array($this, 'select_cate_func'), 'woo_custom_page', 'woo_custom_page_section');
			add_settings_field('ListID','Select ID', array($this, 'select_display_func'), 'woo_custom_page', 'woo_custom_page_section');
			add_settings_field('display','Select display', array($this, 'select_display_func'), 'woo_custom_page', 'woo_custom_page_section');
			
			register_setting('woo_custom_cate', 'list_cate');
			register_setting('woo_custom_ID', 'list_ID');
			register_setting('woo_custom_display', 'display');

			add_settings_section('woo_custom_page_section', '', '', 'woo_custom_page');


		   				      
		}	
		/*-----------------------------end Page init-----------------------------*/
		public function select_cate_func(){
			?>
				<select id="select_Category" name="cate">
			   		<option value="">Choose Category</option>	
				   	<?php $args = array( 
					    'hide_empty' => 0,
					    'taxonomy' => 'category',
					    'orderby' => id,
					    ); 
					    $cates = get_categories( $args ); 
					    foreach ( $cates as $cate ) {  ?>			    
							<option value="<?php echo $cate->term_id;?>"><?php echo $cate->name ?></option>
					<?php } ?>
				</select>
				
			<?php
		}

		public function select_display_func()
		{?>
			<select name="display_type" id="display_type">
				<option value="" style="font-weight: bold;">-- Select Display Type --</option>
				<option value="grid">Show Grid</option>
				<option value="list">Show List</option>
				<option value="carousel">Show Carousel</option>
			</select>
		<?php }


		/*-----------------AJAX FORM META BOX POPUP--------------------------*/
		
		/*-----------------AJAX META BOX POPUP--------------------------*/

		
		/*-----------------ADD NEW COLUMN List Check box --------------------------*/
		function ST4_get_featured_image($post_ID) {
		    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
		    if ($post_thumbnail_id) {
		        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
		        return $post_thumbnail_img[0];
		    }
		}

		public function ST4_columns_head($defaults)
		{
			$defaults['featured_post'] = 'Featured check';
	    	return $defaults;
		}
		
		function ST4_columns_content($column_name, $post_ID) {
		    if ($column_name == 'featured_post') {
		        $featured = get_post_meta( $post_ID );
		        ?>
		            <!-- <img src="' . $post_featured_image . '" style="width:50px; height:50px; object-fit:cover;" /> -->
		            <input type="checkbox" data-id="<?php echo $post_ID ?>" class="check_col" 
	            	<?php if ($featured['meta-checkbox'][0] == "yes") echo "checked"; ?> />
		            
		        <?php

		    }
		    ?>
			
		<?php 
		}
		

		/*-----------------AJAX FOR CHECK PRODUCTS--------------------------*/
		function my_action_javascript() { ?>
			<script type="text/javascript" >
				jQuery(document).ready(function($) {
					$('.check_col').click(function(event) {
						var data = $(this).attr('data-id');
						$.ajax({
			                url: "<?php echo admin_url( 'admin-ajax.php')  ?>",
			                type: 'post',
			                data: {
			                	action:'featured_meta_ajax',
			                    id:data
			                }
			            });
						
					});
				});
			</script> 

			<?php add_thickbox(); ?>
			<div id="my-content-id" style="display:none;">
			     <form>
						<div class="form-group sortby_box">
					   		<label for="exampleFormControlInput1">Display list of Woo Products by</label>
					    	<select name="display_by" class="display_by">
								<option value="" style="font-weight: bold;">-- Select Display by --</option>
								<option value="cate">Category</option>
								<option value="id">List of ID</option>
							</select>
					  	</div>

					  	<div class="form-group ListID">
					   		<label for="SelectListOfID">List of ID</label>
					   		<!-- if choose ListID then will get all Feature Product have check. -->
						   	<div class="wapper_check">
							   	<?php 
							   		$args = array( 
							   			'post_type' => "product",
							   			'post_per_page' => -1,
									    'meta_key'   => 'meta-checkbox',
									    'meta_value' => 'yes' 
								    ); ?>
								<?php $query = new WP_Query($args);?>
								
								<?php if($query->have_posts()): ?>
									<?php while($query->have_posts()): $query->the_post(); ?>

										<div class="form-check">
											<span><input type="checkbox" class="ckb_checkListID" data-id="<?php echo get_the_ID(); ?>"></span>
											<span><?php the_title(); ?></span>
										</div>							
									<?php endwhile; ?>
								<?php endif; ?>
							</div>							  		
					  	</div>

				     	<div class="form-group cate_box">
					   		<label for="exampleFormControlInput1">Choose Category</label>
					    	<select id="select_Category" name="cate" class="select_Category">
						   		<option value="" style="font-weight: bold;">Choose Category</option>	
							   	<?php $args = array( 
								    'taxonomy' => 'product_cat',
								    'orderby'  => 'name'

								    ); 
								    $cates = get_categories( $args ); 
								    foreach ( $cates as $cate ) {  ?>			    
										<option value="<?php echo $cate->term_id;?>"><?php echo $cate->name ?></option>
								<?php } ?>
							</select>
					  	</div>

					  	<div class="form-group sorting_box">
					   		<label for="exampleFormControlInput1">Sorting by</label>
					    	<select name="sorting_by" id="sorting_by">
								<option value="" style="font-weight: bold;">-- Select Display by --</option>
								<option value="asc">ASC</option>
								<option value="desc">DESC</option>
							</select>
					  	</div>

					  	<div class="form-group option_box">
					   		<label for="showlist">Option to show list</label>
					    	<select name="display_type" id="display_type">
								<option value="" style="font-weight: bold;">-- Select Display Type --</option>
								<option value="grid">Show Grid</option>
								<option value="list">Show List</option>
								<option value="carousel">Show Carousel</option>
							</select>
					  	</div>

					  	

					  	<input type="button" id="btn_popup" value="Get code"></input>
			     </form>
			</div>
				
			<style>
				#TB_ajaxContent	{
					margin:1%;
				}
				#TB_ajaxContent .form-group label {
				    cursor: pointer;
				    width: 35%;
				    display: inline-block;
				    margin: 11px;
				    padding-left: 4%;
				    font-weight: bold;
				}
				#TB_ajaxContent #btn_popup{
					border: 0;
				    padding: 8px 15px 8px 15px;
				    background: #ff8500;
				    color: #fff;
				    box-shadow: 1px 1px 4px #dadada;
				    -moz-box-shadow: 1px 1px 4px #dadada;
				    -webkit-box-shadow: 1px 1px 4px #dadada;
				    border-radius: 3px;
				    -webkit-border-radius: 3px;
				    -moz-border-radius: 3px;
				        display: block;
					    float: right;
					    margin-top: 3%;
					    margin-right: 12%;
					        cursor: pointer;
				}
				#TB_ajaxContent #btn_popup:hover{
					background: #ea7b00;
					color: #fff;
					transition: 0.4s;
				}
				#TB_ajaxContent .form-group select{
					width: 45%;
				}


				.cate_box,.ListID{
					display: none;
				}
				.wapper_check{
					display: inline-block;
				}
				.ListID{
					margin-top: 1%;
				}
				.form-check {
				    margin-bottom: 7px;
				}
			</style>


		<?php }

		function featured_meta_ajax_function() {
		    $post_id = $_POST['id'];
		    //$post_id = trim($post_id);
		    //echo $post_id;
		  	echo $post_id;
		    $featured = get_post_meta($post_id);
			if ($featured['meta-checkbox'][0] == '')
			{
				update_post_meta( $post_id, 'meta-checkbox', 'yes' );
			}
			else
			{
				update_post_meta( $post_id, 'meta-checkbox', '' );
			}
			 die();
		}


		
		/*----------------- END AJAX FOR CHECK PRODUCTS--------------------------*/


	} //end class

	$woo_custom_shortcode = new woo_custom_shortcode();