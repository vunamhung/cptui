<?php

namespace vnh;

class Save_CPTUI_to_JSON {
	public $json_dir_path;
	public $cpt_json_file_path;
	public $tax_json_file_path;

	public function __construct($json_dir_path) {
		$this->json_dir_path = get_theme_file_path($json_dir_path);
		$this->cpt_json_file_path = get_theme_file_path($json_dir_path . 'cpt.json');
		$this->tax_json_file_path = get_theme_file_path($json_dir_path . 'ctax.json');
	}

	public function boot() {
		add_action('cptui_after_update_post_type', [$this, 'save_post_types_to_local_json_file']);
		add_action('cptui_after_update_taxonomy', [$this, 'save_taxonomies_to_local_json_file']);
	}

	public function save_post_types_to_local_json_file($data) {
		// Create our directory if it doesn't exist.
		if (!file_exists($this->json_dir_path)) {
			fs()->mkdir($this->json_dir_path);
		}

		if (!array_key_exists('cpt_custom_post_type', $data)) {
			return;
		}

		// Export all post types.
		$all_post_types = get_option('cptui_post_types', []);
		fs()->put_contents($this->cpt_json_file_path, wp_json_encode($all_post_types));
	}

	public function save_taxonomies_to_local_json_file($data) {
		// Create our directory if it doesn't exist.
		if (!file_exists($this->json_dir_path)) {
			fs()->mkdir($this->json_dir_path);
		}

		if (!array_key_exists('cpt_custom_tax', $data)) {
			return;
		}

		// Export all taxonomies.
		$all_taxonomies = get_option('cptui_taxonomies', []);
		fs()->put_contents($this->tax_json_file_path, wp_json_encode($all_taxonomies));
	}
}
