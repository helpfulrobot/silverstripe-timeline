<?php

/**
 * NotificationStarAction
 *
 */
class NotificationStarAction implements GridField_ColumnProvider, GridField_ActionProvider {

	/**
	 * Add a 'Star' column
	 * 
	 * @param type $gridField
	 * @param array $columns 
	 */
	public function augmentColumns($gridField, &$columns) {
		if(!in_array('Actions', $columns)) {
			$columns[] = 'Actions';
		}
	}
	
	/**
	 * Return any special attributes that will be used for FormField::create_tag()
	 *
	 * @param GridField $gridField
	 * @param DataObject $record
	 * @param string $columnName
	 * @return array
	 */
	public function getColumnAttributes($gridField, $record, $columnName) {
		return array('class' => 'col-buttons');
	}
	
	/**
	 * Add the title 
	 * 
	 * @param GridField $gridField
	 * @param string $columnName
	 * @return array
	 */
	public function getColumnMetadata($gridField, $columnName) {
		if($columnName == 'Actions') {
			return array('title' => '');
		}
	}
	
	/**
	 * Which columns are handled by this component
	 * 
	 * @param type $gridField
	 * @return string[] 
	 */
	public function getColumnsHandled($gridField) {
		return array('Actions');
	}
	
	/**
	 * Which GridField actions are this component handling
	 *
	 * @param GridField $gridField
	 * @return string[] 
	 */
	public function getActions($gridField) {
		return array('star', 'unstar');
	}
	
	/**
	 *
	 * @param GridField $gridField
	 * @param DataObject $record
	 * @param string $columnName
	 * @return string - the HTML for the column 
	 */
	public function getColumnContent($gridField, $record, $columnName) {
		if(!$record->canEdit()) return;

		$field = GridField_FormAction::create($gridField, 'star'.$record->ID, false,
				"star", array('RecordID' => $record->ID))
			->addExtraClass('gridfield-button-star')
			->setAttribute('title', _t('GridAction.Star', "Star this notice"));
			//->setAttribute('data-icon', 'chain--minus');
		
		return $field->Field();
	}
	
	/**
	 * Handle the actions and apply any changes to the GridField
	 *
	 * @param GridField $gridField
	 * @param string $actionName
	 * @param mixed $arguments
	 * @param array $data - form data
	 * @return void
	 */
	public function handleAction(GridField $gridField, $actionName, $arguments, $data) {
		if($actionName == 'deleterecord' || $actionName == 'unlinkrelation') {
			$item = $gridField->getList()->byID($arguments['RecordID']);
			if(!$item) {
				return;
			}
			
			if($actionName == 'deleterecord') {
				if(!$item->canDelete()) {
					throw new ValidationException(
						_t('GridFieldAction_Delete.DeletePermissionsFailure',"No delete permissions"),0);
				}

				$item->delete();
			} else {
				if(!$item->canEdit()) {
				throw new ValidationException(
					_t('GridFieldAction_Delete.EditPermissionsFailure',"No permission to unlink record"),0);
			}

				$gridField->getList()->remove($item);
			}
		} 
	}
	
}


