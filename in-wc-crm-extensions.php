<?php 
/**
 * Plugin Name: IN WC-CRM Extensions
 * Plugin URI: http://in-soft.pro/soft/in-wc-crm-extensions/
 * Description: The plugin adds new features and user interface to WooCommerce Customer Relationship Manager
 * Version: 2.0
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

// Класс плагина
class INCRM_Plugin
{
	/**
	 * Путь к папке плагина
	 */
	public $path;
	
	/**
	 * URL к папке плагина
	 */
	public $url;
	
	/**
	 * Конструктор плашина
	 */
	public function __construct()
	{
		// Инициализация свойств
		$this->path = plugin_dir_path( __FILE__ );
		$this->url = plugin_dir_url( __FILE__ );
		
		// Автозагрузка классов
		spl_autoload_register( array( $this, 'autoload' ) );
		
		// Хуки
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init', array( $this, 'init' ) );
	}
	
    /**
     * Автозагрузка лассов по требованию
     *
     * @param string $class Требуемый класс
     */
    function autoload( $class ) 
	{
        $classPrefix = 'INCRM_';
	
		// Если это не наш класс, ничего не делаем...
		if ( strpos( $class, $classPrefix ) === false ) 
			return;
		

		$fileName   = $this->path . 'classes/' . strtolower( str_replace( $classPrefix, '', $class ) ) . '.php';
		if ( file_exists( $fileName ) ) 
		{
			require_once $fileName;
		}
    }	
	
	/**
	 * Плагины загружены
	 */
	public function plugins_loaded()
	{
		// Локализация
		load_plugin_textdomain( INCRM, false, basename( dirname( __FILE__ ) ) . '/lang' );
	}	
	
	
	/**
	 * Дополнительные поля клиентов
	 */
	public $customerExtraFields;
	
	
	/**
	 * Инициализация плагина
	 */
	public function init()
	{
		$this->customerExtraFields = new INCRM_Customer_Extra_Fields( $this );		// Дополнительные поля клиентов
	}	
	
	
	
}

// Запуск плагина
new INCRM_Plugin();