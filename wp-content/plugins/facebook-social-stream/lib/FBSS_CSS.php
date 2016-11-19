<?php

require_once('FBSS_Logger.php');
require_once('FBSS_Registry.php');
require_once('FBSS_Template.php');


class FBSS_CSS {
	
	private static $logger;
	private static $plugin_dir_url;
	
	public static function register() {
		self::$logger = new FBSS_Logger(__CLASS__);
		self::$plugin_dir_url = FBSS_Registry::get('plugin_base_dir_url');
		
		self::$logger->log("Registered action 'add_css_library'.", __LINE__);
		
		if (get_option('fbss_setting_disable_css')) {
			self::$logger->log("CSS disabled by settings.", __LINE__);
		} else {
			add_action('wp_enqueue_scripts', array(__CLASS__, 'add_css_library'));
		}
		
		add_action('wp_head', array(__CLASS__, 'add_css_inline_data'));
	}
	
	public static function add_css_library() {
		$template = new FBSS_Template;
		$template_id = $template->getId();
		
		$plugin_version = FBSS_Registry::get('plugin_version');
		
		$style_dir = self::$plugin_dir_url.'templates/'.$template_id.'/css';
		$style_uri = $style_dir.'/style.min.css';
		
		wp_enqueue_style('wp-fb-social-stream', $style_uri, array(),
			$plugin_version, 'all');
		
		// enqueue customized style
		$css_configs = $template->getConfigurationCSS();
		$custom_css = array();
		
		foreach ($css_configs as $config) {
			$config_index = $config['config']['index'];
			
			foreach ($config['config']['configs'] as $sub_config) {
				if (isset($sub_config['actions'])) {
					if (isset($sub_config['actions']['copy_value_from'])) {
						// copy value and create css
						$copy_from_key = $template->getDBOptionsKey(
							$sub_config['actions']['copy_value_from']['index'],
							$sub_config['actions']['copy_value_from']['config_id']
						);
						
						$copy_from_val = $template->getDBOptionsValue($copy_from_key);
						
						if ($copy_from_val) {
							$prefix = $sub_config['actions']['copy_value_from']['value_prefix'];
							$suffix = $sub_config['actions']['copy_value_from']['value_suffix'];
							
							if ($sub_config['type'] == 'hexcode') {
								$css_string = sprintf('%s {%s: %s#%s%s;}',
										$sub_config['selector'], $sub_config['property'],
										$prefix, $copy_from_val, $suffix);
							} elseif ($sub_config['type'] == 'size') {
								$unit = $template->getDBOptionsValue($copy_from_key.'_u');
								$css_string = sprintf('%s {%s: %s%s%s;}',
										$sub_config['selector'], $sub_config['property'],
										$prefix, $copy_from_val.$unit, $suffix);
							} else {
								$css_string = sprintf('%s {%s: %s%s%s;}',
										$sub_config['selector'], $sub_config['property'],
										$prefix, $copy_from_val, $suffix);
							}
							
							array_push($custom_css, $css_string);
							continue;
						}
					}
				}
				
				$db_options_key = $template->getDBOptionsKey($config_index,
						$sub_config['config_id']);
				$css_val = $template->getDBOptionsValue($db_options_key);
				
				if ($css_val) {
					if ($sub_config['type'] == 'hexcode') {
						$css_string = sprintf('%s {%s: #%s;}',
								$sub_config['selector'], $sub_config['property'],
								$css_val);
					} elseif ($sub_config['type'] == 'size') {
						$unit = $template->getDBOptionsValue($db_options_key.'_u');
						$css_string = sprintf('%s {%s: %s;}',
								$sub_config['selector'], $sub_config['property'],
								$css_val.$unit);
					} else {
						$css_string = sprintf('%s {%s: %s;}',
								$sub_config['selector'], $sub_config['property'],
								$css_val);
					}
					
					array_push($custom_css, $css_string);
				}
			}
		}
		
		if (count($custom_css)) {
			wp_add_inline_style('wp-fb-social-stream', implode(' ', $custom_css));
		}
	}
	
	public static function add_css_inline_data() {
		$css_data = get_option('fbss_setting_cust_inline_css');
		if ($css_data) {
			self::$logger->log("Add custom inline CSS.", __LINE__);
			
			# I hate this method but wp_add_inline_style() needs a stylesheet :(
			echo '<style type="text/css">'."\n".$css_data."\n".'</style>';
		}
	}
}
