<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;
delete_option('cd_ai_settings');
if (function_exists('delete_post_meta_by_key')) delete_post_meta_by_key('_cd_ai_summary');
