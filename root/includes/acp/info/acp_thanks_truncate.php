<?php
/**
*
* @package acp
* @version $Id: acp_thanks_truncate.php,v 133 2011-12-04 10:02:51 Палыч$
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package module_install
*/
if (!defined('IN_PHPBB'))
{
   exit;
}

class acp_thanks_truncate_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_thanks_truncate',
			'title'		=> 'ACP_THANKS_TRUNCATE',
			'version'	=> '1.3.1',
			'modes'		=> array(
				'thanks'			=> array('title' => 'ACP_THANKS_TRUNCATE', 'auth' => 'acl_a_board', 'cat' => array('ACP_THANKS')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>