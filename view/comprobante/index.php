<h1 class="page-header">
    <a class="btn btn-primary pull-right btn-lg" href="?c=Comprobante&a=crud">Nuevo comprobante</a>
    Comprobantes
</h1>

<div id="list"></div>

<script>
    $(document).ready(function(){
        $("#list").anexGrid({
            class: 'table-striped table-bordered',
            columnas: [
                { leyenda: 'Cliente', style: 'width:200px;', columna: 'Cliente_id', ordenable: true },
                { leyenda: 'IGV', style: 'width:60px;', columna: 'IGV', ordenable: true  },
                { leyenda: 'Sub Total', style: 'width:60px;', columna: 'SubTotal', ordenable: true  },
                { leyenda: 'Total', style: 'width:60px;', columna: 'Total', ordenable: true  },
            ],
            modelo: [
                { formato: function(tr, obj, valor){
                    return anexGrid_link({
                        href: '?c=comprobante&a=ver&id=' + obj.id,
                        contenido: obj.Cliente.Nombre
                    });
                }},
                { propiedad: 'IGV', class: 'text-right', },
                { propiedad: 'SubTotal', class: 'text-right', },
                { propiedad: 'Total', class: 'text-right', },
            ],
            url: '?c=Comprobante&a=Listar',
            limite: 10,
            columna: 'id',
            columna_orden: 'DESC',
            paginable: true
        });
    })
</script>