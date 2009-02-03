<?php
/*
================================================================
	RI GNewsify
	for EllisLab ExpressionEngine - by  Ryan Irelan
----------------------------------------------------------------
	Copyright (c) 2009 Airbag Industries, LLC
================================================================
	THIS IS COPYRIGHTED SOFTWARE. PLEASE
	READ THE LICENSE AGREEMENT.
----------------------------------------------------------------
	This software is based upon and derived from
	EllisLab ExpressionEngine software protected under
	copyright dated 2005 - 2009. Please see
	http://expressionengine.com/docs/license.html
----------------------------------------------------------------
	USE THIS SOFTWARE AT YOUR OWN RISK. WE ASSUME
	NO WARRANTY OR LIABILITY FOR THIS SOFTWARE AS DETAILED
	IN THE LICENSE AGREEMENT.
================================================================
	File:			ext.gnewsify.php
----------------------------------------------------------------
	Version:		1.0.0
----------------------------------------------------------------
	Purpose:		Add 3 digits to the end of the url_title to make it compatible with Google News
----------------------------------------------------------------
	Compatibility:	EE 1.6.7
----------------------------------------------------------------
	Created:		2009-02-02
================================================================
*/

// -----------------------------------------
//	Begin class
// -----------------------------------------

class Ri_gnewsify
{
    var $settings        = array();
    
    var $name            = 'GNewsify';
    var $version         = '1.0.0';
    var $description     = 'Add 3 digits to the end of the url_title to make it compatible with Google News';
    var $settings_exist  = 'y';
    var $docs_url        = 'http://www.eeinsider.com';
    
    // -------------------------------
    // Constructor
    // -------------------------------
    
    function Ri_gnewsify ( $settings='' )
    {
        $this->settings = $settings;
    }
    // END

	// --------------------------------
	//  Activate Extension
	// --------------------------------

	function activate_extension()
	{
	    global $DB;

	    $DB->query($DB->insert_string('exp_extensions',
	                                  array(
	                                        'extension_id' => '',
	                                        'class'        => get_class($this),
	                                        'method'       => "alter_url_title",
	                                        'hook'         => "submit_new_entry_end",
	                                        'settings'     => "",
	                                        'priority'     => 10,
	                                        'version'      => $this->version,
	                                        'enabled'      => "y"
	                                      )
	                                 )
	              );
	}
	// END
	
	function settings()
	{		
		$settings = array();

		// we'll set some default so the URLs are Google News friend out of the box
		$settings['lowest_value'] = '111';
		$settings['highest_value'] = '999';
		
		return $settings;
	}
	
	// --------------------------------
	//  Custom method
	// --------------------------------

	function alter_url_title($entry_id, $data)
	{
		global $DB;
		
		// get the settings
		
		
		// generate random number and append to url_title
		$random_number = mt_rand($this->settings['lowest_value'],$this->settings['highest_value']);
		$data = array('url_title' => $data['url_title'] . '-' . $random_number);

		// UPDATE database row
    $sql = $DB->update_string('exp_weblog_titles', $data, "entry_id='" . $entry_id . "'");
    $DB->query($sql);
    
	}
	// END
	
	// --------------------------------
	//  Update Extension
	// --------------------------------  

	function update_extension ( $current='' )
	{
	    global $DB;

	    if ($current == '' OR $current == $this->version)
	    {
	        return FALSE;
	    }

	    if ($current < '1.0.1')
	    {
	        // Update to next version
	    }

	    $DB->query("UPDATE exp_extensions 
	                SET version = '".$DB->escape_str($this->version)."' 
	                WHERE class = '".get_class($this)."'");
	}
	// END
	
	// --------------------------------
	//  Disable Extension
	// --------------------------------

	function disable_extension()
	{
	    global $DB;

	    $DB->query("DELETE FROM exp_extensions WHERE class = '".get_class($this)."'");
	}
	// END
}
// END CLASS


?>