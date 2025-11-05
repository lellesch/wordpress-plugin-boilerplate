<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Rest;

use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Instance_Trait;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Example_Rest_API {

	use Singleton_Instance_Trait;

	protected string $namespace     = 'wp-plugin-name/v1';
	protected string $resource_name = 'example';

	public function init(): void {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes(): void {
		$namespace = $this->namespace;
		$resource  = $this->resource_name;

		register_rest_route(
			$namespace,
			'/' . $resource,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE, // GET
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE, // POST
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
				),
			)
		);
	}

	public function get_items_permissions_check( $request ): bool {
		return current_user_can( 'read' );
	}

	public function create_item_permissions_check( $request ): bool {
		return current_user_can( 'edit_posts' );
	}


	public function get_items( $request ): WP_REST_Response {
		$data = array( 'message' => 'Hello, this is a GET request.' );

		return new WP_REST_Response( $data, 200 );
	}

	public function create_item( $request ): WP_REST_Response {
		$parameters = $request->get_params();
		$data       = array(
			'message'       => 'POST request successful',
			'data_received' => $parameters,
		);

		return new \WP_REST_Response( $data, 201 );
	}
}
