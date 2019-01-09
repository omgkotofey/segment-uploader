/*global $*/
(function($) { 
	$(document).ready(function () {
	
		// File Upload
		function SegmentUploader() {
			function Init() {
	
				
				var fileSelect = document.getElementById('file-upload'),
					fileDrag = document.getElementById('file-drag'),
					fileRemove = document.getElementById('file-remove'),
					segmentName = document.getElementById('segment-name'),
					fileSelectButton = document.getElementById('file-upload-btn');
					segmentCreateButton = document.getElementById('segment-create-btn');
				
				fileSelect.addEventListener('change', fileSelectHandler, false);
				fileDrag.addEventListener('click', removeFileDialog, false);
				fileRemove.addEventListener('click', removeFile, false);
				fileSelectButton.addEventListener('click', invokeFileFialog, false);
				segmentName.addEventListener('input', validateSegmentName, false);
				segmentCreateButton.addEventListener('click', createSegment, false);
				
				// Is XHR2 available?
				var xhr = new XMLHttpRequest();
				if (xhr.upload) {
					// File Drop
					fileDrag.addEventListener('dragover', fileDragHover, false);
					fileDrag.addEventListener('dragleave', fileDragHover, false);
					fileDrag.addEventListener('drop', fileSelectHandler, false);
				}
	
				console.log("Uploader Initialised");
			}
	
			function removeFile(e) { 
				e.stopPropagation();
				e.preventDefault();
				reloadForm();
			}
	
			function invokeFileFialog() { 
				document.getElementById('error-message').classList.add("hidden");
				document.getElementById('file-upload').click();
			}
	
			function removeFileDialog(e) { 
				e.preventDefault();
			}
	
			function fileDragHover(e) {
				var fileDrag = document.getElementById('file-drag');
				e.stopPropagation();
				e.preventDefault();
				document.getElementById('error-message').classList.add("hidden");
				fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
			}
	
			function fileSelectHandler(e) {
				// Fetch FileList object
				var files = e.target.files || e.dataTransfer.files;
	
				// Cancel event and hover styling
				fileDragHover(e);
	
				// Process all File objects
				for (var i = 0, f; f = files[i]; i++) {
					parseFile(f);
					uploadFile(f);
				}
			}
	
			// Output
			function output(msg) {
				// Response
				var m = document.getElementById('messages');
				m.innerHTML = msg;
			}
	
			// Output Error Message
			function outputError(msg) {
				// Response
				var m = document.getElementById('error-message');
				document.getElementById('error-message').classList.remove("hidden");
				m.innerHTML = '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i><br>' + msg;
	
				reloadForm();
			}
	
			// Form reloading
			function reloadForm(){
				document.getElementById('file-image').classList.add("hidden");
				document.getElementById('file-remove').classList.add("hidden");
				document.getElementById('error-message').classList.add("hidden");
				document.getElementById('start').classList.remove("hidden");
				document.getElementById('response').classList.add("hidden");
				document.getElementById('segment-send-btn').classList.add("hidden");
				document.getElementById("file-upload-form").reset();
			}
	
			function changeStep(e){
				e.target.classList.add("hidden");
				current_step = $('#file-drag');
				next_step =  $('#segment-create');
				next_step.show(); 
				current_step.animate({opacity: 0}, {
					step: function(now, mx) {
						scale = 1 - (1 - now) * 0.2;
						left = (now * 50)+"%";
						opacity = 1 - now;
						current_step.css({
					'transform': 'scale('+scale+')',
					'position': 'absolute'
				});
						next_step.css({'left': left, 'opacity': opacity});
					}, 
					duration: 800, 
					complete: function(){
						current_step.hide();
					}, 
					easing: 'easeInOutBack'
				});
			}
	
			function parseFile(file) {
	
				document.getElementById('file-name').innerHTML = '<strong>' + encodeURI(file.name) + '</strong>';
	
				var isTXT = (/\.(?=txt)/gi).test(file.name) && (file.type == 'text/plain');
				if (isTXT) {
					document.getElementById('start').classList.add("hidden");
					document.getElementById('response').classList.remove("hidden");
					document.getElementById('error-message').classList.add("hidden");
					document.getElementById('file-image').classList.remove("hidden");
					document.getElementById('file-image').src = 'assets/images/icon-txt.png';
					document.getElementById('file-remove').classList.remove("hidden");
				} else {
					outputError('К загрузке принимаются только .txt файлы');
				}
			}
	
			function setProgressMaxValue(e) {
				var pBar = document.getElementById('file-progress');
				if (e.lengthComputable) {
					pBar.max = e.total;
				}
			}
	
			function updateFileProgress(e) {
				var pBar = document.getElementById('file-progress');
	
				if (e.lengthComputable) {
					pBar.value = e.loaded;
				}
			}
	
			function uploadFile(file) {
				var pBar = document.getElementById('file-progress'),
					sendBtn = document.getElementById('segment-send-btn'),
					form = document.getElementById('file-upload-form'),
					fileSizeLimit = 5; // In MB
				if (file.size <= fileSizeLimit * 1024 * 1024) {
					pBar.style.display = 'inline';
					var form_data = new FormData();
					form_data.append('mac-file', file);
					$.ajax({
						xhr: function()
						{
							var myXhr = new window.XMLHttpRequest();
							if(myXhr.upload){
								myXhr.open('POST', form.action, true);
								myXhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
								myXhr.upload.addEventListener('progress', updateFileProgress, false);
							}
							return myXhr;
						},
						type: 'POST',
						url: form.action,
						data: form_data,
						processData: false,
						   contentType: false,
						success: function (responce) {
							returnedData = JSON.parse(responce);
							if (returnedData['result'] == 'success'){
								pBar.style.display = 'none';
								output('MAC-адреса: ' + returnedData['mac_count'] + ' шт.');
								if (returnedData['mac_count'] >= 1000) {
									sendBtn.classList.remove("hidden");
									sendBtn.addEventListener('click', changeStep, false);
									$('#segment-create').data('filename', returnedData['filename']);
								}
								else{
									outputError('Для создания сегмента файл должен содержать более 1000 уникальных MAC-адресов');
								}
							}
							else if (returnedData['result'] == 'error'){
								outputError(returnedData['message']);
							}
							else{
								outputError('Произошла неизвестная ошибка');
							}
						},
						error: function (thrownError) {
							console.log(thrownError.responseText);
							outputError('Произошла неизвестная ошибка');
						},
					});
				} 
				else{
					outputError('Объем загружаемого файла не должен превышать '+ fileSizeLimit + ' Мб).');
				}
			}

			function validateSegmentName(e) {  
				var sermentName = e.target.value;
				var re = new RegExp('^[а-яА-Яa-zA-ZёЁ0-9-]{1,30}$');
				if(re.test(sermentName)){
					document.getElementById('segment-name-invalid').classList.add("hidden");
					document.getElementById('segment-create-btn').classList.remove("disabled");
				}
				else{
					document.getElementById('segment-name-invalid').classList.remove("hidden");
					document.getElementById('segment-create-btn').classList.add("disabled");
				}
			}
	
			function createSegment() {
				var segmentName = $('#segment-name').val();
				if (segmentName !== '') {
					document.getElementById('segment-create-btn').classList.add("hidden");
					document.getElementById('segment-spinner').classList.remove("hidden");
					var mac_file_name = $('#segment-create').data('filename');
					$.ajax({
						type: 'POST',
						url: 'api_request.php',
						data: { mac_file_name: mac_file_name, segment_name: segmentName},
						success: function (responce) {
							returnedData = JSON.parse(responce);
							if (returnedData['result'] == 'success'){
								//сегмент создан
								console.log(returnedData);
							}
							else if (returnedData['result'] == 'error'){
								outputError(returnedData['message']);
							}
							else{
								outputError('Произошла неизвестная ошибка');
							}
						},
						error: function (thrownError) {
							console.log(thrownError.responseText);
							outputError('Произошла неизвестная ошибка');
						},
					});
				} 
				else{
					outputError('Невозможно создать сегмент. Укажите имя сегмента.');
				}
			}

			// Check for the various File API support.
			if (window.File && window.FileList && window.FileReader) {
				Init();
			} else {
				document.getElementById('file-drag').style.display = 'none';
			}
		}
		SegmentUploader();
	
	});
})(jQuery)