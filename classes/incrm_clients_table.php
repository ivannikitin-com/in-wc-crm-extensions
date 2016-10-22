<?php
/**
 * Класс реализующий вывод таблицы клиентов
 */
class INCRM_Clients_Table extends WP_HOT_Core
{
    /**
     * Конструктор класса
	 *
     */
    public function __construct(  )
    {
        parent::__construct();
		
        // Обработчик, который формирует список пунктов самовывоза для предзагрузки
		$this->loadHandler = 'INCRM_Clients_Table::getClients';
    }



    /**
     * Метод формирует JSON код настроек таблицы
		*
     * @return string   Сформированный JSON код
     */        
    protected function getTableOptions()
    {   
        // Названия колонок
		$id 			= __( 'ID', INCRM );				// ID в БД
		$client_id		= __( 'Client ID', INCRM );			// ID клиента (наш)
		$company 		= __( 'Company', INCRM );			// Компания клиента
		$name 			= __( 'Name', INCRM );				// Имя клиента
		$tarif			= __( 'Tariff', INCRM );			// Тариф
		$limit 			= __( 'Limit', INCRM );				// Лимиты
		$phone			= __( 'Phone', INCRM );				// Телефон
		$email			= __( 'E-mail', INCRM );			// E-mail
		$address 		= __( 'Address', INCRM );			// Адрес
		$coordinator	= __( 'Coordinator', INCRM );		// Координатор

		
		return "{
			minSpareRows: 1,
			colHeaders: ['$id', '$client_id', '$company', '$name', '$tarif', '$limit', '$phone', '$email', '$address', '$coordinator'],
			columns: [
				{ data: 'id', 			type: 'numeric', 	readOnly: true },
				{ data: 'client_id',	type: 'text' },			
				{ data: 'company',		type: 'text' },		
				{ data: 'name',			type: 'text' },
				{ data: 'tarif',		type: 'text' },
				{ data: 'limit',		type: 'text' },
				{ data: 'phone',		type: 'text' },
				{ data: 'email',		type: 'text' },
				{ data: 'address',		type: 'text' },
				{ data: 'coordinator',	type: 'text' }		
			],
			manualColumnResize: true,
			// stretchH: 'all',
			rowHeaders: true,
			search: true
		}";
    }
	
	/** 
	 * Получение списка клиентов
	 * Метод статичный, потому что вызывается Аяксом
	 */
    public static function getClients() 
    {
		$clients = array();
		
		//  Параметры запроса
		$args = array (
			'role'           => 'customer',
			'order'          => 'ASC',
			'orderby'        => 'display_name',
		);
		
		// Запрос
		$user_query = new WP_User_Query( $args );
		
		// Проходим по результатам
		if ( ! empty( $user_query->results ) ) 
		{
			foreach ( $user_query->results as $user ) 
			{
				$clients[] = array(
					'id'			=> $user->ID,
					'client_id'		=> get_user_meta($user->ID, 'customerID', true),
					'company'		=> get_user_meta($user->ID, 'billing_company', true),
					'name'			=> $user->display_name,
					'tarif'			=> get_user_meta($user->ID, 'tarif', true),
					'limit'			=> get_user_meta($user->ID, 'limit', true),
					'phone'			=> get_user_meta($user->ID, 'billing_phone', true),
					'email'			=> $user->user_email,
					'address'		=> self::getUserAddress( $user->ID ),
					'coordinator'	=> self::getUserData( get_user_meta($user->ID, 'customer_agent', true), 'display_name' ),
				);
			}
		}	

		return $clients;
	}
	
	/** 
	 * Получение данных о польхователе
	 * Метод статичный, потому что вызывается Аяксом
	 * 
	 * @param int 		$userId		ID пользователя WordPress
	 * @param string 	$field		Поле, которое требуется, если нет - ищются ключи
	 * @param string 	$metaKey	Ключ, который требуется, если нет - возвращается объект
	 */
    public static function getUserData( $userId, $field = '', $metaKey='' ) 
    {
		$user = get_userdata( $userId );
		
		if ( ! $user )
			return null;
		
		if ( ! empty( $field ) )
			return $user->$field;
		
		if ( ! empty( $metaKey ) )
			return $get_user_meta($userId, $metaKey, true);

		return $user;
	}	
	
	
	/** 
	 * Получение адреса пользователя
	 * Метод статичный, потому что вызывается Аяксом
	 * 
	 * @param int 	$userId		ID пользователя WordPress
	 */
    public static function getUserAddress( $userId ) 
    {
		// Читаем адрес доставки WC
		$shippingAddr = get_user_meta($userId, 'shipping_address_1', true);
		
		// Если он есть, дополняем его городом, индексом и возвращаем
		if ( ! empty( $shippingAddr ) )
		{
			$zip 		= get_user_meta($userId, 'shipping_postcode', true);
			$country 	= get_user_meta($userId, 'shipping_country', true);
			$state 		= get_user_meta($userId, 'shipping_state', true);
			$city 		= get_user_meta($userId, 'shipping_city', true);
			$addr2 		= get_user_meta($userId, 'shipping_address_2', true);
			
			// Собираем строку адреса
			$addr = ( ! empty( $zip ) ) 		? $zip . ', ' 		: '';
			$addr .= ( ! empty( $country ) ) 	? $country . ', ' 	: '';
			$addr .= ( ! empty( $state ) ) 		? $state . ', ' 	: '';
			$addr .= ( ! empty( $city ) ) 		? $city . ', ' 		: '';
			$addr .= $shippingAddr;
			$addr .= ( ! empty( $addr2 ) ) 		? ', ' . $addr2 	: '';
			
			// Возвращаем адрес
			return $addr;
		}
		
		// Читаем адрес оплаты WC
		$billingAddr = get_user_meta($userId, 'billing_address_1', true);
		
		// Если он есть, дополняем его городом, индексом и возвращаем
		if ( ! empty( $billingAddr ) )
		{
			$zip 		= get_user_meta($userId, 'billing_postcode', true);
			$country 	= get_user_meta($userId, 'billing_country', true);
			$state 		= get_user_meta($userId, 'billing_state', true);
			$city 		= get_user_meta($userId, 'billing_city', true);
			$addr2 		= get_user_meta($userId, 'billing_address_2', true);
			
			// Собираем строку адреса
			$addr = ( ! empty( $zip ) ) 		? $zip . ', ' 		: '';
			$addr .= ( ! empty( $country ) ) 	? $country . ', ' 	: '';
			$addr .= ( ! empty( $state ) ) 		? $state . ', ' 	: '';
			$addr .= ( ! empty( $city ) ) 		? $city . ', ' 		: '';
			$addr .= $billingAddr;
			$addr .= ( ! empty( $addr2 ) ) 		? ', ' . $addr2 	: '';
			
			// Возвращаем адрес
			return $addr;
		}

		// Адреса нет
		return '';
	}	
}