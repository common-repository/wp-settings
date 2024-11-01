<?php
/*
Plugin Name: WP Settings
Plugin URI: https://wordpress.org/plugins/wp-settings/
Description:Displays the important information about WordPress installation such as general wordpress settings ,database and php information.You can generate DB Backup Script.
Version: 1.5
Author: CodeCompiled
Author URI: http://www.codecompiled.com
Text Domain: wp-settings
Domain Path: /languages
*/

/*ceating tabs*/
function ilc_admin_tabs( $current = '00' ) {
    $tabs = array( 'homepage' => 'Home Settings', 'databse' => 'Database Settings');
    echo '<div id="tabs" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' :'tabs' ;
        echo "<a style='background:gray;color:black;' class='nav-tab' href='?page=wp-settings&tab=$tab'>$name</a>";

    }
    echo '</h2>';
}
//ilc_admin_tabs();
/*ceating tabs*/


add_action('admin_menu', 'wpsettings_setupOptionsMenu');
 

 
 
// action function for above hook
function wpsettings_setupOptionsMenu() {
    // Add a new submenu under Settings:
    add_menu_page("WP Settings","WP Settings","install_plugins","wp-settings", 'wpsettings_DisplaySetingsPage');
}

function wpsettings_load_scripts_styles() {
    wp_enqueue_script( "wp-settings-script", plugin_dir_url( __FILE__ ) . '/js/wp-settings-script.js' );
    wp_enqueue_style('wp-settings-css', plugin_dir_url(__FILE__) . '/css/wp-settings-style.css');
}
add_action('wp_print_scripts', 'wpsettings_load_scripts_styles');

function wpsettings_getMySqlDetails() {
         global $wpdb;
         $results = $wpdb->get_results('select version() as mysqlversion');
         $sqlInfoVer;
          foreach( $results as $result ) {
            $sqlInfoVer= $result->mysqlversion;
    }
       $ws_mysqldetails=array();
       $ws_mysqldetails["VERSION"]= $sqlInfoVer;//DB_NAME;
       $ws_mysqldetails["DATABASE NAME"]=DB_NAME;
       $ws_mysqldetails["DATABASE USER NAME"]=DB_USER;
       $ws_mysqldetails["DATABASE HOST"]=DB_HOST;
       $ws_mysqldetails["DATABASE SIZE(MB)"] = $wpdb->get_var("SELECT sum( data_length + index_length ) / 1024 / 1024 'dbsize' FROM information_schema.TABLES WHERE table_schema ='".DB_NAME."'");
       $ws_mysqldetails["NO. OF TABLES"]= $wpdb->get_var("SELECT count(*) FROM INFORMATION_SCHEMA.TABLES WHERE table_schema='".DB_NAME."'");
       return $ws_mysqldetails;
    }
function wpsettings_bloginfo_array() {
    $fields = array('name', 'description', 'wpurl', 'url', 'admin_email', 'version','categories','pages','pingback_url', 'language');
    $data = array();
    foreach($fields as $field) {
    	if(strcmp($field,"categories")==0){
        	 $categoriesCsv="";
        	 $cat=get_categories();
        	 /*
        	 get categories
        	 */
        	 foreach ($cat as $key=>$value) {
        	 	$categoriesCsv.=$value->name." ,";
        	 	
        	 }
        	 $data[$field]=rtrim($categoriesCsv,",");

            
        }
        else if(strcmp($field,"pages")==0)
        {
        	 /*
        	 get pages
        	 */
        	  $pagesCsv="";
        	 $page=get_pages();
        	 foreach ($page as $key=>$value) {
        	 	$pagesCsv.=$value->post_name." ,";
        	 	
        	 }
        	 $data[$field]=rtrim($pagesCsv,",");
        }
        else
        $data[$field] = get_bloginfo($field);
        
    }
    return $data;
}

function wpsettings_theminfo_array() {
    $fields = array('Name', 'Description', 'Version');
    $data = array();
	
	
	if (!function_exists('wp_get_theme')) {
		//function not available
		 foreach($fields as $field) {
        $data[$field] = "THEME DETAILS CURRENTLY UNAVAILABLE";
    }
	} else {
		$theme = wp_get_theme();
		
	}
	
    foreach($fields as $field) {
        $data[$field] = $theme->$field;
    }
    return $data;
}

function wpsettings__getPluginsDetails()
{
   $pluginInfo = array();
  if (function_exists( 'get_plugins' ) ) {
			        /*
			        get plugins
			        */
			         $name='';
                     $desc='';
		        	 $plugins=get_plugins();
		        	 foreach ($plugins as $key=>$value) {
                          foreach ($value as $key1 => $value1) {
						         // var_dump($value1);
                          	//echo $key1.'\n';
                          	     
                          	      //echo $key1." ---> key.\n";
                          	      //echo $value1.".-->value.\n";
						          if($key1=="Name")
						          {
						          	 $name=$value1;
						          }
						          if($key1=="Description")
						          {
						          	$desc=$value1;
						          }
			      	}

		        	 	 $pluginInfo[$name]= $desc;
		        	 	
		        	 }
		        	
			    }
			    else
			    {
                     $pluginInfo[$field]='INFORMATION NOT AVAILABLE';
			    }
       return $pluginInfo;
}



 function wpsettings__getPHPDetails() {
    $phpetails=array();
    $phpetails["PHP VERSION"]= phpversion();
    $phpetails["PROTOCOL"]= array_key_exists( 'SERVER_PROTOCOL',$_SERVER) ? $_SERVER['SERVER_PROTOCOL']: "HTTP/1.1";
    $phpetails["CURRENT REQUEST METHOD"]  	 = array_key_exists('REQUEST_METHOD',$_SERVER) ? $_SERVER['REQUEST_METHOD'] : "GET";
	$phpetails["PORT"]= array_key_exists( 'SERVER_PORT',$_SERVER) ? $_SERVER['SERVER_PORT']: "80";
	$phpetails["SOFTWARE"]= array_key_exists( 'SERVER_SOFTWARE',  	$_SERVER) ? $_SERVER['SERVER_SOFTWARE'] 	 : '';
	$phpetails["HTTP ACCEPT (REQUEST HEADER)"] = array_key_exists( 'HTTP_ACCEPT',$_SERVER) ? $_SERVER['HTTP_ACCEPT']: "text/html,application/xhtml+xml,application/xml,application/json";
	$phpetails["HTTP ACCEPT LANGUAGE (REQUEST HEADER)"] = array_key_exists( 'HTTP_ACCEPT_LANGUAGE',$_SERVER) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : "en";
	$phpetails["HTTP HOST"]= array_key_exists( 'HTTP_HOST',$_SERVER) ? $_SERVER['HTTP_HOST']:'';
	$phpetails["HTTP USER AGENT (CLIENT TYPE)"] 	 = array_key_exists( 'HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT']: '';
	return $phpetails;
}



function wpsettings_getFormattedKey($key)
{
	$wpsettings_keymappings_wordpress=array();
	$wpsettings_keymappings_wordpress["name"]="SITE TITLE( Settings > General)";
	$wpsettings_keymappings_wordpress["description"]="SITE TAGLINE( Settings > General)";
	$wpsettings_keymappings_wordpress["wpurl"]="WORDPRESS ADDRESS (Settings > General)";
	$wpsettings_keymappings_wordpress["url"]="SITE ADDRESS (Settings > General)";
	$wpsettings_keymappings_wordpress["admin_email"]="ADMIN EMAIL (Settings > General)";
	$wpsettings_keymappings_wordpress["charset"]="Site charset";
	$wpsettings_keymappings_wordpress["version"]="WORDPRESS VERSION";
	$wpsettings_keymappings_wordpress["pingback_url"]="PINGBACK URL";
	$wpsettings_keymappings_wordpress["categories"]="CATEGORIES";
	$wpsettings_keymappings_wordpress["pages"]="PAGES";
	$wpsettings_keymappings_wordpress["tags"]="TAGS";
	$wpsettings_keymappings_wordpress["language"]="LANGUAGE";
	return $wpsettings_keymappings_wordpress[$key];
}


function  wpsettings_getFormattedKeyTheme($key)
{
	$wpsettings_keymappings_theme=array();
	$wpsettings_keymappings_theme["Name"]="NAME";
	$wpsettings_keymappings_theme["Description"]="DESCRIPTION";
	$wpsettings_keymappings_theme["DomainPath"]="DOMAINPATH";
	$wpsettings_keymappings_theme["Version"]="VERSION";
	$wpsettings_keymappings_theme["Tags"]= "TAGS";
	$wpsettings_keymappings_theme["ThemeURI"]="THEMEURI";
	return $wpsettings_keymappings_theme[$key];
}
//main method to retreive wordpress information	
function wpsettings_GetWPInfo() {

$tab='';
if(isset($_GET['tab']))
$tab=$_GET['tab'] ;

//echo $tab;
//check which tab to display

   echo '<table class="form-table">';
   switch ( $tab ){

      case 'databse' :
	     ?>
       
	    <?php 
		if(isset($_POST["names"]))
		{
			backup_tables();
		}
		else
		{
			getDatabaseContent(); 
		}
		?>
	   
         <?php
      break;
      case 'footer' :
         ?>
       
         <?php
      break;
 default:
          getHomePageContent(); ?>
         
       
         <?php
      break;
      case 'homepage' :
         ?>
     <?php getHomePageContent(); ?>
         <?php
      break;
   }
   echo '</table>';

}
//check which tab to display

function getDatabaseContent()
{
	?>
<form action="" method="POST">
<table>
<?php $sqldetails=wpsettings_getMySqlDetails();?>
<tr>
	<th >
	Following is the information about the database
	</th>
	
	</tr>
		<?php foreach($sqldetails as $sqlKey=>$sqlValue) { ?>
	<tr>
		<td style="width:30%">
		<?php echo $sqlKey;?>
		</td>
		<td>
		<?php echo $sqlValue;?>
		</td>
		</tr>
  <?php		
		 
	 }?>	
<tr>
<td colspan="2">
Click the download button to take backup of the database
<input type="submit" value="Download" name="submit_btn">
</td>

</tr>
</table>
<input type="hidden" name="names"  id="names">

</form>
		<?php
}


function backup_tables()
{

//$mysqli = new mysqli('localhost','codecomp_wp811','P]4Sl!l6s9','codecomp_wp811');
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
/* check connection */
try {
if ($mysqli->connect_errno) {
    printf("Sorry , unable to connect to database %s\n", $mysqli->connect_error);
    exit();
}
else
{

      $return='';
     
      $result = $mysqli->query('SHOW TABLES');
      
         
      $tableCount=0;
      $tables = array();
        echo "step no 1";
       while ($row = $result->fetch_row()) {
              $tableCount=$tableCount+1;
              	$tables[] = $row[0];
        //printf ("%s (%s)\n", $row[0], $row[1]);
    }
    
        	foreach($tables as $table)
	{
                  echo "step no 2";
		$result = $mysqli->query('SELECT * FROM '.$table);
		
		$num_fields = mysqli_num_fields($result);
		
	        $return.= 'DROP TABLE '.$table.';';
	          echo "step no 3";
		$row2 = mysqli_fetch_row(mysqli_query($mysqli ,'SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		  echo "step no 4";
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row =  mysqli_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j < $num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					//$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j < ($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		
        }
		
     ob_clean();
   header("Content-type: application/text");
   header("Content-Disposition: attachment; filename=dbBackup.sql");
echo $return;
    exit();
}
}//try ends
catch(Exception $e)
{
    echo 'Message: ' .$e->getMessage();
}

} //method ends






function getHomePageContent()
{
	$wpdetails= array();
	//declare server array
	$serverdetails=array();
	?>
	<table class="headerInfo" >
	<tr >
	<td colspan="4">
	 Following is some of the important information about this wordpress installation.
	</td>
	<td></td>
	</tr>
		<tr>
			<td>&nbsp;
			</td>
		</tr>

	<tr >
	<td>
	Wordpress <input type="checkbox" id="chkWordpress">
	</td>
	<td>
	Theme  <input type="checkbox" id="chkThemeDetails">
	</td>
	<td>
	MySQL  <input type="checkbox" id="chkMySQLDetails">
	</td>
	<td>
	Server  <input type="checkbox" id="chkPhpDetails">
	</td>
	<td>
	Plugins  <input type="checkbox" id="chkPluginDetails">
	</td>
	<td>
	Show All Details 
	 <input type="checkbox" id="chkShowHide">
	
	</td>
	</tr>
	</table>
	<?php if (!function_exists('get_bloginfo')) {
	} else{?>
		 <table id="wordpressDetails" class="phpinfo">
		<tr>
           <th colspan="2">
              Wordpress Details
           </th>
		</tr><?php
		$wpdetails=wpsettings_bloginfo_array();
		 foreach($wpdetails as $key=>$wpdetail) {
			 if(array_key_exists($key,$wpdetails))
			 {?>	
          	<tr>
				 <td  style="width:30%">
				 <?php $formattedValue=wpsettings_getFormattedKey($key);
				 echo $formattedValue;?>
				 </td>
				 <td><?php echo $wpdetail; ?>
		         </td>
		         </tr><?php } }?>
	  </table>
 <!-- THEME DETAILS STARTS-->
	<table id="themeDetails" class="phpinfo">
	<tr>
	<th colspan="2"> 
	Theme Details
	</th>
	</tr><?php 
	$wptheme=wpsettings_theminfo_array();
	 foreach($wptheme as $key=>$themeValue) {
		?>
        <tr>
		<td style="width:30%">
		<?php echo wpsettings_getFormattedKeyTheme($key);?>
		</td>
		<td>
		<?php
		if(strcmp($key,"Tags")!=0)
		 echo $themeValue;
		else if(strcmp($key,"Tags")===0)
		{
			echo implode(',', $themeValue);
		}?>		 
		</td>
		</tr>
    <?php		
		 
	 }?>
	
	</table>

 <!-- SQL DETAILS	 -->
<?php $sqldetails=wpsettings_getMySqlDetails();?>
<table id="mySQLDetails" class="phpinfo">
<tr>
	<th colspan="2">
	MySQL Details
	</th>
	
	</tr>
		<?php foreach($sqldetails as $sqlKey=>$sqlValue) { ?>
	<tr>
		<td style="width:30%">
		<?php echo $sqlKey;?>
		</td>
		<td>
		<?php echo $sqlValue;?>
		</td>
		</tr>
    <?php		
	 }?>	
</table>
<!-- SQL DETAILS ENDS-->

<!-- PHP DETAILS -->
<?php $phpdetails=wpsettings__getPHPDetails(); ?>
  <table id="PhpDetails" class="phpinfo">
<tr>
<th colspan="2">
Server Details
</th>
</tr>
<?php foreach($phpdetails as $sqlKey=>$sqlValue) { ?>
	<tr>
		<td style="width:30%">
		<?php echo $sqlKey;?>
		</td>
		<td>
		<?php echo $sqlValue;?>
		</td>
		</tr>
    <?php		
		 
	 } ?>	
</table>
	<!-- PLUGIN DETAILS -->
<?php $plugindetails=wpsettings__getPluginsDetails(); ?>
  <table id="PluginDetails" class="phpinfo">
<tr>
<th colspan="2">
Plugin Details
</th>
</tr>
<?php foreach($plugindetails as $sqlKey=>$sqlValue) { ?>
	<tr>
		<td style="width:30%">
		<?php echo $sqlKey;?>
		</td>
		<td>
		<?php echo $sqlValue;?>
		</td>
		</tr>
    <?php		
	 } ?></table>
<!-- add new section here-->
	<?php }?>
<!-- SQL DETAILS ENDS-->
 <?php } function wpsettings_DisplaySetingsPage() {
    include(plugin_dir_path( __FILE__ ).'/wp-settings-options.php');  
} ?>