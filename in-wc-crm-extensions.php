<?php 
/**
 * Plugin Name: IN WC-CRM Extensions
 * Plugin URI: http://in-soft.pro/soft/in-wc-crm-extensions/
 * Description: The plugin adds new features and user interface to WooCommerce Customer Relationship Manager
 * Version: 0.1
 * Author: Ivan Nikitin and partners
 * Author URI: http://ivannikitin.com
 * Text domain: in-wc-crm-extensions
 *
 * Copyright 2016 Ivan Nikitin  (email: info@ivannikitin.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Напрямую не вызываем!
if ( ! defined( 'ABSPATH' ) ) 
	die( '-1' );

// Определения плагина
define( 'INCRM', 		'in-wc-crm-extensions' );		// Название плагина и текстовый домен
define( 'INCRM_PATH', 	plugin_dir_path( __FILE__ ) );	// Путь к папке плагина
define( 'INCRM_URL', 	plugin_dir_url( __FILE__ ) );	// URL к папке плагина

// Инициализация плагина
add_action( 'init', 'incrm_init' );
function incrm_init() 
{
	// Локализация плагина
	load_plugin_textdomain( INCRM, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );		
		
	// Проверка наличия плагина wp-handsontable-core
	if ( defined( 'WP_HOT_CORE_VERSION' )) 
	{
		// Регистрируем загрузкик классов
		spl_autoload_register( 'incrm_class_loader' );
			
		// Инициализация плагина
		new INCRM_Plugin( INCRM_PATH, INCRM_URL );
	}
	else
	{
		// Предупреждающая надпись и деактивируем плагин
		add_action( 'admin_notices', 'incrm_wp_hot_core_missing' );
		deactivate_plugins( plugin_basename( __FILE__ ) );		
	}	
}

// Предупреждающая надпись
function incrm_wp_hot_core_missing() 
{
	$class = 'notice notice-error';
	$message = __( 'To use this plugin must be installed and activated <strong>wp-handsontable-core</strong>!<br/>
	<a href="https://github.com/ivannin/wp-handsontable-core" target="_blank">https://github.com/ivannin/wp-handsontable-core</a>', WCPO_TEXT_DOMAIN );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}

// Загрузкик классов
function incrm_class_loader( $class )
{
	$fileName = INCRM_PATH . 'classes/' . strtolower( $class ) . '.php';
	if ( file_exists( $fileName ) )
		include_once( $fileName );
}

// Работа при активации и деактивации
register_activation_hook( __FILE__, 'incrm_activate' );				// Активация плагина
register_deactivation_hook( __FILE__, 'incrm_deactivate' );			// Деактивация плагина

// Активация плагина
function incrm_activate()
{
	// Добавление требуемых ролей пользователей
	incrm_set_role( 'customer', 'Клиент', array(		// Роль Клиент
		'view_customer_menu',							// Может просматривать меню клиента в личном кабинете
	));
	
	incrm_set_role( 'employee', 'Сотрудник', array(		// Роль Сотрудник
		'view_employee_menu',							// Может просматривать меню сотрудника в личном кабинете
		'view_employee_report',							// Может просматривать свой отчет
		'edit_employee_report',							// Может редактировать свой отчет
	));
	
	incrm_set_role( 'head', 'Руководство', array(		// Роль Руководство
		'view_employee_menu',							// Может просматривать меню сотрудника в личном кабинете
		'view_employee_report',							// Может просматривать свой отчет
		'edit_employee_report',							// Может редактировать свой отчет
		'view_employee_reports_all',					// Может просматривать отчеты других сотрудников
		'edit_employee_reports_all',					// Может редактировать отчеты других сотрудников
	));
	
}

// Деактивация плагина
function incrm_deactivate()
{
	// Пока ничего
}

// Проверка и установка роли пользователя
// При неоьбходимости добавляет новую роль и устанавливает разрешения
function incrm_set_role( $role, $roleName, $capabilities )
{
	// Получим роль
	$userRole = get_role( $role );
	
	// Если роли нет, создадим ее
	if ( ! $userRole )
		$userRole = add_role( $role, $roleName );
	
	// Установим разрешения
	foreach ( $capabilities as $capability )
	{
		$userRole->add_cap( $capability );
	}
}
