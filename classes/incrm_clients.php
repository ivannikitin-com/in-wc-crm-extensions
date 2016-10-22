<?php
/**
 * Класс реализует вывод и работу со списком клиентов
 */
class INCRM_Clients extends INCRM_Module
{
	/**
	 * Объект HOT таблицы, реализующий клиентов
	 * @var INCRM_Clients_Table
	 */
	private $table;
		
	/**
	 * Конструктор
	 * @param INCRM_Plugin	$plugin	Ссылка на класс плагина для доступа к общим свойствам
	 */
	public function __construct( $plugin )
	{
		// Родительский конструктор
		parent::__construct( $plugin );
		
		// Инициализация таблицы
		$this->table = new INCRM_Clients_Table();
		
		// Шорткоды
		$this->shortcodeInit( array(
			'incrm_clients'	=> 'renderClientList',
		));
		
	}




	
	/**
	 * Вывод списка клиентов
	 * @param mixed	$atts	Атрибуты шорткода
	 */
	public function renderClientList( $atts )
	{
		// Получаем атрибуты
		extract( shortcode_atts( array(
			'title' => __( 'Clients', INCRM ),
			), $atts ) );
		
		$output = '<div class="' . get_class( $this ). '">';
		
		// Заголовок
		if ( ! empty( $title ))
			$output .= '<h2>' . $title . '</h2>';
		
		// Вывод таблицы
		$output .= $this->table;
		
		
		// Конец вывода
		$output .= '</div>';
		
		// Возврат HTML
		return $output;
	}	
}