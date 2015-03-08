<?php
// Perform Elastic Transcoding Jobs
// REQUIRES:
//     1. AWS Credentials 
//     2. S3 Input/Output buckets already set up (must be on same account as Credentials)
//     3. Corresponding PipelineID set up and provided
	
include 'vendor/autoload.php';
include 'aws.php';
use Aws\ElasticTranscoder\ElasticTranscoderClient;	
	
class ElasticTranscoderJob
{
	private $input; //input file name 
	private $output; //output file name 
	private $folder; //user folder for input/output
	
	//constructor - performs conversion
	public function __construct($input, $output, $folder)
	{
		$this->input = $input;
		$this->output = $output;
		$this->folder = $folder.'/';
		
		$aws = new aws();
		$client = $aws.authElasticTranscoder();
		
		$result = $client->createJob(array(
			// PipelineId is required
			'PipelineId' => '1425787425117-1042wm',
			'Input' => array(
				'Key' => $this->folder.$input,
				'FrameRate' => 'auto',
				'Resolution' => 'auto',
				'AspectRatio' => 'auto',
				'Interlaced' => 'auto',
				'Container' => 'auto',
			),
			'Output' => array(
				'Key' => $this->folder.$output,
				'ThumbnailPattern' => '',
				'Rotate' => 'auto',
				'PresetId' => '1351620000001-300040' //128kb-s MP3 - CHANGE THIS
			)
		));
		
		echo "<br>";
		echo "FILE CONVERTED. "; //prints if conversion was successful
		echo "<br>";
	}
	
	public function __toString()
	{
		return "NULL";
	}
}
?>
