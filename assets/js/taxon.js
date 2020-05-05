$('#taxon_mainImage_uploadedImageFile_file').change(function(){
	readURL(this);
});

function readURL(input){

	if (input.files && input.files[0]){
		var reader = new FileReader();

		reader.onload = function(e){
			$('#main-image-feedback').attr('src', e.target.result);
			//console.log(input.files[0]);
			//$('#taxon_mainImage_uploadedImageFile_file').attr('placeholder', input.files[0].name);
			//console.log($('.custom-file-label').text());
			$('.custom-file-label').text(input.files[0].name);
			//console.log(e);
		}

		reader.readAsDataURL(input.files[0]); // convert to base64 string
	}
}

$("#add-image").click(function(){
	const index = +$('#widgets-counter').val();

	const template = $('#taxon_images').data('prototype').replace(/__name__/g, index);

	$('#taxon_images').append( template);
	$('#widgets-counter').val(index+1);

	// gestion du bouton supprimer
	handleDeleteButtons();
});


function handleDeleteButtons(){

	$('button[data-action="delete"]').click(function(){
		const target = this.dataset.target;
		$(target).remove();
	});
}

function updateCounter(){
	const count = $('#taxon_images div.form-group').length;
	$('#widgets-counter').val(count);
}

// et au rechargement de la page
updateCounter();
handleDeleteButtons();
