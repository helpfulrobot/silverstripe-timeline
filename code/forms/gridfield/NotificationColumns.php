<?php
/**
 * Overriden from GridFieldDataColumns so that it doesn't escape HTML content 
 * and marks the Notification as read when it's displayed.
 * 
 * @package timeline
 */
class NotificationColumns extends GridFieldDataColumns {

	/**
	 * HTML for the column, content of the <td> element.
	 * 
	 * Overriden from GridFieldDataColumns so that it doesn't escape HTML content
	 * 
	 * @param  GridField
	 * @param  DataObject - Record displayed in this row
	 * @param  string 
	 * @return string HTML for the column. Return NULL to skip.
	 */
	public function getColumnContent($gridField, $record, $columnName) {
		// Find the data column for the given named column
		$columns = $this->getDisplayFields($gridField);
		$columnInfo = $columns[$columnName];
		
		// Allow callbacks
		if(is_array($columnInfo) && isset($columnInfo['callback'])) {
			$method = $columnInfo['callback'];
			$value = $method($record);
		
		// This supports simple FieldName syntax
		} else {
			$value = $gridField->getDataFieldValue($record, $columnName);
		}
		
		// Make any formatting tweaks
		$value = $this->formatValue($gridField, $record, $columnName, $value);
		// Do any final escaping
		$value = $this->escapeValue($gridField, $value);
		
		// Mark record as read
		$record->markAsRead();
		return $value;
	}
}
