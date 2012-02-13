<?php

if( ! class_exists( 'WP_List_Table' ) ){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Podlove_Show_List_Table extends WP_List_Table {
	
	function __construct(){
		global $status, $page;
		        
		// Set parent defaults
		parent::__construct( array(
		    'singular'  => 'show',   // singular name of the listed records
		    'plural'    => 'shows',  // plural name of the listed records
		    'ajax'      => false       // does this table support ajax?
		) );
	}
	
	function column_slug( $format ) {
		return $format->slug;
	}

	function column_name( $format ) {
		$tab = ( isset( $_REQUEST[ 'tab' ] ) ) ? $_REQUEST[ 'tab' ] : 'shows';
		$actions = array(
			'edit' => sprintf(
				'<a href="?page=%s&action=%s&show=%s&tab=%s">Edit</a>',
				$_REQUEST['page'],
				'edit',
				$format->id,
				$tab
			)
		);
	
		return sprintf('%1$s %2$s',
		    /*$1%s*/ $format->name,
		    /*$3%s*/ $this->row_actions( $actions )
		);
	}
	
	function column_id( $format ) {
		return $format->id;
	}

	function get_columns(){
		$columns = array(
			'id'   => 'ID',
			'name' => 'Name',
			'slug' => 'Slug'
		);
		return $columns;
	}
	
	function prepare_items() {
		// number of items per page
		$per_page = 10;
		
		// define column headers
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		
		// retrieve data
		// TODO select data for current page only
		$data = Podlove_Show::all();
		
		// get current page
		$current_page = $this->get_pagenum();
		// get total items
		$total_items = count( $data );
		// extrage page for current page only
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ) , $per_page );
		// add items to table
		$this->items = $data;
		
		// register pagination options & calculations
		$this->set_pagination_args( array(
		    'total_items' => $total_items,
		    'per_page'    => $per_page,
		    'total_pages' => ceil( $total_items / $per_page )
		) );
	}

}