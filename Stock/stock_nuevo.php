<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">

            <form id="form1" class="text-center border border-light p-3 mt-4 stock" style="border-radius:8px;">
            
                <p class="h4 mb-3">Nuevo Artículo</p>
                
                <div class="form-row mb-3">
                    <div class="col-3">
                        <small>Código</small>
                        <input type="text" id="codigo" class="form-control mx-auto" autofocus required>
                    </div>
                    <div class="col-9">
                        <small>Descripción</small>
                        <input type="text" id="descripcion" class="form-control mx-auto" maxlength="100" required>                        
                    </div>
                </div>
                
                <div class="form-row mb-3">
                    <div class="col-4">
                        <small>Marca</small>
                        <select id="marca" class="browser-default custom-select">
                            <option value=""></option>
                            <?php
                                $sql = "SELECT marca FROM listas WHERE `marca` NOT LIKE ''";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                	echo '<option value="' . $row["marca"] . '">' . $row["marca"] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <small>Familia</small>
                        <select id="familia" class="browser-default custom-select">
                            <option value=""></option>
                            <?php
                                $sql = "SELECT familia FROM listas WHERE `familia` NOT LIKE ''";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                	echo '<option value="' . $row["familia"] . '">' . $row["familia"] . '</option>';
                                }
                                $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <small>Código del Proveedor</small>
                        <input type="text" id="codigo_prov" class="form-control mx-auto" maxlength="50"> 
                    </div>
                </div>
                
                <div class="form-row mb-3">
                    <div class="col-4">
                        <small>Stock Mínimo</small>
                        <input type="number" id="minimo" class="form-control" max="9999999999" min="1">
                    </div>
                    <div class="col-4">
                        <small>Stock Crítico</small>
                        <input type="number" id="critico" class="form-control" max="9999999999" min="1">
                    </div>
                    <div class="col-4">
                        <small>Consumo Mensual</small>
                        <input type="text" id="mensual" class="form-control mx-auto" maxlength="50">  
                    </div>
                </div>
                
                <div class="custom-control custom-switch mb-2">
                    <input type="checkbox" class="custom-control-input" id="activo" checked>
                    <label class="custom-control-label" for="activo">Visible para Nuevo Vale</label>
                </div>
                
                <div class="form-row mb-2">
                    <div class="col-12">
                        <button class="btn btn-primary mt-3 btn-block" type="button" onclick="guardar()"><span id="loader" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp&nbspGuardar</button>
                    </div>
                </div>
                
            </form>
            
            <div id="resultado" class="text-center" style="display:none"></div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    $('#stock').addClass('active');
    document.title = 'Nuevo Artículo';
});

$("#codigo").focusout(function(){
    if ($(this).val() == ''){
        $("#codigo").addClass('requerido');
    }else{
        $("#codigo").removeClass('requerido');
    }
});

$("#descripcion").focusout(function(){
    if ($(this).val() == ''){
        $("#descripcion").addClass('requerido');
    }else{
        $("#descripcion").removeClass('requerido');
    }
});

function guardar(){
    if ($("#codigo").val() == ''){
        $("#codigo").addClass('requerido');
        $("#codigo").focus();
        return;
    }
    
    if ($("#descripcion").val() == ''){
        $("#descripcion").addClass('requerido');
        $("#descripcion").focus();
        return;
    }
    
    $("#loader").css({"display":"inline-block"});
    
    ajax = objetoAjax();

	ajax.open("POST", "stock_crear_articulo_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    $("#loader").css({"display":"none"});
            if (ajax.responseText == '<span class="badge badge-danger">Error: El código ya existe.</span>'){
                document.getElementById("resultado").innerHTML = ajax.responseText;
                $("#codigo").addClass('requerido');
                $("#codigo").focus();
                $("#resultado").fadeIn();
                setTimeout(Borrar, 3000);
            } else if (ajax.responseText == '<span class="badge badge-success">El artículo se ha creado con éxito.</span>'){
                document.getElementById("resultado").innerHTML = ajax.responseText;
                document.getElementById("form1").reset();
                $("#codigo").focus();
                $("#resultado").fadeIn();
                setTimeout(Borrar, 3000);
            } else {
               document.getElementById("resultado").innerHTML = ajax.responseText;
               $("#resultado").fadeIn();
               setTimeout(Borrar, 3000);
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&codigo="+$("#codigo").val()+"&descripcion="+encodeURIComponent($("#descripcion").val())+"&marca="+$( "#marca option:selected" ).text()+"&familia="+$( "#familia option:selected" ).text()+"&minimo="+$("#minimo").val()+"&critico="+$("#critico").val()+"&mensual="+$("#mensual").val()+"&codigo_prov="+$("#codigo_prov").val()+"&activo="+$("#activo").is(':checked'));
}

function Borrar(){
    $("#resultado").fadeOut();
}
</script>
</body>

</html>