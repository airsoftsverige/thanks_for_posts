<?php
/**
*
* thanks_mod[English]
*
* @package language
* @version $Id: info_acp_thanks.php 133 2011-12-02 21:36:00 doktornotor $
* @copyright (c) 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
   exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'CLEAR_LIST_THANKS'			=> 'Clear Thanks List',
	'CLEAR_LIST_THANKS_CONFIRM'	=> 'Do you really want to clear the user`s Thanks List?',
	'CLEAR_LIST_THANKS_GIVE'	=> 'List of thanks issued by the user was cleared.',
	'CLEAR_LIST_THANKS_POST'	=> 'List of thanks in the message was cleared.',
	'CLEAR_LIST_THANKS_RECEIVE'	=> 'List of thanks obtained by the user was cleared.',

	'DISABLE_REMOVE_THANKS'		=> 'Deleting thanks has been disabled by the administrator',
	
	'GIVEN'						=> 'Has&nbsp;thanked',
	'GLOBAL_INCORRECT_THANKS'	=> 'You cannot give thanks for a Global Announcement that has no reference to a particular forum.',
	'GRATITUDES'				=> 'Thanks list',
	
	'INCORRECT_THANKS'			=> 'Invalid thank',
	
	'JUMP_TO_FORUM'				=> 'Jump to forum',
	'JUMP_TO_TOPIC'				=> 'Jump to topic',

	'FOR_MESSAGE'				=> ' for post',
	'FURTHER_THANKS'     	    => ' and one more user',
	'FURTHER_THANKS_PL'         => ' and %d more users',
	
	'NO_VIEW_USERS_THANKS'		=> 'You are not authorised to view the Thanks List.',

	'RECEIVED'					=> 'Been&nbsp;thanked',
	'REMOVE_THANKS'				=> 'Remove your thanks: ',
	'REMOVE_THANKS_CONFIRM'		=> 'Are you sure you want to remove your thanks?',
	'REPUT'						=> 'Rating',
	'REPUT_TOPLIST'				=> 'Thanks Toplist ',
	'RETING_LOGIN_EXPLAIN'		=> 'You are not authorised to view the toplist.',
	'RATING_NO_VIEW_TOPLIST'	=> 'You are not authorised to view the toplist.',
	'RATING_VIEW_TOPLIST_NO'	=> 'Toplist is empty or disabled by administrator',
	'RATING_FORUM'				=> 'Forum',
	'RATING_POST'				=> 'Post',
	'RATING_TOP_FORUM'			=> 'Rating forums',
	'RATING_TOP_POST'			=> 'Rating posts',
	'RATING_TOP_TOPIC'			=> 'Rating topics',	
	'RATING_TOPIC'				=> 'Topic',
	'RETURN_POST'				=> 'Return',

	'THANK'						=> 'time',
	'THANK_FROM'				=> 'from',
	'THANK_TEXT_1'				=> 'These users thanked the author ',
	'THANK_TEXT_2'				=> ' for the post: ',
	'THANK_TEXT_2PL'			=> ' for the post (total %d):',
	'THANK_POST'				=> 'Say Thanks to the author of the post: ',
	'THANKS'					=> 'times',
	'THANKS_BACK'				=> 'Return',
	'THANKS_INFO_GIVE'			=> 'You have just thanked for the post.',
	'THANKS_INFO_REMOVE'		=> 'You have just removed your thank.',
	'THANKS_LIST'				=> 'View/Close list',
	'THANKS_PM_MES_GIVE'		=> 'Thanks for the post',
	'THANKS_PM_MES_REMOVE'		=> 'Remove thanks',
	'THANKS_PM_SUBJECT_GIVE'	=> 'Thanks for the post',
	'THANKS_PM_SUBJECT_REMOVE'	=> 'Remove thanks',
	'THANKS_USER'				=> 'List of thanks',
// Install block
	'THANKS_INSTALLED'			=> 'Thanks for the post',
	'THANKS_INSTALLED_EXPLAIN'  => '<strong>CAUTION!<br />You are strongly advised to only run this installation after following the instruction on code changes to the files (or perform the installation using AutoMod)! <br />It is also strongly recommended to select Yes to Display Full Results (below)!</strong>',
	'THANKS_CUSTOM_FUNCTION'	=> 'Update values for the _thanks table',
));
?>