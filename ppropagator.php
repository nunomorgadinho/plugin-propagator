<?php
# /* 
#     Plugin Name: Plugin Propagator 
#     Plugin URI: http://www.morgadinho.org/
#     Description: Plugin which propagates another plugin installation into several wp-mu installations. 
#     Author: Nuno Morgadinho 
#     Version: 1.0 
#     Author URI: http://www.morgadinho.org/ 
#     */  

global $old_active_plugins;


//required for call to wp_get_current_user
require_once(ABSPATH. WPINC ."/pluggable.php");
require_once(ABSPATH. WPINC ."/capabilities.php");
////needed for deactivate_sidewide_plugin
require_once ( ABSPATH . '/wp-admin/includes/mu.php' );
//needed for activate/deactive_plugin
require_once ( ABSPATH . '/wp-admin/includes/plugin.php' );
//need the file with the Configuration details
require_once(ABSPATH . '/wp-content/plugins/blitztools-plugin/blitztools_config.php');

define(ROOT,'http://blitzchiroblogs.com');

$temp_active_plugins = (array)get_option('active_plugins');

//sets a variable to store the previous value for the active plugins.
add_option('temp_active_plugins',$temp_active_plugins);


/**
 * Given a source and a destination this function can compress any
 * file or directory recursively
 * @param $source
 * @param $destination
 */
function Zip($source, $destination)
{
    if (extension_loaded('zip') === true)
    {
        if (file_exists($source) === true)
        {
                $zip = new ZipArchive();

                if ($zip->open($destination, ZIPARCHIVE::CREATE) === true)
                {
                        $source = realpath($source);

                        if (is_dir($source) === true)
                        {
                                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                                foreach ($files as $file)
                                {
                                        $file = realpath($file);

                                        if (is_dir($file) === true)
                                        {
                                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                                        }

                                        else if (is_file($file) === true)
                                        {
                                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                                        }
                                }
                        }

                        else if (is_file($source) === true)
                        {
                                $zip->addFromString(basename($source), file_get_contents($source));
                        }
                }

                return $zip->close();
        }
    }

    return false;
}

/**
 * Given a zip_file with the complete path this function
 * performs the unzip in the same directory as the name of the file.
 * For instance if zip_file is path/to/file.zip the content will be 
 * available in path/to/file
 * @param unknown_type $zip_file
 */
function unZip($zip_file)
{
	$zip = new ZipArchive();
	$zip->open($zip_file);
	
	$destination = 	substr($zip_file,0,strpos($zip_file,".zip"));

	$zip->extractTo($destination);
	
	$zip->close();
	
	unlink($zip_file);
}

/**
 * This function propagates the action of activate/deactivate a given plugin
 * or set of a plugins from one wp installation to another wordpress installations
 * @param unknown_type $new_value
 */
function propagate($new_value)
{
	global $ADMIN_USER, $ADMIN_PASSWD, $CONFIG;
	
	$LOCALHOST = get_option('siteurl');
	
	if($LOCALHOST !== ROOT)
	{
		return $new_value;
	}
	$old_value = (array) get_option('temp_active_plugins');
	
	$n_new = count($new_value);
	$n_old = count($old_value);
	$diff_value=array();
	$plugin_activated = false;
	if($n_new > $n_old)
	{
		$diff_value = array_diff((array)$new_value,$old_value);
		$plugin_activated = true;
	}
	else if($n_new < $n_old)
	{
		$diff_value = array_diff($old_value,(array)$new_value);
	}


	foreach ($diff_value as $current_plugin)
	{
		foreach ($CONFIG as $host => $hostsettings)
		{
			$curl_url = "http://".$host."/index.php?username=".$ADMIN_USER."&password=".$ADMIN_PASSWD;
			
			
			//contains the dir name of the plugin activated/deactivate
			$plugin_file = plugin_basename(trim($current_plugin));
			$dirname = dirname($plugin_file);
			

			//plugins can be distributed as a single file or as a directory
			if($dirname == '.')
			{
				$plugin_dir = WP_PLUGIN_DIR."/".$plugin_file;		
				$plugin_name = 	substr($plugin_file,0,strpos($plugin_file,".php"));	
				$zip_file = WP_PLUGIN_DIR."/".$plugin_name.".zip";
				$plugin_file = $plugin_name.'/'.$plugin_file;  
				//this is needed to construct well the zip and the url
			}
			else		
			{
				$plugin_dir = WP_PLUGIN_DIR."/".$dirname;
				$zip_file = WP_PLUGIN_DIR."/".$dirname.".zip";
			}
					
			//creates the zip archive and if success proceed with the request
			if($plugin_activated && Zip($plugin_dir,$zip_file))
			{	
				//I am activating the current plugin
				
				$ch = curl_init();
				$curl_url = $curl_url."&plugin_action=activated&plugin_mainfile=".$plugin_file;
		
				echo "url ".$curl_url."<br/>";
				
				curl_setopt($ch, CURLOPT_URL, $curl_url);
	
				$data = array('testpost' => 'Foo', 'file' => '@'.$zip_file);
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
				//return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
				// $output contains the output string
				$output = curl_exec($ch);
			
				curl_close($ch);
		//		unlink($zip_file);
				print $output;		
			}
			else
			{
				//I am deactivating the current plugin	
				$ch = curl_init();
				$curl_url = $curl_url."&plugin_action=deactivated&plugin_mainfile=".$plugin_file;
				curl_setopt($ch, CURLOPT_URL, $curl_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);	
				print $output;
			}
		}//ends for each host
	}//ends for each plugin
	
	
	// update the temporaty storage value
	update_option('temp_active_plugins',$new_value);
	return $new_value;
}

/**
 * Given a request with a plugin activate/deactivate/delete action
 * executes that action on the required plugin
 */
function blitz_handlerequest()
{
	if($LOCALHOST == ROOT)
	{
		exit("");
	}
	if(isset($_REQUEST['plugin_action'])) 
	{
				
		$p_action = $_REQUEST['plugin_action'];
		$plugin_mainfile = $_REQUEST['plugin_mainfile'];
		
		if($p_action=='activated')
		{
			$target_path = WP_PLUGIN_DIR."/".basename( $_FILES['file']['name']); 

			if(!file_exists(WP_PLUGIN_DIR."/".$plugin_mainfile))	
			{
				$uploaded_file = $_FILES['file']['tmp_name'];
	
				if(move_uploaded_file($uploaded_file,$target_path))
				{
					//could copy the zip file into plugins dir properly.

					//unzip the plugin
					unZip($target_path);
				}
			}
			//activate the plugin
			activate_plugin($plugin_mainfile);
			activate_sitewide($plugin_mainfile);
		}
		else 
		{
			$pluginsD = array();
			$pluginsD[] = $plugin_mainfile;
			deactivate_plugins($plugin_mainfile);
		}
		die;
	}
}

/**
 * mu.php does not support activate sitewide with plugin specification.
 * @param unknown_type $plugin
 */
function activate_sitewide($plugin) {
		
	/* Add the plugin to the list of sitewide active plugins */
	$active_sitewide_plugins = maybe_unserialize( get_site_option( 'active_sitewide_plugins' ) );
	
	/* Add the activated plugin to the list */
	$active_sitewide_plugins[ $plugin ] = time();

	/* Write the updated option to the DB */
	if ( !update_site_option( 'active_sitewide_plugins', $active_sitewide_plugins ) )
		return false;

	return true;
}



add_action('init', 'blitz_handlerequest', 12);
add_filter('option_active_plugins','propagate');
?>