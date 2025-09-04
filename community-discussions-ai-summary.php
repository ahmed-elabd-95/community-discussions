<?php
/**
 * Plugin Name: Community Discussions – AI Summary (Mock)
 * Description: CPT with AI Summary (mock)
 * Version: 1.0.0
 * Author: Ahmed Mohamed Elabd
 */

if (!defined('ABSPATH')) exit;

define('CDAI_VER', '1.0.0');
define('CDAI_FILE',__FILE__);
define('CDAI_DIR',plugin_dir_path(__FILE__));
define('CDAI_URL',plugin_dir_url(__FILE__));

require_once CDAI_DIR.'includes/class-cdai-admin.php';
require_once CDAI_DIR.'includes/class-cdai-metabox.php';

class CDAI_Plugin {
    public function __construct() {
        add_action('init',[$this,'register_cpt']);
        add_filter('the_content',[$this,'render_summary']);
        (new CDAI_Admin())->init();
        (new CDAI_Metabox())->init();
    }

    public function register_cpt() {
        $labels=[
            'name'=>'Community Discussions',
            'singular_name'=>'Community Discussion',
            'menu_name'=>'Community Discussions',
            'add_new'=>'Add New',
            'add_new_item'=>'Add New Discussion',
            'edit_item'=>'Edit Discussion',
            'view_item'=>'View Discussion',
            'all_items'=>'All Discussions',
            'search_items'=>'Search Discussions',
            'not_found'=>'No discussions found',
            'not_found_in_trash'=>'No discussions found in Trash'
        ];
        $args=[
            'labels'=>$labels,
            'public'=>true,
            'show_in_rest'=>true,
            'menu_icon'=>'dashicons-format-chat',
            'supports'=>['title','editor','author','thumbnail','revisions'],
            'has_archive'=>true
        ];
        register_post_type('community_discussion',$args);
    }

 public function render_summary($content) {
    if (!is_singular('community_discussion')) return $content;
    $summary = get_post_meta(get_the_ID(), '_cd_ai_summary', true);
    if (!$summary) return $content;
    $html  = '<div class="cd-ai-summary-box" style="border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:30px;background:#f9f9f9;box-shadow:0 2px 6px rgba(0,0,0,0.05);">';
    $html .= '<h2 style="margin-top:0;font-size:20px;color:#333;border-bottom:1px solid #e5e5e5;padding-bottom:8px;">AI Summary</h2>';
    $html .= '<p style="font-size:16px;line-height:1.6;color:#555;">'.esc_html($summary).'</p>';
    $html .= '</div>';
    return $html.$content;
}
}

function cdai_activate() {
    if (!get_option('cd_ai_settings')) {
        add_option('cd_ai_settings',['summary_length'=>60]);
    }
    (new CDAI_Plugin())->register_cpt();
    flush_rewrite_rules();
}
register_activation_hook(CDAI_FILE,'cdai_activate');

function cdai_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(CDAI_FILE,'cdai_deactivate');

new CDAI_Plugin();
