<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  MediaEditor.Rename
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Class PlgMediaEditorRename
 */
class PlgMediaEditorRename extends JPlugin
{
	/**
	 * Load the application automatically
	 */
	protected $app;

	/**
	 * Load the language file automatically
	 *
	 * @var bool
	 */
	protected $autoloadLanguage = true;

	/**
	 * Method to check whether this media editor plugin is allowed on a specific fileType
	 *
	 * @param $fileType string
	 *
	 * @return bool
	 */
	public function onMediaEditorAllowed($fileType)
	{
		return true;
	}

	/**
	 * Method to return the button label of this plugin
	 *
	 * @return string
	 */
	public function onMediaEditorButtonLabel()
	{
        $doc = JFactory::getDocument();
        $doc->addStyleDeclaration('.icon-rename:before { content: "\2a"; }');

		return JText::_('PLG_MEDIA-EDITOR_RENAME_BUTTON_LABEL');
	}

	/**
	 * Method to return the HTML shown in a modal popup within the Media Manager
	 *
	 * @param $filePath string
	 *
	 * @return string
	 */
	public function onMediaEditorDisplay($filePath)
	{
		$data   = array('filePath' => $filePath);
		$layout = new JLayoutFile('form', __DIR__ . '/layout');

		return $layout->render($data);
	}

	/**
	 * Method to process the given file
	 *
	 * @param $filePath string
	 *
	 * @return false|string
	 */
	public function onMediaEditorProcess($filePath)
	{
		// Calculate the right variables
		$newFile = $this->app->input->getFile('toFile');

		$folder      = dirname($filePath);
		$newFilePath = $folder . '/' . $newFile;

		if ($newFilePath == $filePath)
		{
			return false;
		}
        
        if (file_exists($newFilePath))
        {
            throw new InvalidArgumentException(JText::_('COM_MEDIA_ERROR_FILE_EXISTS'));
        }

		// Rename the file
		// @todo: Do this renaming with support for FlySystem?
		rename($filePath, $newFilePath);

        $returnPath = str_replace(COM_MEDIA_BASE, '', $newFilePath);

		// Return the new URL
		return JRoute::_('index.php?option=com_media&view=file&view=file&file=' . $returnPath, false);
	}
}
