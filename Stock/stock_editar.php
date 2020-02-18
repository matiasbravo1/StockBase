<?php
include('../Headers/session.php');
include('../Headers/principal.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_articulo = test_input($_GET["id"]);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$sql = "SELECT * FROM articulos WHERE `id_articulo` = '$id_articulo'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    exit('<br><div class="text-center">El artículo a editar ya no existe. Recuerde actualizar las búsquedas.</div>');
}

$row = $result->fetch_assoc();

if($_SESSION["tipo"] == "Pañol"){
    $disabled = "disabled";
    $visible = "d-none";
}else{
    $disabled = "";
    $visible = "";
}
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">

            <form id="form1" class="text-center border border-light p-3 mt-4 stock" style="border-radius:8px;">
            
                <p class="h4 mb-3">Editar Artículo</p>
                <input id="id_articulo" type="number" style="display:none;" value="<?php echo $row["id_articulo"];?>">
                
                <div class="form-row mb-3">
                    <div class="col-3">
                        <small>Código</small>
                        <input type="text" id="codigo" class="form-control mx-auto" placeholder="Código" autofocus required value="<?php echo $row["codigo"];?>" disabled>
                    </div>
                    <div class="col-9">
                        <small>Descripción</small>
                        <input type="text" id="descripcion" class="form-control mx-auto" placeholder="Descripción" maxlength="100" required value="<?php echo $row["descripcion"];?>" <?php echo $disabled;?>>
                    </div>
                </div>
                
                <div class="form-row mb-3">
                    <div class="col-4">
                        <small>Marca</small>
                        <select id="marca" class="browser-default custom-select" <?php echo $disabled;?>>
                            <option value=""></option>
                            <?php
                                $sql2 = "SELECT marca FROM listas WHERE `marca` NOT LIKE ''";
                                $result2 = $conn->query($sql2);
                                while($row2 = $result2->fetch_assoc()) {
                                    ($row2["marca"] == $row["marca"]) ? $selected = "selected" : $selected = '';
                                	echo '<option value="' . $row2["marca"] . '" ' . $selected . '>' . $row2["marca"] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <small>Familia</small>
                        <select id="familia" class="browser-default custom-select">
                            <option value=""></option>
                            <?php
                                $sql2 = "SELECT familia FROM listas WHERE `familia` NOT LIKE ''";
                                $result2 = $conn->query($sql2);
                                while($row2 = $result2->fetch_assoc()) {
                                    ($row2["familia"] == $row["familia"]) ? $selected = "selected" : $selected = '';
                                	echo '<option value="' . $row2["familia"] . '" ' . $selected . '>' . $row2["familia"] . '</option>';
                                }
                                
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <small>Código del Proveedor</small>
                        <input type="text" id="codigo_prov" class="form-control mx-auto" value="<?php echo $row["codigo_prov"];?>">
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-4">
                        <small>Stock Mínimo</small>
                        <input type="number" id="minimo" class="form-control" max="9999999999" min="0" value="<?php echo $row["minimo"];?>">
                    </div>
                    <div class="col-4">
                        <small>Stock Crítico</small>
                        <input type="number" id="critico" class="form-control" max="9999999999" min="0" value="<?php echo $row["critico"];?>">
                    </div>
                    <div class="col-4">
                        <small>Consumo Mensual</small>
                        <input type="text" id="mensual" class="form-control" value="<?php echo $row["mensual"];?>">
                    </div>
                </div>
                
                <div class="custom-control custom-switch mb-2">
                    <?php
                    ($row["activo"] == '1') ? $activo = "checked" : $activo = '';
                    ?>
                    <input type="checkbox" class="custom-control-input" id="activo" <?php echo $activo;?>>
                    <label class="custom-control-label" for="activo">Visible para Nuevo Vale</label>
                </div>
                
                <div class="form-row mb-2">
                    <div class="col-5">
                        <button class="btn btn-primary mt-3 btn-block" type="button" onclick="guardar()"><span id="loader" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp&nbspGuardar</button>
                    </div>
                    <div class="col-5">
                        <button class="btn btn-warning mt-3 btn-block" type="button" onclick="cancelar()">Cancelar</button>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-danger mt-3 btn-block px-0" type="button" onclick="eliminar()" <?php //echo $disabled;?>>Eliminar</button>
                    </div>
                </div>
            </form>
            <div id="atencion" class="text-center mt-2" style="display:none;color:white;background-color:red;">Atención! Modifique la descripción siempre y cuando se mantenga el tipo de artículo.<br>Si desea modificar el código consulte al programador.</div>
            <div id="resultado" class="text-center" style="display:none"></div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    $('#stock').addClass('active');
    document.title = 'Editar Artículo';
});

$("#descripcion").focusin(function(){
    $("#atencion").css({"display":"block"});
});

$("#descripcion").focusout(function(){
    $("#atencion").css({"display":"none"});
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

function eliminar(){
    alert("Para eliminar un artículo consulte al programador.");
    /*var r = confirm("Seguro que desea eliminar el artículo?");
    if (r == true) {
        ajax = objetoAjax();

    	ajax.open("POST", "stock_eliminar_articulo_ajax.php", true);
    
    	ajax.onreadystatechange = function() {
    
    		if (ajax.readyState == 4){

                if (ajax.responseText.includes("éxito")){
                    $("#form1").hide();
                    document.getElementById("resultado").innerHTML = '<br>'+ajax.responseText+'<div class="text-center"><button class="btn btn-secondary-dark mt-3 btn-sm" type="button" onclick="cancelar()">Cerrar</button></div>';
                    $("#resultado").fadeIn();
                } else {
                    document.getElementById("resultado").innerHTML = ajax.responseText;
                    $("#resultado").fadeIn();
                }    
                    
            }
    	}
    
    	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    
    	ajax.send("&id="+$("#id_articulo").val());
    
    }*/
}

function cancelar(){
    window.close();
}

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

	ajax.open("POST", "stock_editar_articulo_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    $("#loader").css({"display":"none"});
            if (ajax.responseText == '<span class="badge badge-danger">Error: El código ya existe.</span>'){
                document.getElementById("resultado").innerHTML = ajax.responseText;
                $("#codigo").addClass('requerido');
                $("#codigo").focus();
                $("#resultado").fadeIn();
                setTimeout(Borrar, 3000);
            } else if (ajax.responseText == '<span class="badge badge-success">El artículo se ha editado con éxito.</span>'){
                $("#form1").hide();
                document.getElementById("resultado").innerHTML = '<br>'+ajax.responseText+'<br><span class="badge badge-secondary">Recuerde actualizar las búsquedas.</span><div class="text-center"><button class="btn btn-secondary mt-3 btn-sm" type="button" onclick="cancelar()">Cerrar</button></div>';
                $("#resultado").fadeIn();
            } else {
               document.getElementById("resultado").innerHTML = ajax.responseText;
               $("#resultado").fadeIn();
               setTimeout(Borrar, 3000);
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id="+$("#id_articulo").val()+"&codigo="+$("#codigo").val()+"&codigo_prov="+$("#codigo_prov").val()+"&descripcion="+encodeURIComponent($("#descripcion").val())+"&marca="+$( "#marca option:selected" ).text()+"&familia="+$( "#familia option:selected" ).text()+"&minimo="+$("#minimo").val()+"&critico="+$("#critico").val()+"&mensual="+$("#mensual").val()+"&activo="+$("#activo").is(':checked'));
}

function Borrar(){
    $("#resultado").fadeOut();
}
</script>
</body>

</html>