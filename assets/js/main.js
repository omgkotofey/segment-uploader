/*global $*/

$(document).ready(function () {

	// File Upload
	function ekUpload() {
		function Init() {

			console.log("Upload Initialised");

			var fileSelect = document.getElementById('file-upload'),
				fileDrag = document.getElementById('file-drag'),
				fileRemove = document.getElementById('file-remove');

			fileSelect.addEventListener('change', fileSelectHandler, false);
			fileRemove.addEventListener('click', removeFile, false);

			// Is XHR2 available?
			var xhr = new XMLHttpRequest();
			if (xhr.upload) {
				// File Drop
				fileDrag.addEventListener('dragover', fileDragHover, false);
				fileDrag.addEventListener('dragleave', fileDragHover, false);
				fileDrag.addEventListener('drop', fileSelectHandler, false);
			}
		}

		function removeFile(e) { 
			e.stopPropagation();
			e.preventDefault();
			reloadForm();
			
		}

		function fileDragHover(e) {
			var fileDrag = document.getElementById('file-drag');

			e.stopPropagation();
			e.preventDefault();

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
			m.innerHTML = msg;
		}

		// Form reloading
		function reloadForm(){
			document.getElementById('file-image').classList.add("hidden");
			document.getElementById('file-remove').classList.add("hidden");
			document.getElementById('error-message').classList.remove("hidden");
			document.getElementById('start').classList.remove("hidden");
			document.getElementById('response').classList.add("hidden");
			document.getElementById('file-send-btn').classList.add("hidden");
			document.getElementById("file-upload-form").reset();
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
				sendBtn = document.getElementById('file-send-btn'),
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
							sendBtn.classList.remove("hidden");
							output('MAC-адреса: ' + returnedData['mac_count'] + ' шт.');
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

		// Check for the various File API support.
		if (window.File && window.FileList && window.FileReader) {
			Init();
		} else {
			document.getElementById('file-drag').style.display = 'none';
		}
	}
	ekUpload();

});