window.onload = function () {
	let contador = document.querySelector('#preview').value,
		classImage = document.getElementsByClassName('delete'),
		myModal = new bootstrap.Modal(document.getElementById("alertModal"), {}),
		limite = 300;
	document.querySelector('.caracteres').innerHTML = limite - contador.length;
	document.querySelector('#preview').addEventListener('input', function () {
		var caracteresDigitados = this.value.length;
		var caracteresRestantes = limite - caracteresDigitados;
		document.querySelector('.caracteres').innerHTML = caracteresRestantes;
	})
	const $ = document.querySelector.bind(document);
	const previewImg = $('.preview-img');
	const fileChooser = $('.file-chooser');
	const fileButton = $('.file-button');
	fileButton.onclick = () => fileChooser.click();
	fileChooser.onchange = e => {
		const fileToUpload = e.target.files.item(0);
		extArquivo = fileToUpload.type.split('/').pop(),
			extPermitidas = ['jpg', 'png', 'jpeg', 'jpe'];
		if (typeof extPermitidas.find(function (ext) { return extArquivo == ext; }) == 'undefined') {
			document.querySelector('.modal-body').innerHTML = "<p class='error'>Tipo de arquivo inválido, só são aceitas imagens do tipo: <br> <b>jpg</b>, <b>jpe</b>, <b>jpeg</b> e <b>png</b>!</p>";
			document.getElementById('confirmar').style.display = 'none';
			myModal.show();
		} else {
			const reader = new FileReader();
			reader.onload = e => previewImg.src = e.target.result;
			reader.readAsDataURL(fileToUpload);
		}
	};

	const fileCarouselChooser = $('.file-carousel-chooser');
	const fileCarouselButton = $('.file-carousel-button');

	fileCarouselButton.onclick = () => fileCarouselChooser.click();
	fileCarouselChooser.onchange = e => {
		const fileCarouselToUpload = e.target.files.item(0);
		if (fileCarouselToUpload) {
			extArquivo = fileCarouselToUpload.type.split('/').pop(),
				extPermitidas = ['jpg', 'png', 'jpeg', 'jpe'];
			if (typeof extPermitidas.find(function (ext) { return extArquivo == ext; }) == 'undefined') {
				document.querySelector('.modal-body').innerHTML = "<p class='error'>Tipo de arquivo inválido, só são aceitas imagens do tipo: <br> <b>jpg</b>, <b>jpe</b>, <b>jpeg</b> e <b>png</b>!</p>";
				document.getElementById('confirmar').style.display = 'none';
				myModal.show();
			} else {
				let avatar = document.getElementsByClassName('image_error');
				Object.keys(avatar).forEach(item => avatar[item].style.visibility = 'hidden');
				document.querySelector('.image-value-carousel').innerHTML = "Imagem pronta para ser carregada!"
			}
		} else {
			document.querySelector('.image-value-carousel').innerHTML = "Selecionar Imagem";
		}
	};

	Array.from(classImage).forEach(function (item) {
		item.addEventListener('click', function (e) {
			e.preventDefault();

			let confirmar = document.getElementById("confirmar");

			document.querySelector(".modal-body").innerHTML = "Tem certeza que deseja apagar essa imagem?</br> Essa ação não pode ser desfeita!";
			confirmar.dataset.id = this.dataset.id;

			myModal.show();

		});
	});

	document.getElementById("confirmar").addEventListener('click', function (e) {
		myModal.hide();
		document.getElementById('delete-value').value = this.dataset.id;
		document.getElementById('delete-image').submit();
	});

	var toolbarOptions =
		[
			['bold', 'italic', 'underline', 'strike'],
			[{ 'header': 1 }],
			[{ 'list': 'ordered' }, { 'list': 'bullet' }],
			[{ 'size': ['small', false, 'large'] }],
			[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
			[{ 'align': [] }],
			[{ 'color': [] }],
			['link', 'image'],
		];

	let quill = new Quill('#quill-editor', {
		modules: {
			'history': {
				'delay': 2500,
				'userOnly': true
			},
			toolbar: toolbarOptions,
		},
		scrollingContainer: "#editorcontainer",
		theme: 'snow'
	});

	function selectLocalImage() {
		const input = document.createElement('input');
		input.setAttribute('type', 'file');
		input.click();
		input.onchange = () => {
			let file = input.files[0],
				extArquivo = file.type.split('/').pop(),
				extPermitidas = ['jpg', 'png', 'jpeg', 'jpe'];
			if (typeof extPermitidas.find(function (ext) { return extArquivo == ext; }) == 'undefined') {
				document.querySelector('.modal-body').innerHTML = "<p class='error'>Tipo de arquivo inválido, só são aceitas imagens do tipo: <br> <b>jpg</b>, <b>jpe</b>, <b>jpeg</b> e <b>png</b>!</p>";
				document.getElementById('confirmar').style.display = 'none';
				myModal.show();
			} else {
				saveToServer(file);
			}
		};
	}

	function saveToServer(file) {
		const fd = new FormData();
		fd.append('image', file);
		fd.append('id', arqId);
		fd.append('caminho', caminho);
		const xhr = new XMLHttpRequest();
		xhr.open('POST', `${url}save/img`, true);
		xhr.onload = () => {
			if (xhr.status === 200) {
				const url = xhr.responseText;
				insertToEditor(url);
			}
		};
		xhr.send(fd);
	}

	function insertToEditor(url) {
		const range = quill.getSelection();
		quill.insertEmbed(range.index, 'image', url);
	}

	quill.getModule('toolbar').addHandler('image', () => {
		selectLocalImage();
	});

	let statusTimeout;
	document.querySelector('.ql-editor').addEventListener('keyup', function (e) {
		clearTimeout(statusTimeout);
		statusTimeout = setTimeout(() => {
			let metadata = document.querySelector('.ql-editor').innerHTML,
				id = arqId;
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}save/status`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`id=${id}&data=${metadata}`);
			ajax.onreadystatechange = function () {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = ajax.responseText;
					if (data) {
						document.querySelector('.aviso-status').innerHTML = "Salvando...";
						document.querySelector('.aviso-update').innerHTML = "* Falta <b>enviar</b> as atualizões";
						setTimeout(() => {
							document.querySelector('.aviso-status').innerHTML = "";
						}, 700);
					}
				}
			}
		}, 3000);
	});

	document.getElementById('enviarTudo').onclick = function (event) {
		event.preventDefault();
		let editor = document.querySelector('input[name=editor]'),
			imagens = document.querySelector('input[name=imagens]')
		container = document.querySelector('.ql-editor').innerHTML,
			parImg = document.querySelectorAll('.ql-editor img'),
			url = "";
		editor.value = container;

		Array.prototype.forEach.call(parImg, function (el) {
			url += el.currentSrc;
		});
		imagens.value = url;
		document.querySelector('#form-post').submit();
	}

	document.getElementById('addImageToCarousel').addEventListener('click', (e) => {
		e.preventDefault();
		let avatar = document.getElementsByClassName('image_error');
		let input = document.getElementsByClassName('file-carousel-chooser')[0].value;
		Object.keys(avatar).forEach(item => avatar[item].style.visibility = 'hidden');

		if (input) {
			document.getElementById('form-img').submit();
		} else {
			Object.keys(avatar).forEach(item => avatar[item].style.visibility = 'visible');
			avatar[1].style.innerHTML = '* Imagem não pode ser vazia.';
		}
	});
}