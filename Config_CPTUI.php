<?php

namespace vnh;

use vnh\contracts\Enqueueable;

class Config_CPTUI implements Enqueueable {
	public $script_file_uri;
	public $css_file_url;

	const PATH = 'vendor/vunamhung/custom-post-type-ui/';

	public function __construct() {
		$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		$this->script_file_uri = get_theme_file_uri(self::PATH . "js/cptui{$min}.js");
		$this->css_file_url = get_theme_file_uri(self::PATH . "css/cptui{$min}.css");
	}

	public function boot() {
		add_action('after_setup_theme', 'cptui_create_submenus');
		add_action('after_setup_theme', [$this, 'remove_ads']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue'], -1);
	}

	public function enqueue() {
		if (wp_doing_ajax()) {
			return;
		}

		wp_dequeue_script('cptui');
		wp_dequeue_style('cptui-css');

		wp_enqueue_script('cptui', $this->script_file_uri, ['jquery', 'postbox'], CPTUI_VERSION, true);
		wp_enqueue_style('cptui-css', $this->css_file_url, [], CPTUI_VERSION);
	}

	public function remove_ads() {
		remove_filter('cptui_ads', 'cptui_default_ads');
	}
}
