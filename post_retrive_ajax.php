<?php 
	include "../../../wp-load.php";
	global $wpdb;
	global $table_prefix;
	require_once(ABSPATH .'wp-includes/pluggable.php');
	$nonce=$_GET['_ajax_nonce'];
	if (! wp_verify_nonce($nonce, 'a-to-z-category-listing-ajax') ) die("Security check");
	
	?>
	 
	<form name="form1" id="form1" method="post"> 
	<input name="post_java_id" id="post_java_id" type="hidden"  />
	</form>
		<?php
		/*
		$csql_rec = mysql_query("select * from ".$table_prefix."terms where name='Conditions'");
		$csql_res = mysql_fetch_assoc($csql_rec);
		$init_letter = $_GET['R'];
		$sql = "select * from ".$table_prefix."terms wpt,".$table_prefix."term_taxonomy wptt where wpt.name like '$init_letter%' and wptt.parent = '$csql_res[term_id]'  and wptt.taxonomy = 'category' and wpt.term_id = wptt.term_id";
		$sql_rec = mysql_query($sql);
		if(mysql_num_rows($sql_rec) > 0)
		{
		
		
		*/
		global $wpdb;
		$cat_id_qry = "SELECT cat_id from ".$wpdb->prefix."category_ids";
		$rec = mysql_query($cat_id_qry);
		$list = array();
		while($res = mysql_fetch_assoc($rec))
		{
			$list[] = $res['cat_id'];
		}
		//print_r($list);
		$lists = implode(",",$list);
		if(count($list)>0)
		{
		$csql_rec = $wpdb->get_results("select * from ".$table_prefix."terms where name='Conditions'");
		$init_letter = $_GET['R'];
		$sql_rec = $wpdb->get_results("select * from ".$table_prefix."terms wpt,".$table_prefix."term_taxonomy wptt where wpt.name like '".$init_letter."%'   and wptt.taxonomy = 'category' and wpt.term_id = wptt.term_id and wpt.term_id not in(".$lists.")");
		}
		else
		{
			$csql_rec = $wpdb->get_results("select * from ".$table_prefix."terms where name='Conditions'");
		$init_letter = $_GET['R'];
		$sql_rec = $wpdb->get_results("select * from ".$table_prefix."terms wpt,".$table_prefix."term_taxonomy wptt where wpt.name like '".$init_letter."%'   and wptt.taxonomy = 'category' and wpt.term_id = wptt.term_id");
		}
		//echo "select * from ".$table_prefix."terms wpt,".$table_prefix."term_taxonomy wptt where wpt.name like '".$init_letter."%'   and wptt.taxonomy = 'category' and wpt.term_id = wptt.term_id and wpt.term_id not in(".$lists.")";
		//$sql_rec = $wpdb->get_results($sql);
		if(count($sql_rec) > 0)
		{			
			foreach($sql_rec as $c_rec){
				$ttid = $c_rec->term_taxonomy_id;
				$tid = $c_rec->term_id;
				$name = $c_rec->name;
				$description = $c_rec->description;
				$cat_link = $c_rec->term_id;
				?>
				
                <?php
                if(get_option('atoz_listing_style') == 'tabular')
				{
				?>
                <div class="categorylink">
					<h4>
						<?php
						#tag = get_terms('category',array('parent'=>60, 'hide_empty' => false ));
						echo  "<a href='".get_category_link($cat_link)."' class='alinka'>".$name."</a>";
						?>
					</h4>
					<p class="description">
						<?php
						echo $description;
						?>
					</p>
					<div class="clear"></div>
					<?php
						query_posts('cat='.$tid.'&showposts=7');
						while (have_posts()) : the_post();
							//echo $post->ID.' hjhjjhj';
						endwhile; 
					
					
					?>
					<ul class="postlist">
					<?php
										
						/*$psql = "select * from ".$table_prefix."posts wpp,".$table_prefix."term_relationships wpr where wpr.term_taxonomy_id = '$ttid' and wpr.object_id = wpp.ID and wpp.post_status = 'publish' and wpp.post_type = 'post'";*/
						#echo $psql;
						$psql_rec = $wpdb->get_results("select * from ".$table_prefix."posts wpp,".$table_prefix."term_relationships wpr where wpr.term_taxonomy_id = '$ttid' and wpr.object_id = wpp.ID and wpp.post_status = 'publish' and wpp.post_type = 'post'");
						$i=1;
						$pcontent='';
						foreach($psql_rec as $psql_res){
							$aid = $psql_res->ID;
							$post_heading = $psql_res->post_title;
							
							?>
							<li><a id="<?php echo $aid;?>" class="showpost" onclick="show_post_content('<?php echo $aid;?>')" href="#<?php echo $aid.$tid;?>" title="show"><span><?php echo $post_heading?></span></a></li>
							
							<?php
								$pcontent .= '<div id="cont'.$aid.'" class="postcontentt" style="display:none"><div class="post_title_link"><h4><a href="'.get_permalink($psql_res->ID).'" class="post_title_link">'.$psql_res->post_title.'</a></h4></div>
											 <div style="float:left; padding-right:5px; margin-right:10px">
												
												</div>'.short_read('100',$psql_res->post_content,get_permalink($psql_res->ID)).'</div>';
								$line_div .=  '<div id="sline'.$aid.'" class="line" style="display:none">&nbsp;</div>';
						$i++;
						?>
						<?php
						}
						?>		
						<?php echo $line_div;?>
						<?php echo $pcontent;?>	
					</ul> 
                    </div>
                    
                    
                   
                    <?php
                    }
					if(get_option('atoz_listing_style') == 'listing')
					{
					?>
                     <div class="categorylist">
                    <div id="cat_detail">
                        <h4>
                            <?php
                            #tag = get_terms('category',array('parent'=>60, 'hide_empty' => false ));
                            echo  "<a href='".get_category_link($cat_link)."' class='alinka'>".$name."</a>";
                            ?>
                        </h4>
                        <div class="description1">
                            <?php
                            echo $description;
                            ?>
                        </div>
                       </div>
					
					<?php
						query_posts('cat='.$tid.'&showposts=7');
						while (have_posts()) : the_post();
							//echo $post->ID.' hjhjjhj';
						endwhile; 
					
					
					?>
                    <div id="all_links">
                        <ul class="postlist1">
                        <?php
                            /*$psql = "select ID,post_title from ".$table_prefix."posts wpp,".$table_prefix."term_relationships wpr where wpr.term_taxonomy_id = '$ttid' and wpr.object_id = wpp.ID and wpp.post_status = 'publish' and wpp.post_type = 'post'";*/
                            #echo $psql;
                            $psql_rec = $wpdb->get_results("select ID,post_title from ".$table_prefix."posts wpp,".$table_prefix."term_relationships wpr where wpr.term_taxonomy_id = '$ttid' and wpr.object_id = wpp.ID and wpp.post_status = 'publish' and wpp.post_type = 'post'");
                            $i=1;
                            $pcontent='';
                            foreach($psql_rec as $psql_res){
                                $aid = $psql_res->ID;
                                $post_heading = $psql_res->post_title;
                                
                                ?>
                                <li><a id="<?php echo $aid;?>" class="showpost1"  href="<?php echo get_permalink($psql_res->ID);?>" title="show"><span><?php echo $post_heading?></span></a></li>
                                
                                <?php
                            $i++;
                            ?>
                            <?php
                            }
                            ?>		
                        </ul> 
                      </div>
                      <div class="clear"></div>
                    <?php
                    }
					?>
				</div>
			<?php
			
			
			}
			
		}
		else
		{
		?>
		<div class="categorylink">
			<h4 class="alinka">
				<?php echo "Ooops !! No Categories Found.";?>
			</h4>
		</div>
		<?php
		}
?>