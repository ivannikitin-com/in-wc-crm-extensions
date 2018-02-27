<?php
/**
 * Класс реализует дополнительные поля для клиентов
 */
class INCRM_Customer_Extra_Fields extends INCRM_Base
{
	/**
	 * Конструктор плагина
	 */
	public function __construct( $plugin )
	{
		// Конструктор родителя
		parent::__construct( $plugin );
		
		// Показываем мета-поля в профиле пользователя
		add_action('show_user_profile', array( $this, 'showFieldsInProfile' ) );
		add_action('edit_user_profile', array( $this, 'showFieldsInProfile' ) );	
		
		// Сохраняем мета-поля в профиле пользователя
		add_action('personal_options_update', array( $this, 'saveFields' ) );
		add_action('edit_user_profile_update', array( $this, 'saveFields' ) );
		
		// Мета-бок с CRM
		add_action('add_meta_boxes_crm_customers', array( $this, 'addMetabox' ) );
	}
	
	
	/**
	 * Сохраняем мета-поля в профиле пользователя
	 */	
	public function saveFields( $userId ) 
	{ 
		if ( ! current_user_can( 'edit_user', $userId ) )
			return false;
		
		// Старые значенияч полей
		$oldFields = array(
				'tarif' 			=> get_the_author_meta('tarif', $userId ), 
				'limit' 			=> get_the_author_meta('limit', $userId ), 
				'project_manager' 	=> get_the_author_meta('project_manager', $userId ), 
			);
		
		
		// Новые значенияч полей
		$newFields = array(
				'tarif' 			=> ( isset( $_POST['tarif'] ) ) ? sanitize_text_field( $_POST['tarif'] ) : $oldFields['tarif'],
				'limit' 			=> ( isset( $_POST['limit'] ) ) ? sanitize_text_field( $_POST['limit'] ) : $oldFields['limit'],
				'project_manager' 	=> ( isset( $_POST['project_manager'] ) ) ? sanitize_text_field( $_POST['project_manager'] ) : $oldFields['project_manager'],
			);
		
		// Поля для обновления
		$fields = apply_filters( 'incrm_customer_extra_fields_update', array( 'old' => $oldFields, 'new' => $newFields ) );
		
		// Обновляем!
		update_usermeta( $userId, 'tarif', $fields['new']['tarif'] );
		update_usermeta( $userId, 'limit', $fields['new']['limit'] );
		update_usermeta( $userId, 'project_manager', $fields['new']['project_manager'] );
	}	
	
	
	
	/**
	 * Показываем мета-поля в профиле пользователя
	 */	
	public function showFieldsInProfile( $user ) 
	{ 
		// Показывае только для клиентов
		if ( ! in_array( 'customer', $user->roles ) )
			return;
	?>
	<h3><?php esc_html_e( 'Данные клиента в CRM', INCRM ) ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="tarif"><?php esc_html_e( 'Тариф', INCRM ) ?></label></th>
			<td>
				<input type="text" name="tarif" id="tarif" value="<?php echo esc_attr(get_the_author_meta('tarif', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e( 'Тариф обслуживания клиента', INCRM ) ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="limit"><?php esc_html_e( 'Лимиты', INCRM ) ?></label></th>
			<td>
				<input type="text" name="limit" id="limit" value="<?php echo esc_attr(get_the_author_meta('limit', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e( 'Лимиты текста или работ', INCRM ) ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="limit"><?php esc_html_e( 'Координатор', INCRM ) ?></label></th>
			<td>
				<input type="text" name="project_manager" id="project_manager" value="<?php echo esc_attr(get_the_author_meta('project_manager', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e( 'Координатор клиента', INCRM ) ?></span>
			</td>
		</tr>		
	</table>
	<?php 
	}
	
	
	/**
	 * Добавляет мета-бокс в CRM
	 */
	public function addMetabox( $post_type, $post )
	{
		add_meta_box( INCRM . '_extra_fields', 		// id атрибут HTML тега, контейнера блока.
					 __( 'Данные клиента', INCRM ), // Заголовок/название блока. 
					 array( $this, 'showMetabox' ),	// Функция, которая выводит на экран HTML
					 'crm_customers',				// Название экрана для которого добавляется блок.
					 'side',						// Место где должен показываться блок
					 'high');						// Приоритет блока для показа выше или ниже остальных блоков
		
	}
	
	/**
	 * Отрисовка мета-бокса
	 */
	public function showMetabox( $customer )
	{
		$userId =  $customer->user_id;
		$tarif = get_the_author_meta('tarif', $userId );
		$limit = get_the_author_meta('limit', $userId );
		$project_manager = get_the_author_meta('project_manager', $userId );
?>
	<div class="incrm-field">
		<label for="incrm-field-tarif"><?php esc_html_e( 'Тариф', INCRM ) ?></label>
		<input type="text" id="incrm-field-tarif" name="tarif" value="<?php echo $tarif ?>" /> 
	</div>
	<div class="incrm-field">
		<label for="incrm-field-limit"><?php esc_html_e( 'Лимиты', INCRM ) ?></label>
		<input type="text" id="incrm-field-limit" name="limit" value="<?php echo $limit ?>" /> 
	</div>
	<div class="incrm-field">
		<label for="incrm-field-project_manager"><?php esc_html_e( 'Координатор', INCRM ) ?></label>
		<input type="text" id="incrm-field-project_manager" name="project_manager" value="<?php echo $project_manager ?>" /> 
	</div>
<?php
		
	}
	
	
}