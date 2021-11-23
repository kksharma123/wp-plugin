<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Gallery_list extends WP_List_Table {

	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Gallery Info', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Gallery Info', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve products data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_products( $per_page = 10, $page_number = 1 ) {
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}ctbp_gallery";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$results = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $results;
	}


	/**
	 * Delete a product record.
	 *
	 * @param int $id product ID
	 */
	public static function delete_product( $id ) {
		global $wpdb;
		$wpdb->delete(
			"{$wpdb->prefix}ctbp_gallery",
			[ 'id' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}ctbp_gallery";
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no product data is available */
	public function no_items() {
		_e( 'No gallery avaliable.', 'sp' );
	}
	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':

			case 'gallery_title':
			case 'gallery_id':
			case 'gallery_shortcode':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
	}
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_id( $item ) {
		$actions = array(
           //'edit'      => sprintf('<a href="?page='.ctbp_init()->get_slug().'-model&caritem=%s">Edit</a>',$item['id']),
           //'delete'    => sprintf('<a href="?page='.ctbp_init()->get_slug().'-allcars&action=delete&id=%s" onclick="return confirm(\'Are you sure\')">Delete</a>',$item['id']),
          
        );

		return sprintf('%1$s %2$s', $item['id'], $this->row_actions($actions) );
	}
	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			//'cb'      => '<input type="checkbox" />',
			'id'      => __( 'id', 'sp' ),
			'gallery_title'      => __( 'gallery_title', 'sp' ),
			'gallery_id'    => __( 'gallery_id', 'sp' ),
			'gallery_shortcode' => __( 'gallery_shortcode', 'sp' ),
		];
		return $columns;
	}
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'id' => array( 'id', true ),
			'gallery_title' => array( 'gallery_title', true ),
			'gallery_id' => array( 'gallery_id', true ),
			'gallery_shortcode' => array( 'gallery_shortcode', false ),
			
		);
		return $sortable_columns;
	}
	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		// $actions = [
		// 	'bulk-delete' => 'Delete'
		// ];
		return $actions;
	}
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		 $columns = $this->get_columns();
		  $hidden = array();
		  $sortable = $this->get_sortable_columns();
		  $this->_column_headers = array($columns, $hidden, $sortable);
		/** Process bulk action */
		$this->process_bulk_action();
		$per_page     = get_option('posts_per_page');
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );
		$this->items = self::get_products( $per_page, $current_page );
	}

	public function process_bulk_action() {
		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );
			if ( ! wp_verify_nonce( $nonce, 'sp_delete_product' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_product( absint( $_GET['p'] ) );
		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}
		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_product( $id );
			}
			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}
}
?>