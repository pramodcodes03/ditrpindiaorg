<?php
// If you want to use PECL Fileinfo for MIME types:
//if (!extension_loaded('fileinfo') && @dl('fileinfo.so')) $_ENV['MAGIC'] = '/usr/share/file/magic';
// Check if our upload file exists

// Instantiate the class
/**
 * TO handle all reqwust for S3 bucket
 */
class S3Class extends S3
{
	
	function __construct()
	{
		parent::__construct(awsAccessKey, awsSecretKey);
	}

	public function listBucket()
	{
		echo "S3::listBuckets(): ".print_r(parent::listBuckets(), 1)."\n";
	}

	public function putContent($file_path, $bucket_directory = null)
	{
		try {
			$response = parent::putObjectFile($file_path, BUCKET_NAME, $bucket_directory.baseName($file_path), S3::ACL_PUBLIC_READ);

			if (!$response) {
				throw new Exception('File upload failed', 1);
			}

			return ['status' => 'success', 'message' => 'File uploaded !!!'];
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
	}

}