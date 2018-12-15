<?php

use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\PasswordField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\FileField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Control\Session;
use SilverStripe\Control\HTTPRequest;

/**
 * FoundationFormPage
 * Displays a Form with various Fields to see its Foundation appearance
 *
 * @author Martijn van Nieuwenhoven <info@axyrmedia.nl>
 * @package foundationforms
 */

class FoundationFormPage extends Page {

	private static $db = array(
	);

	private static $has_one = array(
	);

}
class FoundationFormPage_Controller extends PageController {

	private static $allowed_actions = array (
		'FoundationForm'
	);

	function FoundationForm() {

		$fields = new FieldList(
			HeaderField::create('FormFieldsHeader', 'Form Fields', 3),
			// Usual suspects
			TextField::create(TextField::class, TextField::class),
			EmailField::create(EmailField::class, EmailField::class),
			PasswordField::create(PasswordField::class, PasswordField::class),
			TextareaField::create(TextareaField::class, TextareaField::class),
			FileField::create(FileField::class, FileField::class),
			// Checkboxes, Radio buttons and Dropdown
			CheckboxField::create(CheckboxField::class, CheckboxField::class),
			DropdownField::create(DropdownField::class, DropdownField::class)
				->setSource(array(
				'NZ' => 'New Zealand',
				'US' => 'United States',
				'GEM'=> 'Germany'
				))
				->setEmptyString(''),

			CheckboxsetField::create('CheckboxsetField', 'CheckboxsetField')
				->setSource(array(
					'NZ' => 'New Zealand',
					'US' => 'United States',
					'GEM'=> 'Germany'
				))
				,

			OptionsetField::create(OptionsetField::class, OptionsetField::class)
				->setSource(array(
					'NZ' => 'New Zealand',
					'US' => 'United States',
					'GEM'=> 'Germany'
				)),
			HeaderField::create('FieldGroupHeader', 'Field Groups', 3),
			// FieldGroups
			FieldGroup::create(
				TextField::create('FieldGroupTextField', TextField::class)->addExtraClass('small-6 columns'),
				TextField::create('FieldGroupTextField1', TextField::class)->addExtraClass('small-6 columns')
			),
			FieldGroup::create(
				TextField::create('FieldGroupTextField2', TextField::class)->addExtraClass('small-4 columns'),
				TextField::create('FieldGroupTextField3', TextField::class)->addExtraClass('small-4 columns'),
				TextField::create('FieldGroupTextField4', TextField::class)->addExtraClass('small-4 columns')
			),
			FieldGroup::create(
				TextField::create('FieldGroupTextField5', TextField::class)->addExtraClass('small-3 columns'),
				TextField::create('FieldGroupTextField6', TextField::class)->addExtraClass('small-3 columns'),
				TextField::create('FieldGroupTextField7', TextField::class)->addExtraClass('small-3 columns'),
				TextField::create('FieldGroupTextField8', TextField::class)->addExtraClass('small-3 columns')
			),
			FieldGroup::create(
				TextField::create('FieldGroupTextField9', TextField::class)->addExtraClass('large-2 small-4 columns'),
				TextField::create('FieldGroupTextField10', TextField::class)->addExtraClass('large-2 small-4 columns'),
				TextField::create('FieldGroupTextField11', TextField::class)->addExtraClass('large-2 small-4 columns'),
				TextField::create('FieldGroupTextField12', TextField::class)->addExtraClass('large-2 small-4 columns'),
				TextField::create('FieldGroupTextField13', TextField::class)->addExtraClass('large-2 small-4 columns'),
				TextField::create('FieldGroupTextField14', TextField::class)->addExtraClass('large-2 small-4 columns')
			),
			FieldGroup::create(
				TextField::create('FieldGroupTextField15', TextField::class)->addExtraClass('small-6 columns'),
				TextField::create('FieldGroupTextField16', TextField::class)->addExtraClass('small-4 columns'),
				TextField::create('FieldGroupTextField17', TextField::class)->addExtraClass('small-2 columns')
			),
			FieldGroup::create(
				DropdownField::create('DropdownField2', DropdownField::class)
					->setSource(array(
						'NZ' => 'New Zealand',
						'US' => 'United States',
						'GEM'=> 'Germany'
					))
					->addExtraClass('large-6 small-6 columns')
					->setEmptyString(''),
				DropdownField::create('DropdownField3', DropdownField::class)
					->setSource(array(
						'NZ' => 'New Zealand',
						'US' => 'United States',
						'GEM'=> 'Germany'
					))
					->addExtraClass('large-6 small-6 columns')
					->setEmptyString('')
			),
			HeaderField::create('SwitchFieldsHeader', 'Switch Fields', 3),
			// FoundationSwitchFields
			FoundationSwitchField::create('SwitchField', 'SwitchField', array(
				0	=> 'Off',
				1	=> 'On'
			))->addExtraClass('large-12'),
			FoundationSwitchField::create('SwitchField2', 'SwitchField', array(
				0	=> 'Off',
				1	=> 'On'
			))->addExtraClass('large round')
		);

		$actions = new FieldList(
			new FormAction('submitFoundationForm', 'Submit')
		);

		// set all to required to see the validation message appearance
		$required = array();
		if($dataFields = $fields->dataFields()) {
			foreach($dataFields as $child) {
				$required[] = $child->getName();
			}
		}

		$validator = new RequiredFields($required);

		$form = new FoundationForm($this, __FUNCTION__, $fields, $actions, $validator);

		// load submitted data, and clear them from session
		$session = $this->getRequest()->getSession();
		if($data = $session->get('FoundationForm' . $this->ID)) {
			$form->loadDataFrom($data);
			Session::clear('FoundationForm' . $this->ID);
		}
		return $form;
	}

	// submit the form and redirect back to the form
	function submitFoundationForm($data, $form) {
		if(isset($data['SecurityID'])) {
			unset($data['SecurityID']);
		}
		Session::set('FoundationForm' . $this->ID, $data);

		return $this->redirect($this->Link());
	}
}
