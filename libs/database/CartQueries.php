<?php
/*
* @ DashboardSketch abstract class that handles anything that relates to dahsboard backend representation.
*/

class DashboardSketch
{
	public $sections = array();


    /*
     * @ Getting all dashboard by selecting all dashboard ids and calling get_dashboard() [single]
     * @ Accept: Nothing.
     * @ Returns: (Array) - All dashboard's details.
     * */
    public static function get_all_dashboards()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . DB_TABLE_NAME_DASHBOARDS;
        $results = $wpdb->get_results("SELECT `ID` FROM $table_name ORDER BY `ID` ASC", ARRAY_A);
        $toReturn = false;
        if($wpdb->num_rows)
        {
           foreach($results as $aDashboard)
           {
               $dashboard = self::get_dashboard($aDashboard['ID']);
               $toReturn[$dashboard['details']['ID']] = $dashboard;
           }
        }
        return $toReturn;
    }

    /*
     * @ Getting all Dashboards.
     * @ Accept: nothing
     * @ Returns: (Array) - dashboards details with it's children.
     * */
    public static function get_all_dashboard_details()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . DB_TABLE_NAME_DASHBOARDS;
        return $wpdb->get_results("SELECT * FROM $table_name ORDER BY `ID` ASC", ARRAY_A);
    }

    /*
     * @ Getting all memory modules(sections or contents that has no parent
     * @ Accept: Nothing.
     * @ Returns: (Array) - all modules..
     * */
    public static function get_all_memoryModules()
    {
        global $wpdb;
        $allMemory = array();
        $table_name = $wpdb->prefix . DB_TABLE_NAME_SECTIONS;
        if($results = $wpdb->get_results("SELECT * FROM $table_name WHERE `dashboard_id` = '0' ORDER BY `view_order` ASC", ARRAY_A))
        {
            foreach($results as $section)
            {
                $allMemory['sections'][$section['ID']] = self::get_section($section['ID']);
            }
        }

        $table_name = $wpdb->prefix . DB_TABLE_NAME_CONTENTS;
        if($results = $wpdb->get_results("SELECT * FROM $table_name WHERE `section_id` = '0'  ORDER BY `view_order` ASC", ARRAY_A))
        {
            foreach($results as $content)
            {
                $allMemory['contents'][$content['ID']] = self::get_content($content['ID']);
            }
        }

        return $allMemory;
    }

    /*
     * @ Get a single dashboard with all its details - sections - contents..
     * @ Accepts: (Int) - Dashboard id.
     * @ Returns: (Array) - Single Dashboard and all Its Details.
     * */
    public static function get_dashboard($ID)
    {
        if(!isset($ID) || empty($ID))
        {
            return false;
        }

        global $wpdb;
        $dashboard = false;
        /* getting single dashboard */
        $table_name = $wpdb->prefix . DB_TABLE_NAME_DASHBOARDS;
        if($results_dashboard = $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = $ID", ARRAY_A))
        {
            $dashboard = array
            (
                'details' => $results_dashboard,
                'sections' => array()
            );

            /* getting sections and iterating though them */
            $table_name = $wpdb->prefix . DB_TABLE_NAME_SECTIONS;
            if($results_sections = $wpdb->get_results("SELECT * FROM $table_name WHERE `dashboard_id` = $ID ORDER BY `view_order` ASC", ARRAY_A))
            {
                foreach($results_sections as $singleSection)
                {
                    $dashboard['sections'][$singleSection['ID']] = self::get_section($singleSection['ID']);
                }
            }
        }
        return $dashboard;
    }

    /*
     * @ Getting a section by id.
     * @ Accept: (Int) Section id.
     * @ Returns: (Array) - Section details with it's children.
     * */
    public static function get_section($ID)
    {
        if(!isset($ID) || empty($ID))
        {
            return false;
        }

        global $wpdb;
        $section = false;
        $table_name = $wpdb->prefix . DB_TABLE_NAME_SECTIONS;
        if($result_section = $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = $ID", ARRAY_A))
        {
            $section = array
            (
                'details' => $result_section,
                'contents' => array()
            );

            $table_name = $wpdb->prefix . DB_TABLE_NAME_CONTENTS;
            if($result_contents = $wpdb->get_results("SELECT * FROM $table_name WHERE `section_id` = $ID ORDER BY `view_order` ASC", ARRAY_A))
            {
                foreach($result_contents as $content)
                {
                    $section['contents'][$content['ID']] = self::get_content($content['ID']);
                }
            }
        }

        return $section;
    }

    /*
     * @ Getting a content by id.
     * @ Accept: (Int) Content id.
     * @ Returns: (Array) - Content details with it's children.
     * */
    public static function get_content($ID)
    {
        if(!isset($ID) || empty($ID))
        {
            return false;
        }

        global $wpdb;
        $content = false;
        $table_name = $wpdb->prefix . DB_TABLE_NAME_CONTENTS;
        if($results_content = $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = $ID", ARRAY_A))
        {
            $results_content['resource'] = unserialize($results_content['resource']);
            $results_content['attributes'] = unserialize($results_content['attributes']);
            $results_content['fields'] = unserialize($results_content['fields']);
            $results_content['t_schema'] = unserialize($results_content['t_schema']);
            $content['details'] = $results_content;
        }
        return $content;
    }

    /*
     * @ Getting all alarm contents.
     * @ Accept: nothing
     * @ Returns: (Array) - Content details with it's children.
     * */
    public static function get_all_alarm_contents()
    {
        global $wpdb;
        $content = false;
        $table_name = $wpdb->prefix . DB_TABLE_NAME_CONTENTS;
        if($alarms = $wpdb->get_results("SELECT * FROM $table_name WHERE `type` = 'alarm' AND `section_id` <> '0'", ARRAY_A))
        {
            foreach($alarms as $key => $alarm)
            {
                $alarms[$key]['resource'] = unserialize($alarms[$key]['resource']);
                $alarms[$key]['attributes'] = unserialize($alarms[$key]['attributes']);
                $alarms[$key]['fields'] = unserialize($alarms[$key]['fields']);
                $alarms[$key]['t_schema'] = unserialize($alarms[$key]['t_schema']);

            }
            $content = $alarms;
        }
        return $content;
    }

    /*
     * @ Add module to DB(dashboard,section or content) according to $details and $actOn value.
     * @ Accepts: (Array) - Module Details, (String) - Module name.
     * @ Returns: Either (int) - Id of the inserted module on success or false.
     * */
    public static function add_module($details = array(), $actOn)
    {
        $toReturn = false;
        if(!empty($details))
        {
            global $wpdb;
            $table_name = self::table_name($actOn);
            if($results = $wpdb->insert( $table_name,$details))
            {
                $toReturn =  $wpdb->insert_id;
            }
        }
        return $toReturn;
    }

    /*
     * @ Update a module(dashboard,section or content) according to $details and $actOn value.
     * @ Accepts: (Array) - Module Details, (String) - Module name.
     * @ Returns: Either (int) - Id of the inserted module on success or false.
     * */
    public static function update_module($details = array(), $actOn)
    {
        if(!empty($details))
        {
            global $wpdb;
            $table_name = self::table_name($actOn);
            return $wpdb->update($table_name,$details,array( 'ID' => $details['ID'] ));
        }
        return false;
    }

    /*
     * @ Delete a module(dashboard,section or content) according to $id and $actOn value.
     * @ Accepts: (Int) - Module ID, (String) - Module name.
     * @ Returns: Either (int) - Id of the inserted module on success or false.
     * */
    public static function delete_module($details  = array())
    {
        global $wpdb;
        $id = $details['details']['ID'];

        if(isset($details['sections']))
        {
            $i_m = 'dashboard';
            $my_child = 'sections';
        }
        else if(isset($details['contents']))
        {
            $i_m = 'section';
            $my_child = 'contents';
        }
        else
        {
            $i_m = 'content';
            $my_child = 'noChildren';
        }

        foreach($details[$my_child] as $children)
        {
            self::delete_module($children);
        }

        $table_name = self::table_name($i_m);
        $wpdb->delete( $table_name, array( 'ID' => $id ) );
        switch($i_m)
        {
            case "dashboard":
                $table_name = $wpdb->prefix . DB_TABLE_NAME_CALENDAR;
                $wpdb->delete( $table_name, array( 'd_id' => $id ) );
                break;
            case "content":
                $table_name = $wpdb->prefix . DB_TABLE_NAME_CHAT;
                $wpdb->delete( $table_name, array( 'content_id' => $id ) );
                break;
        }
        return true;
    }

	/*
	* @ change item order
	* @ Accept: (array) - orderChange, (string) module to act on
	* @ Returns: returns true or false.
	*/
	public static function change_order($orderChange)
    {
		global $wpdb;
		foreach($orderChange as $change)
        {
            $table_name = self::table_name($change['actOn']);
            if(!empty($change))
            {
			    $wpdb->update($table_name, array( 'view_order' => $change['change']['order']), array( 'ID' => $change['change']['id'] ));
            }
		}
        return true;
	}

    /*
	* @ Changes parent
	* @ Accept: (int)- module id, (Int) parent id, (string) module to act on.
	* @ Returns: returns true or false.
	*/
    public static function change_parent($parentChange)
    {
        global $wpdb;
        $table_name = self::table_name($parentChange['actOn']);
        $parent_name = self::parent_name($parentChange['actOn']);
        $wpdb->update($table_name, array($parent_name => $parentChange['parent']), array( 'ID' => $parentChange['id'] ));
        return true;
    }

    /*
     * @ Retrieve correct table name according to module name
     * @ Accepts: (String) - Module name.
     * @ Returns: (String) - Table name.
     * */
    private static function table_name($actOn)
    {
        global $wpdb;
        $table_name = '';
        switch($actOn)
        {
            case 'dashboard':
                $table_name = $wpdb->prefix . DB_TABLE_NAME_DASHBOARDS;
                break;
            case 'section':
                $table_name = $wpdb->prefix . DB_TABLE_NAME_SECTIONS;
                break;
            case 'content':
                $table_name = $wpdb->prefix . DB_TABLE_NAME_CONTENTS;
                break;
        }
        return $table_name;
    }

    private static function parent_name($actOn)
    {
        switch($actOn)
        {
            case 'section':
                return  'dashboard_id';
                break;
            case 'content':
                return  'section_id';
                break;
        }
        return false;
    }
}








/* ------------------- DEAD OR UNUSED CODE ---------------- */
/*
 */
/* ------------------- DEAD OR UNUSED CODE ---------------- */






