<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Daniel Kiesel 2011 
 * @author     Daniel Kiesel 
 * @package    depending_value_wizard 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class FormDependingValueWizard 
 *
 * @copyright  Daniel Kiesel 2011 
 * @author     Daniel Kiesel 
 * @package    Controller
 */
class FormDependingValueWizard extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';

	/**
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'options':
				$this->arrOptions = deserialize($varValue);
				break;
			
			case 'value':
				$this->varValue = deserialize($varValue);
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Check options
	 */
	public function validate()
	{
		$options = deserialize($this->getPost($this->strName));

		$varInput = $this->validator($options);
		
		// Add class "error"
		if ($this->hasErrors())
		{
			$this->class = 'error';
		}
		else
		{
			$this->varValue = $varInput;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		if ($this->varValue==NULL)
		{
			$this->varValue = array();
		}
		
		$strOptions = '';

		foreach ($this->arrOptions as $i=>$arrOption)
		{
			if ($this->varValue[$arrOption['value']]==NULL)
			{
				$this->varValue[$arrOption['value']] = 0;
			}
			
			$strOptions .= sprintf('<span><input type="text" name="%s" id="opt_%s" class="text" value="%s"%s%s%s <label id="lbl_%s" for="opt_%s">%s</label></span>',
									$this->strName . ((count($this->arrOptions) > 1) ? '[' . $arrOption['value'] . ']' : ''),
									$this->strId.'_'.$i,
									$this->varValue[$arrOption['value']],
									$this->isChecked($arrOption),
									$this->getAttributes(),
									$this->strTagEnding,
									$this->strId.'_'.$i,
									$this->strId.'_'.$i,
									$arrOption['label']);
		}

        return sprintf('<div id="ctrl_%s" class="depending_value_wizard_container%s"><input type="hidden" name="%s" value=""%s%s</div>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->strName,
						$this->strTagEnding,
						$strOptions) . $this->addSubmit();
	}
}

?>