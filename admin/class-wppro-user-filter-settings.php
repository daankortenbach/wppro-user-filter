<?php

/**
 * The settings of the plugin.
 *
 * @link       https://wppro.nl/
 * @since      1.0.0
 *
 * @package    Wppro_User_Filter
 * @subpackage Wppro_User_Filter/admin
 */

/**
 * Class Wppro_User_Filter_Admin_Settings
 *
 * @since      1.0.0
 * @package    Wppro_User_Filter
 * @subpackage Wppro_User_Filter/admin
 * @author     Daan Kortenbach <daan@wppro.nl>
 */
class Wppro_User_Filter_Admin_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Adds the User Filter admin menu into the 'Users' menu and calls the function to render the page.
	 *
	 * @since 1.0.0
	 * @uses  add_users_page() to add the menu item and call the function to render the page.
	 */
	public function setup_admin_menu() {

		//Add the menu to the Plugins set of menu items
		add_users_page(
			'User Filter', 					              // The title to be displayed in the browser window for this page.
			'User Filter',					              // The text to be displayed for this menu item
			'manage_options',					          // Which type of users can see this menu item
			'wppro_user_filter',			              // The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content') // The name of the function to call when rendering this menu's page
		);

	}

	/**
	 * Adds the templates and JavaScript/jQuery/AJAX to render the user table, filter and order the users.
	 *
	 * @since 1.0.0
	 *
	 * @uses  get_current_screen()
	 * @uses  count_users()
	 */
	public function get_users_js() {

		// Get current afdmin page
		$page = get_current_screen();

		// Exit early if this is not the intended page.
		if ( $page->base != 'users_page_wppro_user_filter' ) {
			return;
		}

		// Get user count and available roles.
		$user_count = count_users();

		// Unset the 'none' role.
		unset( $user_count['avail_roles']['none'] );

		//-- START JavaScript to be added in wp_footer.
		?>

		<script type="text/html" id="tmpl-displaying-num">
			<span class="displaying-num">{{{data.displayingNum}}} items</span>
		</script>

		<script type="text/html" id="tmpl-user-row">
			<tr id="user-{{{data.id}}}">
				<td class="username column-username has-row-actions column-primary" data-colname="Username">
					<img alt="" src="{{{data.gravatar32}}}" srcset="{{{data.gravatar64}}} 2x" class="avatar avatar-32 photo" height="32" width="32">
					<strong><a href="/wp-admin/user-edit.php?user_id={{{data.id}}}&wp_http_referer=%2Fwp-admin%2Fusers.php?page=wppro_user_filter">{{{data.login}}}</a></strong>
					<div class="row-actions"><span class="edit"><a href="/wp-admin/user-edit.php?user_id={{{data.id}}}&amp;wp_http_referer=%2Fwp-admin%2Fusers.php?page=wppro_user_filter">{{{data.edit}}}</a></span></div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">{{{data.more}}}</span></button>
				</td>
				<td class="name column-name" data-colname="Name">{{{data.name}}}</td>
				<td class="role column-role" data-colname="Role">{{{data.role}}}</td>
			</tr>
		</script>

		<script type="text/javascript" >
		jQuery(document).ready(function($) {

			var data = {
				'action': 'wppro_get_users',
				'paged': 1,
				'role': 'all'
			};

			var allIsClicked = false;
			$('.user-roles .all a').click(function(event){
				event.preventDefault();
				allIsClicked = true;
				data['role'] = 'all';
				data['paged'] = 1;
				data['order'] = 'asc';
				data['orderby'] = 'user_login';
				getUsers(data);
			});

			if ( allIsClicked == false ) {
				$('.user-roles a').click(function(event){
					event.preventDefault();
					var userrole = $(this).data('userrole');
					data['role'] = userrole;
					data['paged'] = 1;
					getUsers(data);
				});
			}

			$('#username a').click(function(event){
				event.preventDefault();
				$(this).blur();
				var order = $(this).data('order');
				data['order'] = order;
				data['orderby'] = 'user_login';
				getUsers(data);
				if(data['order']=='asc'){
					$(this).data({'order': 'desc'});
					$('#username').removeClass('sortable desc').addClass('sorted asc');
					$('#name').removeClass('sorted asc').addClass('sortable desc');
				};
				if(data['order']=='desc'){
					$(this).data({'order': 'asc'});
					$('#username').removeClass('asc').addClass('desc');
				};
			});

			$('#name a').click(function(event){
				event.preventDefault();
				$(this).blur();
				var order = $(this).data('order');
				data['order'] = order;
				data['orderby'] = 'name';
				getUsers(data);
				if(data['order']=='asc'){
					$(this).data({'order': 'desc'});
					$('#name').removeClass('sortable desc').addClass('sorted asc');
					$('#username').removeClass('sorted asc').addClass('sortable desc');
				};
				if(data['order']=='desc'){
					$(this).data({'order': 'asc'});
					$('#name').removeClass('asc').addClass('desc');
				};
			});

			$('a.first-page').click(function(event){
				event.preventDefault();
				data['paged'] = 1;
				getUsers(data);
			});
			$('a.prev-page').click(function(event){
				event.preventDefault();
				data['paged'] = data['paged'] - 1;
				getUsers(data);
			});
			$('a.next-page').click(function(event){
				event.preventDefault();
				data['paged'] = data['paged'] + 1;
				getUsers(data);
			});
			$('a.last-page').click(function(event){
				event.preventDefault();
				data['paged'] = $total_pages;
				getUsers(data);
			});

			function getUsers(data) {

				$.post(ajaxurl, data, function(response) {

					$('#the-list').html('');

					var parsedUsers = JSON.parse( response );
					var template = wp.template('user-row');

					parsedUsers.users.forEach(function(item){
						$('#the-list').append( template( {
							id: item.ID,
							login: item.login,
							name: item.name,
							email: item.email,
							role: item.role,
							gravatar32: item.gravatar[32],
							gravatar64: item.gravatar[64],
							edit: parsedUsers.text.edit,
							more: parsedUsers.text.more
						} ) );
					});

					var avail_users    = parsedUsers.user_count.avail_roles;
					avail_users['all'] = parsedUsers.user_count.total_users;

					$.each( avail_users, function( key, value ) {
						$('.' + key + ' a').removeClass('current');
						if ( data.role == key ) {
							$('.' + key + ' a').addClass('current');
						}
					});

					var template = wp.template('displaying-num');

					if ( data.role !== 'all' ) {
						var displaying_num = parsedUsers.user_count.avail_roles[parsedUsers.role];
						$('.displaying-num').html( template( {
							displayingNum: displaying_num,
						} ) );
						$total_pages = Math.ceil( displaying_num / 10 );
					}
					else {
						var displaying_num = parsedUsers.user_count.total_users
						$('.displaying-num').html( template( {
							displayingNum: displaying_num,
						} ) );
						$total_pages = Math.ceil( displaying_num / 10 );
					}

					if ( displaying_num <= 10 ) {
						$('.pagination-links').hide();
					}
					if ( displaying_num > 10 ) {
						$('.pagination-links').show();
					}

					if ( data['paged'] > 2 ) {
						$('.first-page').show();
						$('.first-page.tablenav-pages-navspan').hide();
					}
					else {
						$('.first-page').hide();
						$('.first-page.tablenav-pages-navspan').show();
					}

					if ( data['paged'] == 1 ) {
						$('.prev-page').hide();
						$('.prev-page.tablenav-pages-navspan').show();
					}
					else {
						$('.prev-page').show();
						$('.prev-page.tablenav-pages-navspan').hide();
					}

					if ( data['paged'] >= $total_pages ) {
						$('.next-page').hide();
						$('.next-page.tablenav-pages-navspan').show();
					}
					else {
						$('.next-page').show();
						$('.next-page.tablenav-pages-navspan').hide();
					}

					if ( data['paged'] < ($total_pages - 1) ) {
						$('.last-page').show();
						$('.last-page.tablenav-pages-navspan').hide();
					}
					else {
						$('.last-page').hide();
						$('.last-page.tablenav-pages-navspan').show();
					}

					$('.current-page').html( data['paged'] );
					$('.total-pages').html( $total_pages );

				});
			}

			getUsers(data);
		});
		</script>
		<?php
		//-- END JavaScript to be added in wp_footer.
	}

	/**
	 * Admin AJAX callback returns a JSON string with a filtered and ordered user set.
	 *
	 * @since 1.0.0
	 *
	 * @uses count_users()
	 * @uses wp_get_current_user()
	 * @uses get_userdata()
	 * @uses get_avatar_url()
	 */
	public function wppro_get_users() {

		$user_count = count_users();
		unset( $user_count['avail_roles']['none'] );
		$role = 'all';
		if ( array_key_exists( $_POST['role'], $user_count['avail_roles'] ) ) {
			$role = $_POST['role'];
		}
		if ( $_POST['paged'] ) {
			$paged = intval( $_POST['paged'] );
		}
		if ( $_POST['orderby'] == 'user_login' ) {
			$args = array(
				'orderby'  => 'user_login',
			);
		}
		if ( $_POST['orderby'] == 'name' ) {
			$args = array(
				'meta_key' => 'first_name',
				'orderby'  => 'meta_value',
			);
		}
		if ( $_POST['order'] == 'asc' ) {
			$args['order'] = 'asc';
		}
		if ( $_POST['order'] == 'desc' ) {
			$args['order'] = 'desc';
		}
		if ( $role != 'all' ) {
			$args['role'] = $role;
		}
		if ( isset( $paged ) ) {
			$args['paged'] = $paged;
		}

		$args['number'] = 10;

		$users  = get_users( $args );
		$output = array();

		foreach ( $users as $key => $value ) {

			// Set current user role if role is empty in $value (could be a WordPress bug)
			$current_user = wp_get_current_user();
			$current_user_role = ( array ) $current_user->roles;
			if ( $value->role == '' && $value->ID == $current_user->ID ) {
				$value->role = $current_user_role[0];
			}

			$user_info = get_userdata( $value->ID );

			$output['user_count'] = $user_count;
			$output['role'] = $role;
			$output['result_count'] = count($users);
			$output['text']['edit'] = __( 'Edit', 'wppro-user-filter' );
			$output['text']['more'] = __( 'Show more details', 'wppro-user-filter' );
			$output['users'][$key]['ID'] = $value->ID;
			$output['users'][$key]['login'] = esc_attr( $value->user_login );
			$output['users'][$key]['email'] = esc_attr( $value->user_email );
			$output['users'][$key]['name'] = esc_attr( $user_info->first_name ) . ' ' . esc_attr( $user_info->last_name );
			$output['users'][$key]['role'] = esc_attr( $value->role );
			$output['users'][$key]['gravatar']['32'] = get_avatar_url( $value->user_email, array( 'size' => 32 ) );
			$output['users'][$key]['gravatar']['64'] = get_avatar_url( $value->user_email, array( 'size' => 64 ) );
		}

		echo json_encode( $output );

		wp_die(); // this is required to terminate immediately and return a proper response
	}

	/**
	 * Renders the part of the User Filter admin page that doesn't need to be renderen by JavaScript.
	 *
	 * @since 1.0.0
	 *
	 * @uses  global $wp_roles
	 * @uses  count_users()
	 */
	public function render_settings_page_content() {

		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
		    $wp_roles = new WP_Roles();
		}
		$role_names = $wp_roles->get_names();

		$user_count = count_users();
		unset( $user_count['avail_roles']['none'] );


		// Make the user roles list items
		$user_roles_list = sprintf(
			'<li class="all"><a href="users.php?page=wppro_user_filter" class="current" aria-current="page" data-userrole="all">All <span class="count">(%s)</span></a> |</li>',
			$user_count['total_users']
		);
		$role_count = count( $role_names );
		$i = 1;
		foreach ($role_names as $key => $value) {
			$delimiter = ' |';
			if ( $role_count == $i ) {
				$delimiter = '';
			}
			$i++;

			$count = 0;
			if ( isset( $user_count['avail_roles'][$key] ) ) {
				$count = $user_count['avail_roles'][$key];
				$user_roles_list .= sprintf(
					'<li class="%1$s"><a href="users.php?page=wppro_user_filter&role=%1$s" data-userrole="%1$s">%2$s <span class="count">(%3$s)</span></a>%4$s</li>',
					$key,
					$value,
					$count,
					$delimiter
				);
			}
		}

		// Make the table navigation links
		if ( isset( $_GET['paged'] ) ) {
			$paged = intval( $_GET['paged'] );
		}
		else {
			$paged = 1;
		}
		$number_of_pages = ceil( $user_count['total_users'] / 10  );

		$tablenavpages = sprintf( '
			<span class="displaying-num">%s items</span>
			<span class="pagination-links">
				<a style="display: none" class="first-page" href="#"><span class="screen-reader-text">%s</span><span aria-hidden="true">&laquo;</span></a><span class="first-page tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
				<a style="display: none" class="prev-page" href="#"><span class="screen-reader-text">%s</span><span aria-hidden="true">&lsaquo;</span></a><span class="prev-page tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
				<span class="screen-reader-text">%s</span>
				<span id="table-paging" class="paging-input"><span class="tablenav-paging-text"><span class="current-page">%s</span> of <span class="total-pages">%s</span></span></span>
				<a class="next-page" href="#"><span class="screen-reader-text">%s</span><span aria-hidden="true">&rsaquo;</span></a><span style="display: none" class="next-page tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
				<a class="last-page" href="#"><span class="screen-reader-text">%s</span><span aria-hidden="true">&raquo;</span></a><span style="display: none"  class="last-page tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
			</span>',
			$user_count['total_users'],
			__( 'First page', 'wppro-user-filter' ),
			__( 'Previous page', 'wppro-user-filter' ),
			__( 'Current page', 'wppro-user-filter' ),
			$paged,
			$number_of_pages,
			__( 'Next page', 'wppro-user-filter' ),
			__( 'Last page', 'wppro-user-filter' )
		);

		$sort_username = sprintf(
			'<th scope="col" id="username" class="manage-column column-username column-primary sortable desc">
				<a href="" data-order="asc"><span>%s</span><span class="sorting-indicator"></span></a>
			</th>',
			__( 'Username', 'wppro-user-filter' )
		);

		$sort_fullname = sprintf(
			'<th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
				<a href="" data-order="asc"><span>%s</span><span class="sorting-indicator"></span></a>
			</th>',
			__( 'Name', 'wppro-user-filter' )
		);
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">
			<h2><?php _e( 'User Filter', 'wppro-user-filter' ); ?></h2>
			<div class="tablenav top">
				<ul class="subsubsub user-roles"><?php echo $user_roles_list ?></ul>
				<div class="tablenav-pages"><?php echo $tablenavpages ?></div>
			</div>
			<table class="wp-list-table widefat fixed striped users">
				<thead>
					<tr>
						<?php echo $sort_username ?>

						<?php echo $sort_fullname ?>

						<th scope="col" id="role" class="manage-column column-role">Role</th>
					</tr>
				</thead>
				<tbody id="the-list" data-wp-lists="list:user"></tbody>
				<tfoot>
					<tr>
						<?php echo $sort_username ?>
						<?php echo $sort_fullname ?>
						<th scope="col" id="role" class="manage-column column-role">Role</th>
					</tr>
				</tfoot>
			</table>
			<div class="tablenav bottom">
				<div class="tablenav-pages">
					<div class="tablenav-pages"><?php echo $tablenavpages ?></div>
				</div>
				<br class="clear">
			</div>
		</div><!-- /.wrap -->
	<?php
	}
}
