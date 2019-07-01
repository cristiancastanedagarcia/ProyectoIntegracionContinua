<ol class="breadcrumb">
  <li><a href="?c=Comprobante&a=index">Inicio</a></li>
  <li class="active">Nuevo comprobante</li>
</ol>

<form id="frm-comprobante" method="post" action="?c=Comprobante&a=Guardar">
    <div class="row">
        <div class="col-xs-12">

            <fieldset>
                <legend>Datos de nuestro cliente</legend>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Cliente</label>
                            <input autocomplete="off" id="cliente" class="form-control" type="text" placeholder="Ingrese el nombre del cliente" />
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>RUC</label>
                            <input autocomplete="off" id="ruc" disabled class="form-control" type="text" placeholder="RUC" />                    
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input autocomplete="off" id="direccion" disabled class="form-control" type="text" placeholder="Dirección" />                    
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="well well-sm">
                <div class="row">
                    <div class="col-xs-7">
                        <input id="producto_id" type="hidden" value="0" />
                        <input autocomplete="off" id="producto" class="form-control" type="text" placeholder="Nombre del producto" />
                    </div>
                    <div class="col-xs-2">
                        <input autocomplete="off" id="cantidad" class="form-control" type="text" placeholder="Cantidad" />
                    </div>
                    <div class="col-xs-2">
                        <div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">S/.</span>
                          <input autocomplete="off" id="precio" class="form-control" type="text" placeholder="Precio" />
                        </div>
                    </div>
                    <div class="col-xs-1">
                        <button class="btn btn-primary form-control" id="btn-agregar" type="button">
                             <i class="glyphicon glyphicon-plus"></i>
                        </button>
                    </div>
                </div>            
            </div>

            <hr />

            <ul id="facturador-detalle" class="list-group"></ul>
            
            <button class="btn btn-primary btn-block btn-lg" type="submit">Generar comprobante</button>

        </div>
</div>    
</form>

<script id="facturador-detalle-template" type="text/x-jsrender" src="">
    {{for items}}
    <li class="list-group-item">
        <div class="row">
            <div class="col-xs-7">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-danger form-control" onclick="facturador.retirar({{:id}});">
                            <i class="glyphicon glyphicon-minus"></i>
                        </button>
                    </span>
                    <input name="producto_id" type="hidden" value="{{:producto_id}}" />
                    <input disabled name="producto" class="form-control" type="text" placeholder="Nombre del producto" value="{{:producto}}" />
                </div>
            </div>
            <div class="col-xs-1">
                <input name="cantidad" class="form-control" type="text" placeholder="Cantidad" value="{{:cantidad}}" />
            </div>
            <div class="col-xs-2">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">S/.</span>
                  <input name="precio" class="form-control" type="text" placeholder="Precio" value="{{:precio}}" />
                </div>
            </div>
            <div class="col-xs-2">
                <div class="input-group">
                    <span class="input-group-addon">S/.</span>
                    <input name="precio"  class="form-control" type="text" readonly value="{{:total}}" />
                    <span class="input-group-btn">
<button type="button" class="btn btn-success form-control" onclick="facturador.actualizar({{:id}}, this);" class="btn-retirar">
    <i class="glyphicon glyphicon-refresh"></i>
</button>
                    </span>
                </div>
            </div>
        </div>
    </li>
    {{else}}
    <li class="text-center list-group-item">No se han agregado productos al detalle</li>
    {{/for}}

    <li class="list-group-item">
        <div class="row text-right">
            <div class="col-xs-10 text-right">
                Sub Total
            </div>
            <div class="col-xs-2">
                <b>{{:subtotal}}</b>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row text-right">
            <div class="col-xs-10 text-right">
                IGV (18%)
            </div>
            <div class="col-xs-2">
                <b>{{:igv}}</b>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row text-right">
            <div class="col-xs-10 text-right">
                Total
            </div>
            <div class="col-xs-2">
                <b>{{:total}}</b>
            </div>
        </div>
    </li>
</script>

<script src="assets/scripts/comprobante.js"></script>