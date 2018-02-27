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
				<input type="text" name="limit" id="limit" value="<?php echo esc_attr(get_the_author_meta('project_manager', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e( 'Координатор клиента', INCRM ) ?></span>
			</td>
		</tr>		
	</table>
	<?php 
	}	
}