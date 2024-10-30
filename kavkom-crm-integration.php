<?php
/**
 * Plugin Name: Kavkom CRM Integration
 * Description: Allows you to add leads to Kavkom CRM straight from contact form on your website | To use Kavkom CRM Integration <a href="https://contactform7.com/">Contact Form 7</a> plugin is required.
 * Version: 0.1
 * Author: Kavkom
 * Author URI: http://www.kavkom.com
 */


add_action('admin_menu', 'kavkom_crm_leads_options_page');

function kavkom_crm_leads_options_page() {
    add_menu_page(
		__( 'CRM Leads', 'kavkom' ), 
		__( 'CRM Leads', 'kavkom' ), 
		'manage_options', 
		'crm-leads', 
		'kavkom_crm_leads_page_content',
		plugins_url('images/icon_kavkom.png', __FILE__ ),
		100
	);
}

function kavkom_crm_leads_page_content() {
	?>
        <div class="wrap">
			<h1>
				<?php esc_html_e('Manage leads in CRM', 'my-plugin-crm-leads'); ?>
			</h1>

			<?php 
				$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'settings';
			?>
			<nav class="nav-tab-wrapper">
				<a href="?page=crm-leads&tab=settings" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>">Settings</a>
				<a href="?page=crm-leads&tab=form_code" class="nav-tab <?php if($tab==='form_code'):?>nav-tab-active<?php endif; ?>">Form code</a>
			</nav>
			<div class="tab-content">
				<?php switch($tab) :
				case 'settings':
					echo '<form action="options.php" method="post">';
					
						settings_fields( "kavkom_crm_leads_settings" ); 
						do_settings_sections( "crm-leads" ); 
					
					echo '<input name="submit" class="button button-primary" type="submit" value="Save" />
				</form>';
					break;
				case 'form_code':
					echo '<h2>Form code</h2>
						<p>To use Kavkom CRM Integration <a href="https://contactform7.com/">Contact Form 7</a> plugin is required. <br>Add code below as your contact form in <a href="https://contactform7.com/">Contact Form 7</a> plugin.</p>
						<p>To make the plugin work with your CRM you should make your custom fields system names according to this:</p>
						<ul>
							<li>First name: first_name</li>
							<li>Last name: last_name</li>
							<li>E-mail: email</li>
							<li>Phone number: phone_number</li>
							<li>Company: company_name</li>
							<li>Country: country</li>
							<li>Number of agents: numberofagents</li>
							<li>Description: description</li>
						</ul>
						<textarea style="width: 700px; height: 600px;" disabled>
							<label>First name
								[text* first-name placeholder "First name"]
							</label>
							<label>Last name
								[text* last-name placeholder "Last name"]
							</label>
							<label> Occupation
								[text* occupation placeholder "Occupation"]
							</label>
							<label> E-mail
								[email* email placeholder "E-mail"]
							</label>
							<label> Phone number
								[text* phone-number placeholder "Phone number"]
							</label>
							<label> Company
							[text* Company placeholder "Company"]
							</label>
							<label> Country
							[text* Country placeholder "Country"]
							</label>
							<label> Number of agents
							[number* Numberofagents placeholder "Number of agents"]
							</label>
							<label> Questions / Comments
							[textarea* comments class:form-control class:input-form placeholder "Questions / Comments"]
							</label>
							[submit "Submit"]
						</textarea>';
					break;
				endswitch; ?>
				</div>
		</div>
	<?php
}


function kavkom_crm_leads_register_settings(){
	register_setting('kavkom_crm_leads_settings', 'kavkom_crm_leads_settings_options', 'kavkom_crm_leads_validate');
    add_settings_section('kavkom_crm_settings', 'CRM settings', 'kavkom_crm_settings_section_text', 'crm-leads');

    add_settings_field( 'kavkom_crm_leads_settings_api_key', 'API Key', 'kavkom_crm_leads_settings_api_key', 'crm-leads', 'kavkom_crm_settings');
    add_settings_field( 'kavkom_crm_leads_settings_api_path', 'API Path', 'kavkom_crm_leads_settings_api_path', 'crm-leads', 'kavkom_crm_settings');
    add_settings_field( 'kavkom_crm_leads_settings_domain_uuid', 'Domain UUID', 'kavkom_crm_leads_settings_domain_uuid', 'crm-leads', 'kavkom_crm_settings');
    add_settings_field( 'kavkom_crm_leads_settings_module_id', 'Module ID', 'kavkom_crm_leads_settings_module_id', 'crm-leads', 'kavkom_crm_settings');
    add_settings_field( 'kavkom_crm_leads_settings_tag_id', 'Tag ID', 'kavkom_crm_leads_settings_tag_id', 'crm-leads', 'kavkom_crm_settings');
}
add_action('admin_init',  'kavkom_crm_leads_register_settings');

function kavkom_crm_leads_update_api_path(){
	$options = get_option( 'kavkom_crm_leads_settings_options' );
	if(empty($options['api_path'])) {
		$api_path = ['api_path' => 'https://api.kavkom.com'];
		update_option('kavkom_crm_leads_settings_options', $api_path);
	}
}
add_action('admin_init',  'kavkom_crm_leads_update_api_path');


function kavkom_crm_leads_validate( $input ) {
	$options = get_option('kavkom_crm_leads_settings_options');
	foreach ($input as $key => $value){
		$options[$key] = $value;
	}
	return $options;
}

function kavkom_crm_settings_section_text() {
    echo '<p>Here you can set all the necessary variables for connecting website with the Kavkom CRM</p>';
}

function kavkom_crm_leads_settings_api_key() {
    $options = get_option( 'kavkom_crm_leads_settings_options' );
    echo "<input id='kavkom_crm_leads_settings_api_key' name='kavkom_crm_leads_settings_options[api_key]' type='text' value='" . esc_attr( $options['api_key'] ) . "' /><label for='kavkom_crm_leads_settings_api_key'><br/><span style='display:block; margin-top: 5px;'>API Key to connect to Kavkom API.</span></label>";
}

function kavkom_crm_leads_settings_api_path() {
    $options = get_option( 'kavkom_crm_leads_settings_options' );
    echo "<input id='kavkom_crm_leads_settings_api_path' name='kavkom_crm_leads_settings_options[api_path]' type='text' value='" . esc_attr( $options['api_path'] ) . "' /><label for='kavkom_crm_leads_settings_api_path'><br/><span style='display:block; margin-top: 5px;'>Path to Kavkom API.</span></label>";
}

function kavkom_crm_leads_settings_domain_uuid() {
    $options = get_option( 'kavkom_crm_leads_settings_options' );
    echo "<input id='kavkom_crm_leads_settings_domain_uuid' name='kavkom_crm_leads_settings_options[domain_uuid]' type='text' value='" . esc_attr( $options['domain_uuid'] ) . "' /><label for='kavkom_crm_leads_settings_domain_uuid'><br/><span style='display:block; margin-top: 5px;'>Uuid of domain where all the leads will be stored.</span></label>";
}

function kavkom_crm_leads_settings_module_id() {
    $options = get_option( 'kavkom_crm_leads_settings_options' );
    echo "<input id='kavkom_crm_leads_settings_module_id' name='kavkom_crm_leads_settings_options[module_id]' type='text' value='" . esc_attr( $options['module_id'] ) . "' /><label for='kavkom_crm_leads_settings_module_id'><br/><span style='display:block; margin-top: 5px;'>ID of module where all the leads will be stored.</span></label>";
}

function kavkom_crm_leads_settings_tag_id() {
    $options = get_option( 'kavkom_crm_leads_settings_options' );
    echo "<input id='kavkom_crm_leads_settings_tag_id' name='kavkom_crm_leads_settings_options[tag_id]' type='text' value='" . esc_attr( $options['tag_id'] ) . "' /><label for='kavkom_crm_leads_settings_tag_id'><br/><span style='display:block; margin-top: 5px;'>ID of tag which will be assigned to newly created lead.</span></label>";
}

// save lead from contact form to crm
add_action('wpcf7_before_send_mail','kavkomcrm_new_lead_registration',10,1);
function kavkomcrm_new_lead_registration() {
	$options = get_option('kavkom_crm_leads_settings_options');
	if(isset($options['api_key']) && isset($options['api_path']) && isset($options['domain_uuid']) && isset($options['module_id']) && isset($options['tag_id'])) {
		$submission = WPCF7_Submission::get_instance();
		$data = $submission->get_posted_data();
		// get fields from api 
		$url = $options['api_path'] . '/api/crm/v1/module/get';
		$args = array(
			'method'      => 'GET',
			'timeout'     => 45,
			'sslverify'   => false,
			'headers'     => array(
				'X-API-TOKEN' => $options['api_key']
			),
			'body' => array(
				'domain_uuid' => $options['domain_uuid'],
				'id' => $options['module_id']
			)
		);
		$fieldsList = wp_remote_post($url, $args);
		if (is_wp_error($fieldsList)) {
			error_log(print_r($fieldsList, true));
		}
	
		$module = json_decode(wp_remote_retrieve_body($fieldsList));
		$field_values = [];
		foreach($module->data->fields as $field) {
			if ($field->system_name == 'first_name') {
				$field_values[$field->id] = [
					'value' => sanitize_text_field($data['first-name'])
				];
			}
			if ($field->system_name == 'last_name') {
				$field_values[$field->id] = [
					'value' => sanitize_text_field($data['last-name'])
				];
			}
			if ($field->system_name == 'email') {
				$field_values[$field->id] = [
					'value' => sanitize_email($data['email'])
				];
			}
			if ($field->system_name == 'phone_number') {
				$field_values[$field->id] = [
					'value' => sanitize_text_field($data['phone-number'])
				];
			}
			if ($field->system_name == 'company_name') {
				$field_values[$field->id] = [
					'value' => sanitize_text_field($data['Company'])
				];
			}
			if ($field->system_name == 'country') {
				$field_values[$field->id] = [
					'value' => sanitize_text_field($data['Country'])
				];
			}
			if ($field->system_name == 'numberofagents') {
				$field_values[$field->id] = [
					'value' => sanitize_text_field($data['Numberofagents'])
				];
			}
			if ($field->system_name == 'description') {
				$field_values[$field->id] = [
					'value' => sanitize_text_field($data['comments'])
				];
			}
		}

		// store lead in crm 
		$url  = $options['api_path'] . '/api/crm/v1/lead/store';
		$body = [
			'domain_uuid' => $options['domain_uuid'],
			'lead' => array(
				'module_id' => $options['module_id'],
				'field_values' => $field_values
			)
		];
		$args = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'sslverify'   => false,
			'headers'     => array(
				'X-API-TOKEN' => $options['api_key']
			),
			'body' =>  $body
		);
	
		$request = wp_remote_post($url, $args);
		if (is_wp_error($request)) {
			error_log(print_r($request, true));
		} 
		// assign tag
		$lead = json_decode(wp_remote_retrieve_body($request));
		$url  = $options['api_path'] . '/api/crm/v1/tags/assign';
		$body = [
			'lead_id' => $lead->data->id,
			'tags' => array(
				'0' => $options['tag_id']
			)
		];
		$args = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'sslverify'   => false,
			'headers'     => array(
				'X-API-TOKEN' => $options['api_key']
			),
			'body' =>  $body
		);
		$response = wp_remote_post($url, $args);
		if (is_wp_error($response)) {
			error_log(print_r($response, true));
		} 
	} 
}