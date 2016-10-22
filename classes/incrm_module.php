<?php
/**
 * Базовый класс модуля
 */
class INCRM_Module
{
	/**
	 * Ссылка на класс плагина для доступа к общим свойствам
	 * @var INCRM_Plugin
	 */
	 protected $plugin;
	 
	 /**
	  * Конструктор
	  * @param INCRM_Plugin	$plugin	Ссылка на класс плагина для доступа к общим свойствам
	  */
	 public function __construct( $plugin )
	 {
		// Ссылка на плагин
		$this->plugin = $plugin;
		
		// Инициализация админки
		if ( is_admin() )
			$this->adminInit();
	 }
	 
	/**
	 * Инициализация шорткодов
	 * @param mixed	$shortcodes Ассоциаливный массив шорткодов: tag => метод, который его реализует
	 */
	protected function shortcodeInit( $shortcodes = array() )
	{
		foreach( $shortcodes as $tag => $method )
		{
			add_shortcode( $tag , array( $this, $method ) );
		}
	}
	
	/**
	 * Инициализация админки
	 */
	protected function adminInit()
	{
		// Nothing
	}	
}