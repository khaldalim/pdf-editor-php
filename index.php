<?php include('header.php'); ?>
<section>
	<div>
		<div id="add-ref" style="display: none">
			<button id="create-reference" href="#" onclick="addRef()">Ajouter une reférence</button>
			<button id="finish-btn" href="#" onclick="finish()" >Enregister le PDF</button>
			<a id="btnLinkPdf" href="#" target="_blank" style="display: none" >Voir le PDF</a>
			<a id="shareLinkPdf"  href="mailto:?subject=envoi PDF par mail&body=" style="display: none">Partager le PDF par mail</a>
		</div>


		<!-- create form --> 
		<div id="create-form" style="display: block">
			<p>
				<label for="pseudo">Titre de la référence</label> :<br>
				<input type="text" name="" id="title" required>
			</p>
			<p>
				<label for="story">Texte d'accompagnement</label> :<br>

				<textarea id="textarea-data" name="" rows="11" cols="50" maxlength=250 required ></textarea>
			</p>
			<p>
				<label for="avatar">Ajouter un visuel</label> :<br>
				Format Jpeg ou Png limité à 5Mo<br>
				<input type="file" id="file" name="file" accept="image/png, image/jpeg, image/jpg" required >
			</p>
			<button id="create-button" data-statut="22" href="#" >Ajouter</button>
		</div>


		<div>
			<h3>Les références</h3>

			<div class="container">
			</div>

			<div id="refs">
			</div>

		</div>

		<br><br>
	</div>
</section>




<?php 

include('footer.php'); 
?>




<script>
	var arrayDataPDF = [];
	var arrayRawDataPDF = [];
	var validRef = 0 ;
	const maxValidRef = 4 ;
	var imageRef = "";
	var uid = 0;
	var statut = "";
	var idModify = 0;

	function ctlButton(){
		if (validRef >= maxValidRef ) {
			$("#create-button").prop("disabled", true);
			$("#create-reference").prop("disabled", true);
		}
		else {
			$("#create-button").prop("disabled", false);
			$("#create-reference").prop("disabled", false);
		}
	}

	function validate(){
		console.log(validRef);
		document.getElementById("create-form").style.display = "none";
		document.getElementById("add-ref").style.display = "block";
		var error = 0;
				
		var titleRef = document.getElementById('title').value;
		var textRef = document.getElementById('textarea-data').value;
		
		var validateText = /^([a-z0-9 A-Z]{3,})$/;
		var titleRefResult = validateText.test(titleRef);
		var textRefResult = validateText.test(textRef);


		if(titleRefResult == false)	{
			error++;
		}
		if(textRefResult == false) {
			error++;
		}


		if(error == 0){

			var dataFinal = '[{"data":['+arrayDataPDF+']}]';
			$.ajax({
				method: "POST",
				url: "ajax/upload-image.php",
				data:  {
					imageRef : imageRef,
					dataType: 'json',
					titleRef : titleRef,
					descRef : textRef,
					UID : uid,
					numContainer : validRef,
					IDContainer : idModify,
					statut : statut
				} ,



				beforeSend: function() {},
				success: function(data, textStatus, XMLHttpRequest){

					console.log(data);
					data =  JSON.parse(data);
					imageUrl = data.url; 
					uid = data.uid;

					if(statut == 55){
						arrayRawDataPDF[idModify][0] = document.getElementById('title').value ;
						arrayRawDataPDF[idModify][1] = document.getElementById('textarea-data').value ;
						arrayRawDataPDF[idModify][2] = imageUrl ;
						document.getElementById("add-ref").style.display = "block";
						document.getElementById("create-form").style.display = "none";
						$(".container"+idModify+" h3").html(arrayRawDataPDF[idModify][0]);
						$(".container"+idModify+" p").html(arrayRawDataPDF[idModify][1]);
						console.log(arrayRawDataPDF[idModify][2]);
						$(".container"+idModify+" img").attr('src' , arrayRawDataPDF[idModify][2]);
					}


					

					$("#btnLinkPdf").attr("href" , "http://pdf-editor-php.fr/pdf-edited.php?f="+uid);
					$("#shareLinkPdf").attr("href" , "mailto:?subject=Le lien du PDF&body=http://pdf-editor-php.fr/pdf-edited.php?f="+uid);

					var data = '{"image":"'+imageUrl+'","titre":"'+titleRef+'","description":"'+textRef+'"}';
					arrayDataPDF.push(data);

					var rawData = [titleRef, textRef,imageUrl];
					arrayRawDataPDF.push(rawData);
					document.getElementById('title').value = '';
					document.getElementById('textarea-data').value ='';
					document.getElementById('file').value = '';
					$(".container").html("");

					var lengtArrayPdf = arrayDataPDF.length;
					var strData = '';
					var num = 0;

					var lengtArrayPdfRaw = arrayRawDataPDF.length;
					for(var i=0;  i< lengtArrayPdfRaw; i++) {
						$(".container").append('<div style="padding:10px;margin-bottom:5%; min-height:225px;" data-id="'+i+'" class="'+ String("container"+i) +'"><div style="display: inline; float: left;"><img style="width:300px; height:225px;     object-fit: cover;" src="'+ arrayRawDataPDF[i][2] +'"></div><div style="float:left; padding:10px"><h3>' + arrayRawDataPDF[i][0] + '</h3><p>' + arrayRawDataPDF[i][1] + '</p><div><button id="modify-button" onclick="modify('+i+')">Modifier</button ><button id="suppr-button" onclick="suppr('+i+')">Supprimer</button ></div></div></div>');

						if(num != 0) strData += ',';
						strData += arrayDataPDF[i];
						num ++;
					}

					var finalData = '[{"data":['+strData+']}]';


					
						validRef ++ ;
					
					ctlButton();
				},  
				error: function(MLHttpRequest, textStatus, errorThrown){}
			});



		} else {
			alert("Error");
		}
		ctlButton();
	}

	// ajouter une ref
	function addRef(){
		document.getElementById('title').value = '';
		document.getElementById('textarea-data').value ='';
		document.getElementById('file').value = '';
		document.getElementById("create-form").style.display = "block";
		document.getElementById("add-ref").style.display = "none";
		//$("#create-button").attr("onclick" , "validate()");

		$("#create-button").attr("data-statut" , "22");
		$("#create-button").text("Ajouter"); 
	}


	// MODIFFIER
	function modify(i){
		$("#create-button").prop("disabled", false);
		idModify = i;

		document.getElementById("add-ref").style.display = "none";
		document.getElementById("create-form").style.display = "block";
		document.getElementById('title').value = arrayRawDataPDF[i][0];
		document.getElementById('textarea-data').value = arrayRawDataPDF[i][1];
		//$("#create-button").attr("onclick" , "validate()");

		$("#create-button").attr("data-statut" , "55");
		$("#create-button").text("Modifier");

		var buttonStatut = document.querySelector('#create-button');
		 statut = buttonStatut.dataset.statut;
		console.log(statut);

	}


	// SUPPRIMER
	function suppr(i){
		document.getElementById("create-form").style.display = "none";
		document.getElementById("add-ref").style.display = "block";
		$(".container"+i).remove();
		arrayDataPDF.splice(i, 1);
		arrayRawDataPDF.splice(i, 1);
		validRef-- ;
		ctlButton();
	}


	// modifier les références
	// function update(){
	// 	validate();



	// }

	var uploadField = document.getElementById("file");
	uploadField.onchange = function() {
		if(this.files[0].size > 507200){
			alert("Fichier trop lourd ! ");
			this.value = "";
		};
	};







	//image to base 64
	document.getElementById('create-button').addEventListener('click', function() {
		var files = document.getElementById('file').files;
		if (files.length > 0) {
			getBase64(files[0]);
		}
	});

	function getBase64(file) {
		var reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function () {
			// console.log(reader.result);
			imageRef = reader.result;
			validate();
		};
		reader.onerror = function (error) {
			console.log('Error: ', error);
		};
	}








	function finish(){
		document.getElementById("btnLinkPdf").style.display = "block";
		document.getElementById("shareLinkPdf").style.display = "block";
	};
</script>
