<?php

namespace Foundation\Extensions;


use SilverStripe\Forms\Form;
use SilverStripe\SpamProtection\Extension\FormSpamProtectionExtension;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\DataExtension;
use Foundation\Forms\FoundationForm;



/**
 * Performs the FoundationFormTransformation on CommentingController
 * @author Anselm Christophersen <ac@anselm.dk>
 * @package foundationforms
 */
class FoundationCommentingController extends DataExtension {

	public function alterCommentForm(Form $form) {
		$form->Fields()->bootstrapify();
		$form->Actions()->bootstrapify();
		$form->setTemplate('FoundationCommentingControllerForm', FoundationForm::class);

		if ($form->hasExtension(FormSpamProtectionExtension::class)) {
			$form->enableSpamProtection();
		}
		Requirements::css(FOUNDATIONFORMS_DIR . '/css/foundationforms.css');
	}

}
