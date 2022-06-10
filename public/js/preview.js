
// cuando cargue la ventana
window.addEventListener('load', function(){
	var imagen = document.querySelector('.preview'); // referencia al elemento <img>
	var imagenInicial = imagen.src; // guardamos la ruta de la imagen inicial
	var fileInput = document.querySelector('.file-with-preview'); // referencia al <input>
	
	fileInput.onchange = function(e) {	
		// si el fichero no es de los tipos adecuados, no se hacen cambios
		if(!fileInput.files[0].name.match(/\.(jpe?g|png|gif)$/i)){
			alert('El tipo del fichero debe ser JPG, PNG o GIF');
			imagen.src = imagenInicial; // pone de nuevo la imagen original
			fileInput.value = ''; 	    // borra el contenido del input	
		}else{
			// si el fichero si es del tipo adecuado
		    let reader = new FileReader(); // nuevo objeto FileReader
		    reader.readAsDataURL(fileInput.files[0]); // lee el fichero
	
		    reader.onload = function(){ // cuando esté listo...
				imagen.src = reader.result; // coloca la previsualización en la imagen
		    }
		}
	}
});

