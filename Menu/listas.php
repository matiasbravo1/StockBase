<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">

            <form id="form1" class="text-center border border-light p-3 mt-4 config" style="border-radius:8px;">
            
                <p class="h4 mb-3">Listas</p>
                
                <div class="form-row mb-3 justify-content-center">

                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Lista</small>
                        <select id="lista" class="browser-default custom-select">
                            <option value="seccion">Secciones</option>
                            <option value="proveedor" selected>Proveedores</option>
                            <option value="marca">Marcas</option>
                            <option value="familia">Familias</option>
                        </select>
                    </div>
                    <div class="col-2 mt-auto mb-0 d-none">
                        <button class="btn mt-3 btn-block btn-form" style="background-color:#9933CC;color:white" type="button" onclick="ver()"><span id="loader" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Ver</button>
                    </div>
                    <div class="col-3 mt-auto mb-0">
                        <button class="btn mt-3 btn-block btn-form" style="background-color:#9933CC;color:white" type="button" onclick="agregar()"><span id="loader" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Agregar valor</button>
                    </div>
                </div>
                <div class="form-row mb-3 justify-content-center">
                    <div id="resultado" class="col-6 col-sm-6 col-md-8 col-lg-8 col-xl-8">
                        
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    //$('#stock').addClass('active');
    document.title = 'Listas';
    ver();
});

$("#lista").change(function(){
    ver();
});

function ver(){
    document.getElementById("resultado").innerHTML = '<center><span id="loader" class="spinner-border spinner-border-sm mr-2" role="status" style="display:inline-block" aria-hidden="true"></center>';

    ajax = objetoAjax();
    
		ajax.open("POST", "listas_buscar_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                //document.getElementById("loader_izq").style.display = "none";
                document.getElementById("resultado").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&lista="+$("#lista").val());
}

function borrar(id){
    ajax = objetoAjax();

	ajax.open("POST", "listas_eliminar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            if (ajax.responseText.includes("Error")){
                alert(ajax.responseText);
            } else {
                ver();   
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id="+id);
}

function agregar(){
    
    if($("#lista").val() == 'seccion'){
        alert("No se pueden insertar valores en la lista de secciones. Consulte al programador.");
        return;
    }
    
    var valor = prompt("Ingrese el valor a insertar en la lista seleccionada.");
    
    if (valor == null || valor == "") {
      return;
    } else {
          
        ajax = objetoAjax();
    
    	ajax.open("POST", "listas_agregar_ajax.php", true);
    
    	ajax.onreadystatechange = function() {
    
    		if (ajax.readyState == 4){
                if (ajax.responseText.includes("Error")){
                    alert(ajax.responseText);
                } else {
                    ver();   
                }
            }
    	}
    
    	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    
    	ajax.send("&lista="+$("#lista").val()+"&valor="+valor);
    }
}
</script>
</body>

</html>