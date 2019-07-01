<ol class="breadcrumb">
  <li><a href="?c=Comprobante&a=index">Inicio</a></li>
  <li class="active">Comprobante #<?php echo str_pad($comprobante->id, 5, '0', STR_PAD_LEFT); ?></li>
</ol>

<div class="row">
        <div class="col-xs-12">

            <fieldset>
                <legend>Datos de nuestro cliente</legend>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Cliente</label>
                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->Nombre; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>RUC</label>
                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->RUC; ?>"  />                    
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->Direccion; ?>" />                    
                        </div>
                    </div>
                </div>
            </fieldset>

            <ul id="facturador-detalle" class="list-group">
                <?php foreach($comprobante->Detalle as $d): ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-7">
                            <?php echo $d->Producto->Nombre; ?>
                        </div>
                        <div class="col-xs-1 text-right">
                            <?php echo $d->Cantidad; ?>
                        </div>
                        <div class="col-xs-2 text-right">
                            <?php echo number_format($d->PrecioUnitario, 2); ?>
                        </div>
                        <div class="col-xs-2 text-right">
                            <?php echo number_format($d->Total, 2); ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
                <li class="list-group-item">
                    <div class="row text-right">
                        <div class="col-xs-10 text-right">
                            Sub Total
                        </div>
                        <div class="col-xs-2">
                            <b><?php echo number_format($comprobante->SubTotal, 2); ?></b>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row text-right">
                        <div class="col-xs-10 text-right">
                            IGV (18%)
                        </div>
                        <div class="col-xs-2">
                            <b><?php echo number_format($comprobante->IGV, 2); ?></b>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row text-right">
                        <div class="col-xs-10 text-right">
                            Total <b>(S/.)</b>
                        </div>
                        <div class="col-xs-2">
                            <b><?php echo number_format($comprobante->Total, 2); ?></b>
                        </div>
                    </div>
                </li>
            </ul>

        </div>
</div>

<a class="btn btn-primary btn-danger btn-lg btn-block" href="?c=comprobante&a=eliminar&id=<?php echo $comprobante->id; ?>" onclick="return confirm('¿Está seguro de eliminar este comprobante?');">Eliminar comprobante</a>