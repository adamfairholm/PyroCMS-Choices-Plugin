<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PyroCMS Choices Plugin
 *
 * This is a simple plugin that allows you to loop through the
 * choices that have been set up in a choices field type.
 *
 * @author		Adam Fairholm
 * @package		PyroCMS\Addon\Plugins
 * @copyright	Copyright (c) 2013, Adam Fairholm
 * @link 		https://github.com/adamfairholm/PyroCMS-Parse-Tags-Plugin
 * @license 	MIT
 */
class Plugin_Choices extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Choices',
	);
	public $description = array(
		'en' => 'Access choice field type data as an array.'
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer 
	 * to the Blog plugin for a larger example
	 *
	 * @todo fill the  array with details about this plugin, then uncomment the return value.
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'cycle' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Cycle through choices field type options.'
				),
				'single' => false,
				'double' => true,
				'variables' => 'key|value',
				'attributes' => array(
					'field_slug' => array(
						'type' => 'slug',
						'required' => true,
					),
					'field_namespace' => array(
						'type' => 'slug',
						'required' => true,
					),
				),
			)
		);
	
		return $info;
	}

	/**
	 * Cycle
	 *
	 * Cycle through choices field type options
	 *
	 * Usage:
	 *
	 * {{ choices:cycle field_slug="my_field_slug" field_namespace="my_field_namespace" }}
	 * 		{{ key }} {{ value }}
	 * {{ /choices:cycle }}
	 *
	 * @return string
	 */
	public function cycle()
	{
		$field_slug = $this->attribute('field_slug');
		$field_namespace = $this->attribute('field_namespace');

		// If we don't have both of these, we can't go on.
		if ( ! $field_slug and ! $field_namespace) {
			return null;
		}


		// Get options
		$field = $this->db
						->limit(1)
						->where('field_slug', $field_slug)
						->where('field_namespace', $field_namespace)
						->get('data_fields')->row();

		if ( ! $field) {
			return null;
		}

		$field_data = @unserialize($field->field_data);

		if ( ! isset($field_data['choice_data'])) {
			return null;
		}

		// Break up the choice data into an array into an array
		$lines = explode("\n", $field_data['choice_data']);

		$choices = array();
		
		foreach ($lines as $line) 
		{
			$bits = explode(' : ', $line, 2);

			$key_bit = trim($bits[0]);
		
			if (count($bits) == 1)
			{
				$choices[] = array('key' => $key_bit, 'value' => lang_label($key_bit));
			}
			else
			{
				$choices[] = array('key' => $key_bit, 'value' => lang_label(trim($bits[1])));
			}
		}

		return $choices;
	}

}