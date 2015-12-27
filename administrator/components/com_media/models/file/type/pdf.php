<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Media Component File Type Image Model
 */
class MediaModelFileTypePdf extends MediaModelFileTypeDefault implements MediaModelFileTypeInterface
{
	/**
	 * Name of this file type
	 *
	 * @var string
	 */
	protected $name = 'pdf';

	/**
	 * File extensions supported by this file type
	 */
	protected $extensions = array(
		'pdf',
	);

	/**
	 * MIME types supported by this file type
	 */
	protected $mimeTypes = array(
		'application/pdf',
	);

	/**
	 * Return the file properties of a specific file
	 *
	 * @param string $filePath
	 *
	 * @return array
	 */
	public function getProperties($filePath)
	{
		// @todo: Count the number of pages in the PDF
		// @todo: Detect the PDF version type

		return array();
	}
}
