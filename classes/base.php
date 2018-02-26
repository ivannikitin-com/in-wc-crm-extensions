<?php
/**
 * Базовый клксс для компонентов плагина
 */
class INCRM_Base
{
	/**
	 * Ссылка на класс плагина
	 */
	protected $plugin;
	
	/**
	 * Конструктор плагина
	 */
	public function __construct( $plugin )
	{
		// Сохраняем ссылку на плагин
		$this->plugin = $plugin;
	}
}