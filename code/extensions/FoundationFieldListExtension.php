<?php

namespace Foundation\Extensions;

use ReflectionClass;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;
use SilverStripe\View\SSViewer;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extension;



class FoundationFieldListExtension extends Extension {

	/**
	 * A list of ignored fields that should not take on Foundation transforms
	 * @var array
	 */
	protected $ignores = array ();

	/**
	 * Transforms all fields in the FieldList to use Foundation templates
	 * @return FieldList
	 */
	public function bootstrapify() {
		foreach ($this->owner as $f) {

			$sng = Injector::inst()->get(get_class($f), true, ['dummy', '']);

			if (isset($this->ignores[$f->getName()])) continue;

            // if we have a CompositeField, bootstrapify its children
            if ($f instanceof CompositeField) {
                $f->getChildren()->bootstrapify();
                continue;
            }

			// If we have a Tabset, bootstrapify all Tabs
			if ($f instanceof TabSet) {
				$f->Tabs()->bootstrapify();
			}

			// If we have a Tab, bootstrapify all its Fields
			if ($f instanceof Tab) {
				$f->Fields()->bootstrapify();
			}

			// If the user has customised the holder template already, don't apply the default one.
			if ($sng->getFieldHolderTemplate() == $f->getFieldHolderTemplate()) {
				$class = new ReflectionClass($f);
				$template = "Foundation" . $class->getShortName() . "_holder";
				if (SSViewer::hasTemplate($template)) {
					$f->setFieldHolderTemplate($template);
				}
				else {
					$f->setFieldHolderTemplate("FoundationFieldHolder");
				}

			}

			// If the user has customised the field template already, don't apply the default one.
			if ($sng->getTemplate() == $f->getTemplate()) {
				foreach(array_reverse(ClassInfo::ancestry($f)) as $className) {
					$parts = explode('\\', $className);
					$shortName = end($parts);
					$bootstrapCandidate = "Foundation{$shortName}";
					$nativeCandidate = $className;
					if (SSViewer::hasTemplate($bootstrapCandidate)) {
						$f->setTemplate($bootstrapCandidate);
						break;
					}
					elseif (SSViewer::hasTemplate($nativeCandidate)) {
						$f->setTemplate($nativeCandidate);
						break;
					}


				}
			}
		}

		return $this->owner;
	}

	/**
	 * Adds this field as ignored. Should not take on Foundation transformation
	 *
	 * @param  string $field The name of the form field
	 * @return FieldList
	 */
	public function bootstrapIgnore($field) {
		$this->ignores[$field] = true;

		return $this->owner;
	}
}
