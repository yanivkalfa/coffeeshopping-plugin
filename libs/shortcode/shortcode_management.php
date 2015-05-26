<?php
class Shortcode_management{
	public $section;
	
	public function __construct(){
		if ( is_user_logged_in() ){
			//$this->section = DashboardSketch::get_all_sections();
			if (current_user_can( 'manage_options' ) )  {
				add_shortcode('dashboard', array($this, 'dashboard_display'));
			}else{
				add_shortcode('dashboard', array($this, 'dashboardDenied'));
			}
		}else{
			add_shortcode('dashboard', array($this, 'accessDenied'));
		}
  	}
	
	/*
	* @ iterate through sections array and use dashboard class to create dashboard content
	* @ Accept: nothing.
	* @ Returns: nothing.
	*/
	public function dashboard_display(){
		global $podioResponses;
		$Dashboard = new Dashboard();
		$tempSection = '';
		foreach($this->section as $section_id => $section){
			if($section['section_status'] == 'active'){
				$tempSection = new DashboardSection($section);
				$Dashboard->addSection($tempSection);
			}
		}
		
		$sectionHTML = $Dashboard->get();
		

		foreach($sectionHTML as $section){
			echo $section;
			echo '<br>';
		}
		unset($podioResponses);
	}
	
	public function accessDenied(){
		echo 'You do not have sufficient permissions to access this page. <strong>login at <a href="/wp-admin/">Login</a></strong>';
	}
	
	public function dashboardDenied(){
		echo 'You do not have sufficient permissions to view this dashboard. <strong>login at <a href="/wp-admin/">Login</a></strong>';
	}
}
//$WP_ShortCode = new shortcode_management();

/* ------------------- DEAD OR UNUSED CODE ---------------- */
/*

*/
/* ------------------- DEAD OR UNUSED CODE ---------------- */

