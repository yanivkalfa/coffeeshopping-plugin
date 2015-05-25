<?php

class settings{
    public $cleanCurrentURL;
    public $result;
    public $submitResult = array();

    public function __construct(){
        if(is_admin()){
            $this->cleanCurrentURL = site_url().$_SERVER["REQUEST_URI"];
            $this->page_submited();
            $this->settings();
        }
    }

    /*
    * @ function that handle $_POST settings call.
    * @ Accepts: nothing.
    * @ Returns: nothing.
    */
    public function page_submited(){

        if ( isset($_POST['bec_settings_n']) && wp_verify_nonce($_POST['bec_settings_n'],'getRightDetails') ){
            $resultSingle = $this->check_single((!empty($_POST['bec_settings'])) ? $_POST['bec_settings'] : '');
            if(!empty($resultSingle)){
                $this->result = true;
            }
        }

        if ( isset($_POST['bec_api_settings']) && wp_verify_nonce($_POST['bec_api_settings'],'getRightDetails') ){
            update_option('API_DETAILS', $_POST['bec_api']);
        }

        if ( isset($_POST['bec_tool_tips']) && wp_verify_nonce($_POST['bec_tool_tips'],'getToolTips') ){
            update_option('TOOL_TIPS_DEF', $_POST['bec_tool_tip']);
        }

        if ( isset($_POST['bec_tool_tip_admin']) && wp_verify_nonce($_POST['bec_tool_tip_admin'],'getToolTipsAdmin') ){
            update_site_option('TOOL_TIPS_DEF', $_POST['bec_tool_tip']);
        }

        if ( isset($_POST['bec_user_permission']) && wp_verify_nonce($_POST['bec_user_permission'],'saveUserPermission') )
        {
            global $Utils;
            $users = get_users(array('role' => 'dashboard_user'));
            foreach($users as $user)
            {
                $Utils->resetUserCaps($user);
            }

            if(isset($_POST['permission']) &&!empty($_POST['permission']))
            {
                foreach($_POST['permission'] as $user_id => $caps)
                {
                    $user = new WP_User( $user_id );
                    foreach($caps as $cap)
                    {
                        $user->add_cap($cap);
                    }
                }
            }
        }

        if ( isset($_POST['bec_add_user']) && wp_verify_nonce($_POST['bec_add_user'],'addnew_user') ) {
            $role = 'dashboard_user';
            $userData = array(
                'user_login'  =>  $_POST['bec_new_user']['BEC_USER_NAME'],
                'user_pass'   =>  $_POST['bec_new_user']['BEC_PASSWORD'],
                'user_email' => $_POST['bec_new_user']['BEC_EMAIL'],
                'role' => $role
            );

            $user = wp_insert_user( $userData ) ;

            if(isset($user->errors))
            {
                $errorsString = array();
                foreach($user->errors as $error){
                    $errorsString[] = $error[0];

                }
                $this->submitResult['new_user'] = array(
                    'success' => false,
                    'msg' => implode(' / ',$errorsString)
                );

            }
            else
            {
                $this->submitResult['new_user'] = array(
                    'success' => true,
                    'msg' => 'User was added successfully'
                );

                $blog_id = get_current_blog_id();
                add_user_to_blog( $blog_id, $user, $role );
            }
        }
    }


    /*
    * @ Checks value of input array and update the site option accordingly - works for 1 D array.
    * @ Accept: global $_post fields.
    * @ Returns: rebuilt input array.
    */
    public function check_single($input){
        $mid = array();
        foreach($input as $fieldName => $value){
            if(!empty($value)){
                $mid[$fieldName] = $value;
                if(get_option($fieldName) === FALSE){
                    add_option($fieldName, $mid[$fieldName]);
                }else{
                    update_option($fieldName, $mid[$fieldName]);
                }
            }
        }
        return $mid;
    }

    /*
    * @ Output the settings page after all values were sorted.
    * @ Accept: nothing.
    * @ Returns: nothing.
    */
    public function settings(){
        ?>
        <h1>Settings:</h1>
        <div id="newTabs">
            <ul>
                <li><a href="#newTabs-1">Settings</a></li>
            </ul>
            <div id="newTabs-1">
                <h2>General Settings: </h2>
            </div>
        </div>
        <!-- ajax loader -->
        <div class="backgroundShadow"></div>
        <div class="messageWrap">
            <div class="messageHolder"></div>
        </div>
        <!--<img class="ajaxLoader" src="<?php echo IMAGES_DIR; ?>ajax-loader.gif" /> -->
    <?php
    }
}

$wpSettings = new settings();