<?php

	use \Classes\Config;
	use \Classes\MacFile;
	use \Classes\AudienceApi;

	function main(){
		//будем открывать файл mac.txt
		$mac_filename = "uploads/mac.txt";
		
		// Здесь будет обработка mac-адресов

		$responce_upload = upload_file_to_api();
		$new_segment_id =  $responce_upload['segment']['id'];
		$responce_confirm = confirm_segment($new_segment_i, uniqid());
		print_r($responce);
	}

	main();

?>