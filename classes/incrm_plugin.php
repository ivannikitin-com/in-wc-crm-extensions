<?php
/**
 * Основной класс плагина
 */
class INCRM_Plugin
{
	/**
	 * Путь к папке плагина
	 * @var string
	 */  	 
	public $path;
	
	/**
	 * URL к папке плагина
	 * @var string
	 */  	 
	public $url;

	/**
	 * Модули плагина
	 * @var mixed
	 */  	 
	public $modules = array();	

	

	/**
	 * Конструктор класса
	 * @param string	$path	Путь к папке плагина
	 * @param string	$url	URL к папке плагина
	 */
	public function __construct( $path, $url )
	{
		// Инициализуем свойства
		$this->path = $path;
		$this->url = $url;
		
		// Загрузка и инициализация модулей
		$this->loadModules();
	}
	
	/**
	 * Загрузка и инициализация модулей
	 * Все модули наследуются от класса INCRM_Module
	 */
	private function loadModules( )
	{
		$moduleList = array(
			'INCRM_Clients',		// Список клиентов
		);
		foreach ( $moduleList as $moduleName )
		{
			$this->modules[ $moduleName ] = new $moduleName( $this );
		}
	}
	
}