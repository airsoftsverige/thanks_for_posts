<?php
/** 
*
* @package phpBB3
* @version $Id: functions_thanks.php,v 133 2011-12-04 10:02:51 Палыч$
* @copyright (c) 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit; 
}
$user->add_lang('mods/thanks_mod');

// Output thanks list
function get_thanks($post_id)
{
	global $thankers, $config, $user;
	$view = request_var('view', '');
	$return = '';
	$user_list = array();
	$count = 0;
	$maxcount = $config['thanks_number_post'];
	$further_thanks = 0;
	$further_thanks_text = '';

	foreach($thankers as $key => $value)
	{
		if ($thankers[$key]['post_id'] == $post_id)
		{
			if ($count >= $maxcount)
			{
				$further_thanks++;
			}
			else
			{
			$user_list[$thankers[$key]['username_clean']] = array(
				'thanks_time' => $thankers[$key]['thanks_time'],
				'username' => $thankers[$key]['username'],
				'user_id' => $thankers[$key]['user_id'],
				'user_colour' => $thankers[$key]['user_colour'],
				);
			}

			$count++;
		}
	}
	array_multisort($user_list, SORT_DESC);
	$comma = '';
	foreach($user_list as $key => $value)
	{
		$return .= $comma;
		$return .= get_username_string('full', $value['user_id'], $value['username'], $value['user_colour']);
		if ($config['thanks_time_view'])
		{
			$return .= ($value['thanks_time']) ? ' ('.$user->format_date($value['thanks_time'], false, ($view == 'print') ? true : false).')' : '';
		}
		$comma = ' &bull; ';
	}

   if ($further_thanks > 0)
   {
      $further_thanks_text = ($further_thanks == 1) ? $user->lang['FURTHER_THANKS'] : sprintf($user->lang['FURTHER_THANKS_PL'], $further_thanks);
   }
   $return = ($return == '') ? false : ($return . $further_thanks_text);
   return $return;
}

//get thanks number
function get_thanks_number($post_id)
{
	global $thankers;
	$i = 0;
	foreach($thankers as $key => $value)
	{
		if ($thankers[$key]['post_id'] == $post_id)
		{
			$i++;
		}
	}
	return $i;
}

// add a user to the thanks list
function insert_thanks($post_id, $user_id)
{
	global $db, $user, $phpbb_root_path, $phpEx, $forum_id, $config, $auth;
	$to_id = request_var('to_id', 0);
	$from_id = request_var('from_id', 0);	
	$sql_array = array(
		'SELECT'	=> 'p.post_id, p.poster_id, p.topic_id, p.forum_id',
		'FROM'		=> array (POSTS_TABLE => 'p'),
		'WHERE'		=> 'p.post_id ='. (int) $post_id );
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if ($user->data['user_type'] != USER_IGNORE && !empty($to_id))
	{
		if ($row['poster_id'] != $user_id && $row['poster_id'] == $to_id && !already_thanked($post_id, $user_id) && $auth->acl_get('f_thanks', $row['forum_id'])&& $from_id == $user_id)
		{
			$sql = 'INSERT INTO ' . THANKS_TABLE . ' ' . $db->sql_build_array('INSERT', array(
				'user_id'	=> (int) $user_id,
				'post_id'	=> $post_id,
				'poster_id'	=> $to_id,
				'topic_id'	=> (int) $row['topic_id'],
				'forum_id'	=> (int) $row['forum_id'],
				'thanks_time'	=> time()
			));
			$db->sql_query($sql);
		
			$lang_act = 'GIVE';
			if ($config ['thanks_notice_on'])
			{
				send_thanks_pm($user_id, $to_id, $send_pm = true, $post_id, $lang_act);
				send_thanks_email($to_id, $post_id, $lang_act);
			}	
			if ($config['thanks_info_page'])
			{
				meta_refresh (1, append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id"));
				trigger_error($user->lang['THANKS_INFO_'.$lang_act] . '<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id").'">'.$user->lang['RETURN_POST'].'</a>');
			}
			else
			{
				redirect (append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id"));			
			}
		}
		elseif (!$row['forum_id'])
		{
			trigger_error($user->lang['GLOBAL_INCORRECT_THANKS'] . '<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id").'">'.$user->lang['RETURN_POST'].'</a>');
		}
		else
		{
			trigger_error($user->lang['INCORRECT_THANKS'] . '<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id").'">'.$user->lang['RETURN_POST'].'</a>');
		}
	}
	return;
}

// clear list user's thanks
function clear_list_thanks($object_id, $list_thanks = '')
{
	global $db, $user, $phpbb_root_path, $phpEx, $auth, $config, $topic_data;
	// confirm
	$s_hidden_fields = build_hidden_fields(array(
		'list_thanks'		=> $list_thanks,
		)
	);
	$lang_act = $field_act = '';
	if (confirm_box(true))
	{
		if (!empty($list_thanks) && $auth->acl_get('m_thanks'))
		{
			if ($list_thanks === 'give')
			{	
				$lang_act = 'GIVE';
				$field_act = 'user_id';
			}	
			elseif ($list_thanks === 'receive')
			{
				$lang_act = 'RECEIVE';
				$field_act = 'poster_id';
			}	
			elseif ($list_thanks === 'post')
			{
				$lang_act = 'POST';
				$field_act = 'post_id';
			}	

			if (!empty($field_act))
			{
				$sql = "DELETE FROM " . THANKS_TABLE . '
					WHERE ' . $field_act .' = '. (int)$object_id;				
				$result = $db->sql_query($sql);		
			
				if ($result != 0)
				{
					if ($config['thanks_info_page'])
					{
						if ($list_thanks === 'post')
						{
							meta_refresh (1, append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p='.$object_id.'#p'.$object_id));
							trigger_error($user->lang['CLEAR_LIST_THANKS_'.$lang_act].'<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p='.$object_id.'#p'.$object_id. (($topic_data['topic_type'] == POST_GLOBAL) ? "&amp;f=$forum_id" : '')).'">'.$user->lang['BACK_TO_PREV'].'</a>');
						}
						else
						{
							meta_refresh (1, append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u='.$object_id));
							trigger_error($user->lang['CLEAR_LIST_THANKS_'.$lang_act].'<br /><br /><a href="'.append_sid("{$phpbb_root_path}memberlist.$phpEx",'mode=viewprofile&amp;u='.$object_id).'">'.$user->lang['BACK_TO_PREV'].'</a>');						
						}
					}
					else
					{
						if ($list_thanks === 'post')
						{
							redirect (append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p='.$object_id.'#p'.$object_id));
						}
						else
						{
							redirect (append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u='.$object_id));			
						}
					}
				}
			}	
		}	
		else
		{
			if ($list_thanks === 'post')
			{
				trigger_error($user->lang['INCORRECT_THANKS'] . '<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p='.$object_id.'#p'.$object_id) .'">'.$user->lang['BACK_TO_PREV'].'</a>');		
			}
			else
			{
				trigger_error($user->lang['INCORRECT_THANKS'] . '<br /><br /><a href="'.append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=',$object_id) .'">'.$user->lang['BACK_TO_PREV'].'</a>');			
			}
		}
	}
	else
	{
		confirm_box(false, 'CLEAR_LIST_THANKS', $s_hidden_fields);
		if ($list_thanks === 'post')
		{		
			redirect (append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p='.$object_id.'#p'.$object_id));
		}
		else
		{
			redirect(append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u='.$object_id));		
		}
	}
	return;
}

// remove a user's thanks
function delete_thanks($post_id, $user_id)
{
	global $db, $user, $phpbb_root_path, $phpEx, $forum_id, $config, $auth;
	$to_id = request_var('to_id', 0);
	// confirm
	$hidden = build_hidden_fields(array(
		'to_id'		=> $to_id,
		'rthanks'	=> $post_id,
		)
	);
	if (!$config['remove_thanks'])
	{
		trigger_error($user->lang['DISABLE_REMOVE_THANKS'] . '<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id") .'">'.$user->lang['RETURN_POST'].'</a>');		
	}
	
	if (confirm_box(true, 'REMOVE_THANKS', $hidden))
	{
		if ($user->data['user_type'] != USER_IGNORE && !empty($to_id) && $auth->acl_get('f_thanks', $forum_id))
		{
			$sql = "DELETE FROM " . THANKS_TABLE . '
				WHERE post_id ='. (int) $post_id ." AND user_id = " . (int) $user->data['user_id'];
			$db->sql_query($sql);
			$result = $db->sql_affectedrows($sql);
			if ($result != 0)
			{
				$lang_act = 'REMOVE';
				if ($config ['thanks_notice_on'])
				{
					send_thanks_pm($user_id, $to_id, $send_pm = true, $post_id, $lang_act);
					send_thanks_email($to_id, $post_id, $lang_act);
				}	
				if ($config['thanks_info_page'])
				{
					meta_refresh (1, append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id"));
					trigger_error($user->lang['THANKS_INFO_'.$lang_act].'<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id").'">'.$user->lang['RETURN_POST'].'</a>');
				}
				else
				{
					redirect (append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id"));
				}
			}
			else
			{
				trigger_error($user->lang['INCORRECT_THANKS'] . '<br /><br /><a href="'.append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id") .'">'.$user->lang['RETURN_POST'].'</a>');		
			}
		}
	}
	else
	{
		confirm_box(false, 'REMOVE_THANKS', $hidden);
		redirect(append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;p=$post_id#p$post_id"));
	}
	return;
}

// display the text/image saying either to add or remove thanks
function get_thanks_text($post_id)
{
	global $db, $user, $postrow;
	if (already_thanked($post_id, $user->data['user_id']))
	{
		$postrow = array_merge($postrow, array(
			'THANK_ALT'		=> $user->lang['REMOVE_THANKS'],
			'THANKS_IMG'	=> 'removethanks-icon',
		));
		return;
	}
	$postrow = array_merge($postrow, array(
		'THANK_ALT'		=> $user->lang['THANK_POST'],
		'THANKS_IMG'	=> 'thanks-icon',
	));
	return;
}

// change the variable sent via the link to avoid odd errors
function get_thanks_link($post_id)
{
	global $db, $user;
	if (already_thanked($post_id, $user->data['user_id']))
	{
		return 'rthanks';
	}
	return 'thanks';
}

// check if the user has already thanked that post
function already_thanked($post_id, $user_id)
{
	global $db, $thankers;
	$thanked = false;
	foreach((array)$thankers as $key => $value)
	{
		if ($thankers[$key]['post_id'] == $post_id && $thankers[$key]['user_id'] == $user_id)
		{
			$thanked = true;
		}
	}
	return $thanked;
}

// stuff goes here to avoid over-editing memberlist.php
function output_thanks_memberlist($user_id)
{
	global $db, $user, $row, $phpEx, $template, $phpbb_root_path, $config, $auth;
	
	$thankers_member = array();
	$thankered_member = array();
	$thanks = '';
	$thanked = '';
	$poster_receive_count = 0;
	$poster_give_count = 0;
	$poster_limit = $config['thanks_number'];
	
	$sql = 'SELECT poster_id, COUNT(*) AS poster_receive_count
		FROM ' . THANKS_TABLE . "
		WHERE poster_id = " . (int) $user_id. '
		GROUP BY poster_id';
	$result = $db->sql_query($sql);
	$poster_receive_count = (int) $db->sql_fetchfield('poster_receive_count');
	$db->sql_freeresult($result);
	
	$sql_array = array(
		'SELECT'	=> 't.*, u.username, u.user_colour',
		'FROM'		=> array(THANKS_TABLE => 't', USERS_TABLE => 'u'),
	);
	$sql_array['WHERE'] = 't.poster_id ='. (int) $user_id .' AND ';
	$sql_array['WHERE'] .= 'u.user_id = t.user_id';
	$sql_array['ORDER_BY'] = 't.post_id DESC LIMIT ' .(int)$poster_limit;
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$thankers_member[] = array(  
			'user_id' 		=> $row['user_id'], 
			'poster_id' 	=> $row['poster_id'], 
			'post_id' 		=> $row['post_id'], 
			'username'		=> $row['username'],
			'user_colour'	=> $row['user_colour'],
		);	

	}	
	$db->sql_freeresult($result);
    $user_list = array();
	$post_list = array ();
	$i=0;
	foreach($thankers_member as $key => $value)
	{
		if ($thankers_member[$key]['poster_id'] == $user_id)
		{
			$i++;
			$user_list[$i] = array( 
				'username' 		=> $thankers_member[$key]['username'],
				'user_id' 		=> $thankers_member[$key]['user_id'], 
				'user_colour' 	=> $thankers_member[$key]['user_colour'],
				'post_id' 		=> $thankers_member[$key]['post_id'], 
			);
		}
	}
	unset ($value);
	$collim = ($poster_limit > $poster_receive_count)? ceil($poster_receive_count/4) : ceil($poster_limit/4);
	$thanked .= '<span style="float: left;">';
	$i = $j = 0;
	foreach($user_list as $value)
	{
		$i++;
		if ($i <= $poster_limit)
		{
			$thanked .= '&nbsp;&nbsp;&bull;&nbsp;&nbsp;'. get_username_string('full', $value['user_id'], $value['username'], $value['user_colour']) . ' &#8594; <a href="'. append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $value['post_id']. '#p' . $value['post_id']) . '">' . $user->lang['FOR_MESSAGE'] . '</a><br />';
			$j++;	
			if ($j > $collim or $i == $poster_receive_count or $i == $poster_limit)
			{
				$thanked .= '&nbsp;</span>';
				$j = 0;
				if ($i < $poster_limit and $i < $poster_receive_count)
				{
					$thanked .= '<span style="float: left;">';
				}
			}
		}
	}	
	if ($poster_receive_count > $poster_limit)
	{
		$further_thanks = $poster_receive_count - $poster_limit;
		$further_thanks_text = ($further_thanks == 1) ? $user->lang['FURTHER_THANKS'] : sprintf($user->lang['FURTHER_THANKS_PL'], $further_thanks);
		$thanked.= '<span style="float: left;">&nbsp;'.$further_thanks_text.'</span>';
	}
	unset ($value);
//===
	$sql = 'SELECT user_id, COUNT(*) AS poster_give_count
		FROM ' . THANKS_TABLE . "
		WHERE user_id = " . (int) $user_id. '
		GROUP BY user_id';
	$result = $db->sql_query($sql);
	$poster_give_count = (int) $db->sql_fetchfield('poster_give_count');
	$db->sql_freeresult($result);
	
	$sql_array = array(
		'SELECT'	=> 't.*, u.username, u.user_colour',
		'FROM'		=> array(THANKS_TABLE => 't', USERS_TABLE => 'u'),
	);
	$sql_array['WHERE'] = 't.user_id ='. (int) $user_id . ' AND ';
	$sql_array['WHERE'] .= 'u.user_id = t.poster_id';
	$sql_array['ORDER_BY'] = 't.post_id DESC LIMIT ' .(int)$poster_limit;
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$thankered_member[] = array(
			'user_id' 		=> $row['user_id'],
			'poster_id' 	=> $row['poster_id'],
			'post_id' 		=> $row['post_id'],
			'username'		=> $row['username'],
			'user_colour'	=> $row['user_colour'],
		);
	}
	$db->sql_freeresult($result);
	
	$i=0;
	foreach($thankered_member as $key => $value)
	{
		if ($thankered_member[$key]['user_id'] == $user_id)
		{
			$i++;
			$post_list[$i] = array(
				'postername' 		=> $thankered_member[$key]['username'],
				'poster_id' 		=> $thankered_member[$key]['poster_id'],
				'poster_colour' 	=> $thankered_member[$key]['user_colour'],
				'post_id' 			=> $thankered_member[$key]['post_id'],
			);
		}
	}
	unset ($value);
	$collim = ($poster_limit > $poster_give_count)? ceil($poster_give_count/4) : ceil($poster_limit/4);
	$thanks .= '<span style="float: left;">';
	$i = $j = 0;
	foreach($post_list as $value)
	{
		$i++;
		if ($i <= $poster_limit)
		{
			$thanks .= '&nbsp;&nbsp;&bull;&nbsp;&nbsp;'. get_username_string('full', $value['poster_id'], $value['postername'], $value['poster_colour']) . ' &#8592; <a href="'. append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $value['post_id']. '#p' . $value['post_id']) . '">' . $user->lang['FOR_MESSAGE'] . '</a><br />';
			$j++;	
			if ($j > $collim or $i == $poster_give_count or $i == $poster_limit)
			{
				$thanks .= '</span>';
				$j = 0;
				if ($i < $poster_limit and $i < $poster_give_count)
				{
					$thanks .= '<span style="float: left;">';
				}
			}
		}
	}
	if ($poster_give_count > $poster_limit)
	{		
		$further_thanks = $poster_give_count - $poster_limit;
		$further_thanks_text = ($further_thanks == 1) ? $user->lang['FURTHER_THANKS'] : sprintf($user->lang['FURTHER_THANKS_PL'], $further_thanks);
		$thanks.= '<span style="float: left;">&nbsp;'.$further_thanks_text.'</span>';
	}
	unset ($value);	

	$template->assign_vars(array(
		'DELETE_IMG' 			=> $user->img('icon_post_delete', $user->lang['CLEAR_LIST_THANKS']),
		'POSTER_RECEIVE_COUNT'	=> $poster_receive_count,
		'THANKS'				=> $thanks,
		'POSTER_GIVE_COUNT'		=> $poster_give_count,
		'THANKED'				=> $thanked,
		'THANKS_PROFILELIST_VIEW'	=>	$config['thanks_profilelist_view'],
		'S_MOD_THANKS'			=> $auth->acl_get('m_thanks'),
		'U_CLEAR_LIST_THANKS_GIVE'	=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u='.$user_id.'&amp;list_thanks=give'),
		'U_CLEAR_LIST_THANKS_RECEIVE'	=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u='.$user_id.'&amp;list_thanks=receive'),
	));
}

// stuff goes here to avoid over-editing viewtopic.php
function output_thanks($user_id)
{
	global $db, $user, $poster_id, $postrow, $row, $phpEx, $topic_data, $phpbb_root_path, $config, $forum_id, $max_post_thanks, $auth, $poster_list_count;
	if (!empty($postrow))
	{
		get_thanks_text($row['post_id']);
		$thank_mode = get_thanks_link($row['post_id']);
		$postrow = array_merge($postrow, array(
			'COND'						=> (already_thanked($row['post_id'], $user->data['user_id'])) ? true : false,
			'THANKS'					=> get_thanks($row['post_id']),
			'THANK_MODE'				=> $thank_mode,
			'THANKS_LINK'				=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", "p={$row['post_id']}" . (($topic_data['topic_type'] == POST_GLOBAL) ? "&amp;f=$forum_id" : '') . "&amp;" . $thank_mode . "={$row['post_id']}&amp;to_id=$poster_id&amp;from_id=".$user->data['user_id']),
			'THANK_TEXT'				=> $user->lang['THANK_TEXT_1'],
			'THANK_TEXT_2'				=> (get_thanks_number($row['post_id']) != 1) ? sprintf($user->lang['THANK_TEXT_2PL'], get_thanks_number($row['post_id'])) : $user->lang['THANK_TEXT_2'],
			'THANKS_FROM'				=> $user->lang['THANK_FROM'],
			'POSTER_RECEIVE_COUNT'		=> isset($poster_list_count[$poster_id]['R']) ? $poster_list_count[$poster_id]['R'] : '',
			'POSTER_GIVE_COUNT'			=> isset($poster_list_count[$poster_id]['G']) ? $poster_list_count[$poster_id]['G'] : '',
			'POSTER_RECEIVE_COUNT_LINK'	=> append_sid("{$phpbb_root_path}thankslist.$phpEx", "mode=givens&amp;author_id={$poster_id}&amp;give=false"),
			'POSTER_GIVE_COUNT_LINK'	=> append_sid("{$phpbb_root_path}thankslist.$phpEx", "mode=givens&amp;author_id={$poster_id}&amp;give=true"),
			'S_IS_OWN_POST'				=> ($user->data['user_id'] == $poster_id) ? true : false,
			'S_POST_ANONYMOUS'			=> ($poster_id == ANONYMOUS) ? true : false,
			'THANK_IMG' 				=> (already_thanked($row['post_id'], $user->data['user_id'])) ? $user->img('removethanks', $user->lang['REMOVE_THANKS']. get_username_string('username', $poster_id, $row['username'], $row['user_colour'], $row['post_username'])) : $user->img('thankposts', $user->lang['THANK_POST']. get_username_string('username', $poster_id, $row['username'], $row['user_colour'], $row['post_username'])),
			'DELETE_IMG' 				=> $user->img('icon_post_delete', $user->lang['CLEAR_LIST_THANKS']),
			'THANKS_POSTLIST_VIEW'		=> $config['thanks_postlist_view'],
			'THANKS_COUNTERS_VIEW'		=> $config['thanks_counters_view'],
			'S_ALREADY_THANKED'			=> already_thanked($row['post_id'], $user->data['user_id']),
			'S_REMOVE_THANKS'			=> $config['remove_thanks'],
			'S_FIRST_POST_ONLY'			=> $config['thanks_only_first_post'],
			'POST_REPUT'				=> (get_thanks_number($row['post_id']) != 0) ? round(get_thanks_number($row['post_id']) / ($max_post_thanks / 100), $config['thanks_number_digits']).'%' : '',
			'S_THANKS_POST_REPUT_VIEW' 	=> $config['thanks_post_reput_view'],
			'S_THANKS_REPUT_GRAPHIC' 	=> $config['thanks_reput_graphic'],
			'THANKS_REPUT_HEIGHT'		=> sprintf('%dpx', $config['thanks_reput_height']),
			'THANKS_REPUT_GRAPHIC_WIDTH' 	=> sprintf('%dpx', $config['thanks_reput_level']*$config['thanks_reput_height']),
			'THANKS_REPUT_IMAGE' 		=> ($config['thanks_reput_image']) ? $phpbb_root_path . $config['thanks_reput_image'] : '',
			'THANKS_REPUT_IMAGE_BACK'	=> ($config['thanks_reput_image_back']) ? $phpbb_root_path . $config['thanks_reput_image_back'] : '',
			'S_GLOBAL_POST_THANKS'		=> ($topic_data['topic_type'] == POST_GLOBAL) ? true : false,
			'U_CLEAR_LIST_THANKS_POST'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", "p={$row['post_id']}" . (($topic_data['topic_type'] == POST_GLOBAL) ? "&amp;f=$forum_id" : '') ."&amp;list_thanks=post"),
			'S_MOD_THANKS'				=> $auth->acl_get('m_thanks'),
		));
	}
}

//refresh counts if post delete
function delete_post_thanks($post_id)
{
	global $db; 
	$sql = 'DELETE 
			FROM ' . THANKS_TABLE . "
			WHERE post_id =". (int) $post_id;
	$db->sql_query($sql);
}

//send pm
function send_thanks_pm($user_id, $to_id, $send_pm = true, $post_id = 0, $lang_act)
{
	global $phpEx, $phpbb_root_path, $config, $row, $forum_id, $user, $db;
	if (!$config['thanks_notice_on'])
	{
		return;	
	}
	$allow_thanks_pm = 0;
	$sql_array = array(
		'SELECT'	=> 'u.user_allow_thanks_pm',
		'FROM'		=> array(USERS_TABLE => 'u'),
			);
	$sql_array['WHERE'] = 'u.user_id ='. (int) $to_id;
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	$allow_thanks_pm = (int) $db->sql_fetchfield('user_allow_thanks_pm');
	$db->sql_freeresult($result);
	
	if (!$allow_thanks_pm)
	{
		return;	
	}
	include($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
	$user->data['user_lang'] = (file_exists($phpbb_root_path . 'language/' . $user->data['user_lang'] . "/mods/thanks_mod.$phpEx")) ? $user->data['user_lang'] : $config['default_lang'];
	$user->add_lang('mods/thanks_mod');
	$massage = '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id .'#p' . $post_id) .'">'. $user->lang['THANKS_PM_MES_'. $lang_act] .'</a>';
	$pm_data = array(
		'from_user_id'			=> $user->data['user_id'],
		'from_user_ip'			=> $user->ip,
		'from_username'			=> $user->data['username'],
		'enable_sig'			=> false,
		'enable_bbcode'			=> true,
		'enable_smilies'		=> false,
		'enable_urls'			=> false,
		'icon_id'				=> 0,
		'bbcode_bitfield'		=> '',
		'bbcode_uid'			=> '',
		'message'				=> $massage,
		'address_list'			=> array('u' => array($to_id => 'to')),
	);
		generate_text_for_storage($pm_data['message'], $pm_data['bbcode_uid'], $pm_data['bbcode_bitfield'], $flags, $pm_data['enable_bbcode'], $pm_data['enable_urls'], $pm_data['enable_smilies']);
	submit_pm('post', $user->lang['THANKS_PM_SUBJECT_'.$lang_act], $pm_data, false);
	return;
}
//send email
function send_thanks_email($to_id, $post_id, $lang_act)
{
	global $phpEx, $phpbb_root_path, $config, $row, $forum_id, $user, $db;
	if (!$config['thanks_notice_on'])
	{
		return;	
	}
	if ($config['email_enable'])
	{
		$sql_array = array(
			'SELECT'	=> 'u.user_allow_thanks_email',
			'FROM'		=> array(USERS_TABLE => 'u'),
				);
		$sql_array['WHERE'] = 'u.user_id ='. (int) $to_id;
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$allow_thanks_email = (int) $db->sql_fetchfield('user_allow_thanks_email');
		$db->sql_freeresult($result);	

		if (!$allow_thanks_email)
		{
			return;	
		}
		$server_url = generate_board_url();
		$sql_array = array(
		'SELECT'	=> 'u.user_email, u.user_lang, u.user_email, u.username',
		'FROM'		=> array(USERS_TABLE => 'u'),
			);
		$sql_array['WHERE'] = 'u.user_id ='. (int) $to_id;
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$row['user_lang'] = (file_exists($phpbb_root_path . 'language/' . $row['user_lang'] . "/mods/thanks_mod.$phpEx")) ? $row['user_lang'] : $config['default_lang'];
		
		if (!class_exists('messenger'))
		{
			include($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
		}
		$messenger = new messenger(false);
		$messenger->template('user_thanks', $row['user_lang']);
		$messenger->to($row['user_email'], $row['username']);
		$messenger->headers('X-AntiAbuse: Board servername - ' . $config['server_name']);
		$messenger->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
		$messenger->headers('X-AntiAbuse: Username - ' . $user->data['username']);
		$messenger->headers('X-AntiAbuse: User IP - ' . $user->ip);
		$messenger->assign_vars(array(
			'THANKS_SUBG'	=> htmlspecialchars_decode($user->lang['GRATITUDES']),
			'USERNAME'		=> htmlspecialchars_decode($user->data['username']),
			'POST_THANKS'	=> htmlspecialchars_decode($user->lang['THANKS_PM_MES_'. $lang_act]),
			'U_POST_THANKS'	=> "$server_url/viewtopic.".$phpEx.'?p='.$post_id.'#p'. $post_id,
		));
		$messenger->send(NOTIFY_EMAIL);
	}
	return;
}
	
// create an array of all thanks info
function array_all_thanks($post_list)
{
	global $db, $post_list, $thankers, $max_post_thanks, $poster_list_count, $config, $forum_id, $auth;
	$thankers = array();
	$poster_list = array();
	$poster_list_count = array();	
// max post thanks
	if ($config['thanks_post_reput_view'])
	{
	$sql = 'SELECT MAX(tally) AS max_post_thanks
		FROM (SELECT post_id, COUNT(*) AS tally FROM ' . THANKS_TABLE . ' GROUP BY post_id) t';
	$result = $db->sql_query($sql);
	$max_post_thanks = (int) $db->sql_fetchfield('max_post_thanks');
	$db->sql_freeresult($result);
	}
	else
	{
	$max_post_thanks = 1;
	}
	
//array all user who say thanks on viewtopic page
	if ($auth->acl_get('f_thanks', $forum_id))
	{
		$sql_array = array(
			'SELECT'	=> 't.*, u.username, u.username_clean, u.user_colour',
			'FROM'		=> array(THANKS_TABLE => 't', USERS_TABLE => 'u'),
		);
		$sql_array['WHERE'] = 'u.user_id = t.user_id AND ' . $db->sql_in_set('t.post_id', $post_list);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$j = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$thankers[$j] = array(  
				'user_id' 			=> $row['user_id'],
				'poster_id' 		=> $row['poster_id'],
				'post_id' 			=> $row['post_id'],
				'thanks_time'		=> $row['thanks_time'],
				'username'			=> $row['username'],
				'username_clean'	=> $row['username_clean'],
				'user_colour'		=> $row['user_colour'],
			);
			$j++;
		}
		$db->sql_freeresult($result);
	}
//array thanks_count for all poster on viewtopic page
	if($config['thanks_counters_view'])
	{
		$sql = 'SELECT DISTINCT poster_id FROM '. POSTS_TABLE . ' WHERE ' . $db->sql_in_set('post_id', $post_list);
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$poster_list[] = $row['poster_id'];
			$poster_list_count[$row['poster_id']]['R'] = $poster_list_count[$row['poster_id']]['G'] = 0;
		}
		$db->sql_freeresult($result);
		
		$sql = 'SELECT *, COUNT(poster_id) AS poster_count FROM ' . THANKS_TABLE . ' WHERE ' .$db->sql_in_set('poster_id', $poster_list). ' GROUP BY poster_id';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$poster_list_count[$row['poster_id']]['R'] = $row['poster_count'];
		}
		$db->sql_freeresult($result);		

		$sql = 'SELECT *, COUNT(user_id) AS user_count FROM ' . THANKS_TABLE . ' WHERE ' .$db->sql_in_set('user_id', $poster_list). ' GROUP BY user_id';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$poster_list_count[$row['user_id']]['G'] = $row['user_count'];
		}
		$db->sql_freeresult($result);	
	}
	return;
}
// topic reput
function get_thanks_topic_reput($topic_id)
{
	global $max_topic_thanks, $config, $phpbb_root_path, $template, $topic_thanks;
	$template->assign_block_vars('topicrow.reput', array(
		'TOPIC_REPUT'				=> (isset($topic_thanks[$topic_id])) ? round($topic_thanks[$topic_id] / ($max_topic_thanks / 100), $config['thanks_number_digits']).'%' : '',
		'S_THANKS_TOPIC_REPUT_VIEW' => $config['thanks_topic_reput_view'],
		'S_THANKS_TOPIC_REPUT_VIEW_COLUMN' => $config['thanks_topic_reput_view_column'],
		'S_THANKS_REPUT_GRAPHIC' 	=> $config['thanks_reput_graphic'],
		'THANKS_REPUT_HEIGHT'		=> sprintf('%dpx', $config['thanks_reput_height']),
		'THANKS_REPUT_GRAPHIC_WIDTH'=> sprintf('%dpx', $config['thanks_reput_level']*$config['thanks_reput_height']),
		'THANKS_REPUT_IMAGE' 		=> ($config['thanks_reput_image']) ? $phpbb_root_path . $config['thanks_reput_image'] : '',
		'THANKS_REPUT_IMAGE_BACK'	=> ($config['thanks_reput_image_back']) ? $phpbb_root_path . $config['thanks_reput_image_back'] : '',	
	));
}
// topic thanks number
function get_thanks_topic_number()
{
	global $db, $topic_thanks, $topic_list, $config;
	if ($config['thanks_topic_reput_view'])
	{
	$sql = 'SELECT topic_id, COUNT(*) AS topic_thanks
		FROM ' . THANKS_TABLE . "
		WHERE " . $db->sql_in_set('topic_id', $topic_list) . '
		GROUP BY topic_id';
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$topic_thanks[$row['topic_id']] = $row['topic_thanks'];
	}
	$db->sql_freeresult($result);
	return array($topic_thanks);
	}
}
// max topic thanks
function get_max_topic_thanks()
{
	global $db, $max_topic_thanks, $config;
	if ($config['thanks_topic_reput_view'])
	{
	$sql = 'SELECT MAX(tally) AS max_topic_thanks
		FROM (SELECT topic_id, COUNT(*) AS tally FROM ' . THANKS_TABLE . ' GROUP BY topic_id) t';
	$result = $db->sql_query($sql);
	$max_topic_thanks = (int) $db->sql_fetchfield('max_topic_thanks');
	$db->sql_freeresult($result);
	return $max_topic_thanks;
	}
}
// max post thanks for toplist
function get_max_post_thanks()
{
	global $db, $max_post_thanks;
	$sql = 'SELECT MAX(tally) AS max_post_thanks
		FROM (SELECT post_id, COUNT(*) AS tally FROM ' . THANKS_TABLE . ' GROUP BY post_id) t';
	$result = $db->sql_query($sql);
	$max_post_thanks = (int) $db->sql_fetchfield('max_post_thanks');
	$db->sql_freeresult($result);
	return $max_post_thanks;
}
?>