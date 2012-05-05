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
 * Class ModuleDependingValueWizard 
 *
 * @copyright  Daniel Kiesel 2011 
 * @author     Daniel Kiesel 
 * @package    Controller
 */
class ModuleDependingValueWizard extends Widget
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
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'value':
				$this->varValue = deserialize($varValue);
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			case 'dependingValue_fields':
				$this->arrConfiguration['dependingValue_fields'] = deserialize($varValue);
				break;
			
			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Generate module
	 * @return string
	 */
	public function generate()
	{
		$GLOBALS['TL_CSS'][] = 'system/modules/depending_value_wizard/html/css/depending_value_wizard.css?' . TABLESORT;
		
		$this->import('Database');
		$strCommand = 'cmd_' . $this->strField;
		
		$this->saveConstruction($strCommand);
		
		
		$output = NULL;
		
		$output .= '<table cellspacing="0" cellpadding="0" id="ctrl_'.$this->strId.'" class="depending_value_wizard" summary="Depending Value wizard">';
			$output .= '<thead>';
				$output .= '<tr>';
					$output .= '<th class="label">' . $GLOBALS['TL_LANG']['MSC']['depending_value_wizard']['label'] . '</th>';
					$output .= '<th class="value">' . $GLOBALS['TL_LANG']['MSC']['depending_value_wizard']['value'] . '</th>';
				$output .= '</tr>';
			$output .= '</thead>';
			$output .= '<tbody>';
				
				foreach($this->options as $option)
				{
					if(isset($this->arrConfiguration['standardValue']) && empty($this->varValue[$option['value']])){
						$this->varValue[$option['value']] = $this->arrConfiguration['standardValue'];
					}
					
					$output .= '<tr>';
						$output .= '<td class="label">';
							$output .= $option['label'];
						$output .= '</td>';
						$output .= '<td class="value">';
							$output .= '<input type="text" name="'.$this->strId.'['.$option['value'].']" class="tl_text_2" tabindex="1" value="'.$this->varValue[$option['value']].'"' . $this->strTagEnding;
						$output .= '</td>';
					$output .= '</tr>';
				}
				
			$output .= '</tbody>';
		$output .= '</table>';
		
		return $output;
	}
	
	
	/**
	 * Save Construction
	 * @param string
	 */
	protected function saveConstruction($strCommand){
				
		// Get new value
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable)
		{
			$this->varValue = $this->Input->post($this->strId);
		}
		
		// Save the value
		if ($this->Input->get($strCommand) || $this->Input->post('FORM_SUBMIT') == $this->strTable)
		{
			/*$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);*/
			
			// Reload the page
			if (is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
			{
				$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', $this->Environment->request)));
			}
		}	
	}
}

?>