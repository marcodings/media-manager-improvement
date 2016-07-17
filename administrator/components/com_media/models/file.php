 * @since 3.7.0
	 * @since 3.7.0
	 * @since 3.7.0
	 * @since 3.7.0
	 * @since 3.7.0
	/**
	 * Stored files
	 *
	 * @var null
	 * @since 3.7.0
	 */
	protected $storedFiles = null;

	 * @since 3.7.0
	 * @param  string $filePath
	 * @return  self
	 * @since 3.7.0
		if (strstr($filePath, COM_MEDIA_BASE) == false)
		{
			$filePath = COM_MEDIA_BASE . '/' . $filePath;
		}

			return $this;
		$filePath = realpath($filePath);

		$mediaBase     = str_replace(DIRECTORY_SEPARATOR, '/', JPATH_ROOT . '/images/');
		return $this;
	 * @param   string $filePath
	 * @since 3.7.0
		if (empty($storedFile))
			try
				$this->id   = $this->create();
				$this->resetStoredFiles();
				$storedFile = $this->getStoredFileByPath($filePath);
			}
			catch (Exception $e)
			{
				// Do nothing
		if (empty($storedFile))
			throw new RuntimeException(JText::_('COM_MEDIA_ERROR_NO_FILE_IN_DB'));

		$this->id = $storedFile->id;

		$this->fileProperties['id']           = $this->id;
		$this->fileProperties['hash']         = $storedFile->md5sum;
		$this->fileProperties['file_adapter'] = $storedFile->adapter;

		// Check for hash to see if this entry needs updating
		if (empty($this->fileAdapter))
			return true;
		$this->fileAdapter->setFilePath($this->fileProperties['path']);

		if ($this->fileAdapter->getHash() != $this->fileProperties['hash'])
		{
			try
			{
				$this->update();
			}
			catch (Exception $e)
			{
				// Do nothing
			}
		}
	 * @param   string $filePath
	 * @since 3.7.0
		$path        = str_replace(JPATH_ROOT . '/', '', dirname($filePath));
		$filename    = basename($filePath);
		$storedFiles = $this->getStoredFiles($path);
		foreach ($storedFiles as $storedFile)
	/**
	 * Reset the listing of files stored in the database
	 *
	 * @since 3.7.0
	 */
	protected function resetStoredFiles()
	{
		$this->storedFiles = null;
	}

	 * @param   string $folder
	 * @since 3.7.0
		if (!isset($this->storedFiles[$folder]))
			$this->storedFiles[$folder] = $this->getFilesModel()
		return $this->storedFiles[$folder];
	 * @throw   RuntimeException
	 * @since   3.7.0
	public function create()
		$table->save($data);
	 * @since 3.7.0
	public function update()
	 * Delete a file
	 * @return bool
	 * @throws RuntimeException
	 * @throws Exception
	 * @since 3.7.0
	public function delete()
		if (empty($this->fileProperties))
		$fileName = $this->fileProperties['name'];
		$filePath = $this->fileProperties['path'];
		if ($fileName !== JFile::makeSafe($fileName))
			// Filename is not safe
			$filename = htmlspecialchars($fileName, ENT_COMPAT, 'UTF-8');
			throw new RuntimeException(JText::sprintf('COM_MEDIA_ERROR_UNABLE_TO_DELETE_FILE_WARNFILENAME', substr($filename, strlen(COM_MEDIA_BASE))));
		if (!is_file($filePath))
		// Trigger the onContentBeforeDelete event
		$fileObject = new JObject(array('filepath' => $filePath));
		$result     = $this->triggerEvent('onContentBeforeDelete', array('com_media.file', &$fileObject));
		if (in_array(false, $result, true))
			// There are some errors in the plugins
			$errors = $fileObject->getErrors();
			throw new Exception(JText::plural('COM_MEDIA_ERROR_BEFORE_DELETE', count($errors), implode('<br />', $errors)));
		$rt = JFile::delete($fileObject->filepath);
		// Trigger the onContentAfterDelete event.
		$this->triggerEvent('onContentAfterDelete', array('com_media.file', &$fileObject));
		return $rt;
	 * @since 3.7.0
	 * @param  string $fileAdapterName
	 * @param  string $filePath
	 * @since 3.7.0
		$adapterFactory    = new MediaModelFileAdapter;
	 * @since 3.7.0
	 * @param   mixed $fileType
	 * @since 3.7.0
	 * @since 3.7.0
	 * @param   array $properties
	 * @since 3.7.0
	/**
	 * Method to set the current file adapter
	 *
	 * @return  MediaModelFileAdapterInterfaceAdapter
	 * @since 3.7.0
	 */
	protected function loadFileAdapter()
	{
		if ($this->fileAdapter instanceof MediaModelFileAdapterInterfaceAdapter)
		{
			return $this->fileAdapter;
		}

		if (!isset($this->fileProperties['file_adapter']))
		{
			return false;
		}

		$adapterFactory    = new MediaModelFileAdapter;
		$this->fileAdapter = $adapterFactory->getFileAdapter($this->fileProperties['file_adapter']);
		$this->fileAdapter->setFilePath($this->fileProperties['path']);

		return $this->fileAdapter;
	}

	/**
	 * Method to detect which file type class to use for a specific $_file
	 *
	 * @return  MediaModelFileAdapterInterfaceAdapter
	 * @since 3.7.0
	 */
	protected function loadFileType()
	{
		if ($this->fileType instanceof MediaModelFileTypeInterface)
		{
			return $this->fileType;
		}

		if (!isset($this->fileProperties['path']))
		{
			return false;
		}

		if (!$this->fileAdapter instanceof MediaModelFileAdapterInterfaceAdapter)
		{
			$this->loadFileAdapter();
		}

		$typeFactory    = new MediaModelFileType;
		$this->fileType = $typeFactory->getFileType($this->fileProperties['path'], $this->fileAdapter);

		if (!$this->fileType instanceof MediaModelFileTypeInterface)
		{
			throw new RuntimeException(JText::_('JERROR_UNDEFINED') . ': ' . $this->fileProperties['path']);
		}

		$this->fileProperties['file_type'] = $this->fileType->getName();

		return $this->fileType;
	}

	/**
	 * Merge file type specific properties with the generic file properties
	 *
	 * @return  void
	 * @since 3.7.0
	 */
	protected function setPropertiesByFileType()
	{
		if ($this->fileType)
		{
			$properties           = $this->fileType->getProperties($this->fileProperties['path']);
			$this->fileProperties = array_merge($this->fileProperties, $properties);
		}
	}

	/**
	 * Merge file type specific properties with the generic file properties
	 *
	 * @return  void
	 * @since 3.7.0
	 */
	protected function setPropertiesByFileAdapter()
	{
		if (!$this->fileAdapter)
		{
			return;
		}

		$mimeType = $this->fileAdapter->getMimeType($this->fileProperties['path']);

		if (empty($mimeType))
		{
			return;
		}

		$this->fileProperties['mime_type'] = $mimeType;
	}

	/**
	 * Triggers the specified event
	 *
	 * @param string $eventName
	 * @param array  $eventArguments
	 *
	 * @since 3.7.0
	 */
	private function triggerEvent($eventName, $eventArguments)
	{
		JPluginHelper::importPlugin('content');
		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger($eventName, $eventArguments);
	}

	 * @since 3.7.0
	protected function getFilesModel()