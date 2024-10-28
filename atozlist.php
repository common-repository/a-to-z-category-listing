<?php
/*
Plugin Name: AtoZ Category List
Plugin URI: http://wordpress.org/extend/plugins/a-to-z-category-listing/
Description: This Plugin will show A-to-Z listing of all categories in alphabetical order
Version: 1.2
Author: Anblik Web Design Company
Author URI: http://www.anblik.com/
*/
?>
<?php
function atoz_list ()
{
global $wpdb;
$table = $wpdb->prefix."category_ids";
$structure = "CREATE TABLE $table (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cat_id` INT NOT NULL
) ENGINE = MYISAM ;";
 $wpdb->query($structure);
}
add_action('activate_a-to-z-category-listing/atozlist.php', 'atoz_list');
?>
<?php
add_action('activate_atoz_listing/atozlist.php','atoz_activation');
add_action('admin_menu','atoz_list_admn_pages');
add_shortcode('new_atozlisting', 'atozlisting');

function atoz_activation()
{
	global $wpdb;
	$data = "tabular";
	add_option('atoz_listing_style',$data);
}
function atoz_list_admn_pages()
{
	add_management_page( "A To Z List", "A To Z Listing", "manage_options", "a-to-z", "atoz_manager");
}
function atoz_manager()
{
	global $wpdb;
	$main_url = get_bloginfo("url");
	if(isset($_POST['atozSubmit']))
	{
		$data = $_POST['atozstyle'];
		update_option('atoz_listing_style',$data);
		echo "<script>
				location.replace('".$main_url."/wp-admin/tools.php?page=a-to-z');
				</script>";
	}
	?>
    <div>
    	<div>
        	<h2>A to Z Listing Style</h2>
			</ul>
        </div>
        <div  style=" border:1px solid #CCC; padding:10px; background-color:#f1f1f1;"> 
       <form name="atozmanage" id="atozmanage" method="post" action="">
        <ul >
                         
        			 <li style="list-style-type:none; height:50px; ">
                     <input type="radio" name="atozstyle" id="atozstyle" value="tabular" <?php if(get_option('atoz_listing_style') == 'tabular'){echo "checked";}?> />
                     <strong>Tabular Form</strong>
                     <p>( This will list articles in a tabular manner horizontally. Clicking on the tab will open a short introduction about the article and clicking on article title will take user to the actual post page. )</p></li>
                     <br />
                     
                     <li style="list-style-type:none;  height:50px;">
                     <input type="radio" name="atozstyle" id="atozstyle" value="listing" <?php if(get_option('atoz_listing_style') == 'listing'){echo "checked";}?>/>
                     <strong>List Form</strong>
                     <p>( This will list articles in a top top-down listing manner vertically. Clicking on article title will take user to the actual post page. )</p></li>
        </ul>
		
        <input type="submit" name="atozSubmit" id="atozSubmit" value="Update" class="button-primary"/>
      </form>
      </div>
   </div>	
<div>
	<?php
	global $wpdb;
	if($_POST && $_POST['action']=='selcat'){
		$ids = $_POST['cat'];
		//$mysql_qury = "TRUNCATE table ".$wpdb->prefix."category_ids";
		$res_del = $wpdb->query($wpdb->prepare("TRUNCATE table ".$wpdb->prefix."category_ids"));
		
		if(count($ids) > 0){
				foreach($ids as $c_id)
				{
				//$qry = "INSERT into ".$wpdb->prefix."category_ids (cat_id) values($c_id);";
				$rec = $wpdb->query($wpdb->prepare("INSERT into ".$wpdb->prefix."category_ids (cat_id) values($c_id)"));
				}
			}	
		
	}		
	?>
	<h2>Exclude category</h2>
	<p>Select categories from the list which you do not want to view in the category listing.</p>
	<form name="atozmanage1" id="atozmanage1" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
	  <select name="cat[]" id="cat" multiple="multiple" style="height:200px; width:150px">
	  <!--<option value="0">..None..</option>-->
	  <?php 
	  
	//$slct_id = "SELECT cat_id from ".$wpdb->prefix."category_ids";
	//$rec = mysql_query($slct_id);
	$rec = $wpdb->query($wpdb->prepare("SELECT cat_id from ".$wpdb->prefix."category_ids"));
	$array_id = array();
	while($res = mysql_fetch_assoc($rec))
	{
		$array_id[] = $res['cat_id'];
	}  
	$cat_id = get_all_category_ids();
	foreach($cat_id as $id)
	{  
		
	 ?> 
	   <option value="<?php echo $id; ?>" <?php if(in_array($id,$array_id)){echo "Selected=\"Selected\"";}?>><?php echo get_cat_name($id);?></option>
	 <?php
	 	
	 }?>
      </select>
	  <br />
	  <input type="hidden" name="action" value="selcat" />
	  <input type="submit" name="atozSubmit1" id="atozSubmit1" value="Submit" class="button-primary" onclick="get_id();"/>
	  
	</form>

	</div>
<?php		
}
function short_read($limit,$content,$link)
{
	$pcontent = $content;
	//$link = get_permalink();	
	$readmore='read more';
  	$excerpt = explode(' ', $pcontent, $limit);
	if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $readmore = '<p><a class="readmore visit" href="'.$link.'">'.$readmore.'</a></p>';
  return $excerpt.$readmore;
}
function atozlisting()
{
	global $wpdb;
	$url = get_bloginfo("url");
	$nonce = wp_create_nonce('a-to-z-category-listing-ajax');
	
?>
	<div id="nonce_value" style="display:none;"><?php echo $nonce;?></div>
	<link href="<?php echo $url;?>/wp-content/plugins/a-to-z-category-listing/atoz.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $url;?>/wp-content/plugins/a-to-z-category-listing/script.js" type="text/javascript"></script>
	<form name="form2" id="form2" method="post"> 
	<input name="atozlist" id="atozlist" type="hidden"  />
	</form>
	
		<!-- //////////////////////////// -->
		<!-- STARTING LIST A-Z CATEGORIES -->
		<!-- //////////////////////////// -->
		<div class="container"> 
				<ul class="tabsxd"> 
				<?php
				$calp = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
					for($i=0;$i <= count($calp)-1;$i++){		
						$index_row .= '<li id="li_'.$calp[$i].'"><a href="#tab' . $calp[$i] . '" onclick="alph_list(\''.$calp[$i].'\',\''.$url.'\');">' . $calp[$i] . '</a></li>';
					}
				  print $index_row; ?>
				</ul>
				<div class="clear"></div>
				
			    <div id="areaHint">
				<div class="tab_container"> 
				</div> <!-- end tab_container-->
				</div><!-- end of areaHint-->
		</div><!-- end container-->
		<!-- ///////////////////////////// -->
		<!-- FINISHING LIST A-Z CATEGORIES -->
		<!-- ///////////////////////////// -->
<?php 
}
?>