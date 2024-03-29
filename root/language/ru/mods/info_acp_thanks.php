<?php
/**
*
* mod_thanks [Russian]
*
* @package language
* @version $Id: info_acp_thanks.php 133 2011-12-04 10:02:51Палыч $
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

$lang = array_merge($lang, array(
	'acl_f_thanks' 						=> array('lang' => 'Может благодарить за сообщения', 'cat' => 'misc'),
	'acl_m_thanks' 						=> array('lang' => 'Может очищать список благодарностей', 'cat' => 'misc'),
	'acl_u_viewthanks' 					=> array('lang' => 'Может просматривать лист всех благодарностей', 'cat' => 'misc'),
	'acl_u_viewtoplist'					=> array('lang' => 'Может просматривать топлист', 'cat' => 'misc'),
	'ACP_DELTHANKS'						=> 'Удалено учтённых благодарностей',
	'ACP_DELPOST'						=> 'Отсутствует сообщений (удалено)',
	'ACP_DELUPOST'						=> 'Отсутствует пользователей (удалено)',
	'ACP_POSTS'							=> 'Всего сообщений',
	'ACP_POSTSEND'						=> 'Осталось сообщений с благодарностями',
	'ACP_POSTSTHANKS'					=> 'Было сообщений с благодарностями',
	'ACP_THANKS'						=> 'Благодарности',
	'ACP_THANKS_MOD_VER'				=> 'Версия МОДа: ',
	'ACP_THANKS_TRUNCATE'				=> 'Очистка списка благодарностей',
	'ACP_ALLTHANKS'						=> 'Было учтено благодарностей',
	'ACP_THANKSEND'						=> 'Осталось учтено благодарностей',
	'ACP_THANKS_REPUT'					=> 'Опции рейтинга',
	'ACP_THANKS_REPUT_SETTINGS'			=> 'Опции рейтинга',
	'ACP_THANKS_REPUT_SETTINGS_EXPLAIN'	=> 'Здесь вы можете установить значения настроек рейтинга для сообщений, тем и форумов, основанного на системе СПАСИБО. Объект (сообщение, тема или форум), имеющий наибольшее суммарное число благодарностей, принимается за 100% рейтинга.',
	'ACP_THANKS_SETTINGS'				=> 'Опции конфигурации',
	'ACP_THANKS_SETTINGS_EXPLAIN'		=> 'Здесь вы можете установить значения настроек функций благодарностей за сообщения.',
	'ACP_THANKS_REFRESH'				=> 'Обновление счётчиков',
	'ACP_USERS'							=> 'Всего пользователей (включая Ботов и Гостя)',
	'ACP_USERSEND'						=> 'Осталось благодаривших пользователей',
	'ACP_USERSTHANKS'					=> 'Было благодаривших пользователей',
	
	'GRAPHIC_BLOCK_BACK'				=> 'images/rating/reput_block_back.gif',
	'GRAPHIC_BLOCK_RED'					=> 'images/rating/reput_block_red.gif',
	'GRAPHIC_DEFAULT'					=> 'Примеры изображений',
	'GRAPHIC_OPTIONS'					=> 'Параметры графики',
	'GRAPHIC_STAR_BACK'					=> 'images/rating/reput_star_back.gif',
	'GRAPHIC_STAR_BLUE'					=> 'images/rating/reput_star_blue.gif',
	'GRAPHIC_STAR_GOLD'					=> 'images/rating/reput_star_gold.gif',

	'IMG_THANKPOSTS'					=> 'Поблагодарить за сообщение',
	'IMG_REMOVETHANKS'					=> 'Удалить благодарность',

	'LOG_CONFIG_THANKS'					=> 'Обновлена конфигурация МОДа Спасибо за сообщение',
	'REFRESH'							=> 'Обновить',
	'REMOVE_THANKS'						=> 'Удаление благодарностей',
	'REMOVE_THANKS_EXPLAIN'				=> 'Если включено, пользователь может отменить выданную благодарность.',

	'STEPR'								=> ' - выполнение, шаг %s',
	
	'THANKS_COUNTERS_VIEW'				=> 'Счётчики благодарностей',
	'THANKS_COUNTERS_VIEW_EXPLAIN'		=> 'Если включено, в блоке информации об авторе будет отображаться количество выданных/полученных благодарностей.',
	'THANKS_FORUM_REPUT_VIEW'			=> 'Отображение рейтинга для форумов',
	'THANKS_FORUM_REPUT_VIEW_COLUMN'	=> 'Отображение рейтинга для форумов в колонке',
	'THANKS_FORUM_REPUT_VIEW_COLUMN_EXPLAIN' => 'Если включено, рейтинг форума будет отображаться в отдельной колонке. <br /> Примечание: эта опция эффективна только для стиля prosilver и только при включении опции выше.',
	'THANKS_FORUM_REPUT_VIEW_EXPLAIN'	=> 'Если включено, в списке форумов будет отображен рейтинг форума.',
	'THANKS_INFO_PAGE'					=> 'Информационные сообщения',
	'THANKS_INFO_PAGE_EXPLAIN'			=> 'Если включено, после выдачи/отмены благодарности выводится информационное сообщение.',
	'THANKS_NOTICE_ON'					=> 'Уведомления доступны',
	'THANKS_NOTICE_ON_EXPLAIN'			=> 'Если включено, уведомления доступны и пользователь может настраивать уведомления в личном разделе.',
	'THANKS_NUMBER'						=> 'Число благодарностей в списках профиля',
	'THANKS_NUMBER_EXPLAIN'				=> 'Максимальное число благодарностей, отображаемое в списках в профиле.<br /><strong>Внимание! Установление значения более 250 может замедлить работу конференции.</strong>',
	'THANKS_NUMBER_DIGITS'				=> 'Число десятичных разрядов для рейтинга',
	'THANKS_NUMBER_DIGITS_EXPLAIN'		=> 'Укажите число десятичных разрядов после запятой для значения рейтинга.',
	'THANKS_NUMBER_ROW_REPUT'			=> 'Число строк в топлисте для рейтинга',
	'THANKS_NUMBER_ROW_REPUT_EXPLAIN'	=> 'Укажите число строк для вывода топлиста рейтинга сообщений, тем и форумов.',
	'THANKS_NUMBER_POST'				=> 'Число благодарностей в списке в сообщении',
	'THANKS_NUMBER_POST_EXPLAIN'		=> 'Максимальное число благодарностей, отображаемое в списке в посте.<br /><strong>Внимание! Установление значения более 250 может замедлить работу конференции.</strong>',
	'THANKS_ONLY_FIRST_POST'			=> 'Благодарность только за первое сообщение в теме',
	'THANKS_ONLY_FIRST_POST_EXPLAIN'	=> 'Если включено, пользователь может объявить благодарность только за первое сообщение в теме.',
	'THANKS_POST_REPUT_VIEW'			=> 'Отображение рейтинга для сообщений',
	'THANKS_POST_REPUT_VIEW_EXPLAIN'	=> 'Если включено, при просмотре тем будет отображено значение рейтинга для сообщений.',
	'THANKS_POSTLIST_VIEW'				=> 'Список благодарностей в сообщении',
	'THANKS_POSTLIST_VIEW_EXPLAIN'		=> 'Если включено, в сообщении будет отображаться список пользователей, поблагодаривших автора за сообщение.<br />Учтите, что данная опция будет эффективна, если в форуме разрешено право благодарить за сообщения.',
	'THANKS_PROFILELIST_VIEW'			=> 'Списки благодарностей в профиле',
	'THANKS_PROFILELIST_VIEW_EXPLAIN'	=> 'Если включено, при просмотре профиля пользователя будут отображаться списки выданных и полученных благодарностей.',
	'THANKS_REFRESH'					=> 'Обновление счётчиков благодарностей',
	'THANKS_REFRESH_EXPLAIN'			=> 'Обновление счётчиков благодарностей после массового удаления постов/тем/пользователей, объединения/разделения тем, установки/снятия для темы опции важная, замены автора поста и т.п. <br /><strong>Важно: Для корректной работы функции обновления счётчиков требуется MySQL версии 4.1 или выше!</strong>',
	'THANKS_REFRESH_MSG'				=> 'Выполнение обновления может занять некоторое время. Все некорректные записи благодарностей будут удалены! <br /> Действие не обратимо!',
	'THANKS_REFRESHED_MSG'				=> 'Счётчики благодарностей обновлены',
	'THANKS_REPUT_GRAPHIC'				=> 'Графичеcкое отображение рейтинга',
	'THANKS_REPUT_GRAPHIC_EXPLAIN'		=> 'Если включено, значение рейтинга будет отображаться графически с помощью изображений, указанных ниже.',
	'THANKS_REPUT_HEIGHT'				=> 'Высота графики',
	'THANKS_REPUT_HEIGHT_EXPLAIN'		=> 'Укажите высоту графической шкалы для рейтинга в пикселах.<br /> <strong>Внимание! Для корректного отображения необходимо указывать высоту, равную высоте указанных ниже изображений!</strong>',
	'THANKS_REPUT_IMAGE'				=> 'Основное изображение для графической шкалы',
	'THANKS_REPUT_IMAGE_DEFAULT'		=> '<strong>Примеры графичеcких изображений</strong>',
	'THANKS_REPUT_IMAGE_DEFAULT_EXPLAIN' => 'Здесь вы можете видеть само изображение и путь к файлу изображения для стиля prosilver. Размеры изображений 15х15 пикселей. <br />Вы можете нарисовать собственные изображения - основное и фоновое, для этого можно использовать файл reput_star_.psd в папке contrib. <strong>Высота и ширина изображения должны быть одинаковыми для корректного построения графической шкалы.</strong>',
	'THANKS_REPUT_IMAGE_EXPLAIN'		=> 'Путь относительно корневой папки phpBB к основному изображению, выбранному для графической шкалы.',
	'THANKS_REPUT_IMAGE_NOEXIST'		=> 'Файл основного изображения для графической шкалы отсутствует',
	'THANKS_REPUT_IMAGE_BACK'			=> 'Фоновое изображение для графической шкалы',
	'THANKS_REPUT_IMAGE_BACK_EXPLAIN'	=> 'Путь относительно корневой папки phpBB к фоновому изображению, выбранному для графической шкалы.',
	'THANKS_REPUT_IMAGE_BACK_NOEXIST'	=> 'Файл фонового изображения для графической шкалы отсутствует',
	'THANKS_REPUT_LEVEL'				=> 'Количество изображений в графической шкале',
	'THANKS_REPUT_LEVEL_EXPLAIN'		=> 'Максимальное число изображений, соответствующее 100% значению рейтинга в графической шкале.',
	'THANKS_TIME_VIEW'					=> 'Время благодарности',
	'THANKS_TIME_VIEW_EXPLAIN'			=> 'Если включено, в сообщении будет отображаться время благодарности.',
	'THANKS_TOP_NUMBER'					=> 'Число пользователей в топлисте',
	'THANKS_TOP_NUMBER_EXPLAIN'			=> 'Укажите число пользователей для вывода в топлисте на главной странице. 0 - отключает вывод топлиста.',
	'THANKS_TOPIC_REPUT_VIEW'			=> 'Рейтинг для тем',
	'THANKS_TOPIC_REPUT_VIEW_COLUMN'	=> 'Рейтинг для тем в колонке',
	'THANKS_TOPIC_REPUT_VIEW_COLUMN_EXPLAIN' => 'Если включено, в списке тем рейтинг тем будет отображаться в отдельной колонке. <br /> Примечание: эта опция эффективна только для стиля prosilver и только при включении опции выше.',
	'THANKS_TOPIC_REPUT_VIEW_EXPLAIN'	=> 'Если включено, в списке тем будет отображаться рейтинг для тем.',
	'TRUNCATE'							=> 'Очистить',	
	'TRUNCATE_THANKS'					=> 'Полная очистка списка благодарностей',
	'TRUNCATE_THANKS_EXPLAIN'			=> 'Данная процедура полностью очищает все счётчики благодарностей (удаляет все выданные благодарности).<br /> Действие не обратимо!',
	'TRUNCATE_THANKS_MSG'				=> 'Счётчики благодарностей очищены.',
	'ALLOW_THANKS_PM_ON'				=> 'Получать уведомления о благодарностях в ЛС',
	'ALLOW_THANKS_EMAIL_ON'				=> 'Получать уведомления о благодарностях по Email',
));
?>
