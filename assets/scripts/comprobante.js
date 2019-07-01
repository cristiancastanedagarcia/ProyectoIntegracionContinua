var facturador = {
    detalle: {
        igv:      0,
        total:    0,
        subtotal: 0,
        cliente_id: 0,
        items:    []
    },

    /* Encargado de agregar un producto a nuestra colección */
    registrar: function(item)
    {
        var existe = false;
        
        item.total = (item.cantidad * item.precio);
        
        this.detalle.items.forEach(function(x){
            if(x.producto_id === item.producto_id) {
                x.cantidad += item.cantidad;
                x.total += item.total;
                existe = true;
            }
        });

        if(!existe) {
            this.detalle.items.push(item);
        }

        this.refrescar();
    },

    /* Encargado de actualizar el precio/cantidad de un producto */
    actualizar: function(id, row)
    {
        /* Capturamos la fila actual para buscar los controles por sus nombres */
        row = $(row).closest('.list-group-item');

        /* Buscamos la columna que queremos actualizar */
        $(this.detalle.items).each(function(indice, fila){
            if(indice == id)
            {
                /* Agregamos un nuevo objeto para reemplazar al anterior */
                facturador.detalle.items[indice] = {
                    producto_id: row.find("input[name='producto_id']").val(),
                    producto: row.find("input[name='producto']").val(),
                    cantidad: row.find("input[name='cantidad']").val(),
                    precio:   row.find("input[name='precio']").val(),
                };

                facturador.detalle.items[indice].total = facturador.detalle.items[indice].precio *
                                                         facturador.detalle.items[indice].cantidad;

                return false;
            }
        })

        this.refrescar();
    },

    /* Encargado de retirar el producto seleccionado */
    retirar: function(id)
    {
        /* Declaramos un ID para cada fila */
        $(this.detalle.items).each(function(indice, fila){
            if(indice == id)
            {
                facturador.detalle.items.splice(id, 1);
                return false;
            }
        })

        this.refrescar();
    },

    /* Refresca todo los productos elegidos */
    refrescar: function()
    {
        this.detalle.total = 0;

        /* Declaramos un id y calculamos el total */
        $(this.detalle.items).each(function(indice, fila){
            facturador.detalle.items[indice].id = indice;
            facturador.detalle.total += fila.total;
        })

        /* Calculamos el subtotal e IGV */
        this.detalle.igv      = (this.detalle.total * 0.18).toFixed(2); // 18 % El IGV y damos formato a 2 deciamles
        this.detalle.subtotal = (this.detalle.total - this.detalle.igv).toFixed(2); // Total - IGV y formato a 2 decimales
        this.detalle.total    = this.detalle.total.toFixed(2);

        var template   = $.templates("#facturador-detalle-template");
        var htmlOutput = template.render(this.detalle);

        $("#facturador-detalle").html(htmlOutput);
    }
};

$(document).ready(function(){
    $("#btn-agregar").click(function(){
        var producto_id = $("#producto_id"),
            producto = $("#producto"),
            cantidad = $("#cantidad"),
            precio =   $("#precio");
        
        // Validaciones
        if(producto_id.val() === '0') {
            alert('Debe seleccionar un producto');
            return;
        }
        
        if(!isNumber(cantidad.val())) {
            alert('Debe ingresar una cantidad válida');
            return;
        } else if( parseInt(cantidad.val()) <= 0 ) {
            alert('Debe ingresar una cantidad válida');
            return;
        }
        
        facturador.registrar({
            producto_id: parseInt(producto_id.val()),
            producto: producto.val(),
            cantidad: parseFloat(cantidad.val()),
            precio: parseFloat(precio.val()),
        });

        producto_id.val('0');
        producto.val('');
        cantidad.val('');
        precio.val('');
    })
    
    $("#frm-comprobante").submit(function(){

        var form = $(this);

        if(facturador.detalle.cliente_id == 0)
        {
            alert('Debe agregar un cliente');
        }
        else if(facturador.detalle.items.length == 0)
        {
            alert('Debe agregar por lo menos un detalle al comprobante');
        }else
        {
            $.ajax({
                dataType: 'JSON',
                type: 'POST',
                url: form.attr('action'),
                data: facturador.detalle,
                success: function (r) {
                    if(r) window.location.href = '?c=Comprobante';
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(errorThrown + ' ' + textStatus);
                }   
            });            
        }

        return false;
    })
    
    /* Autocomplete de cliente, jquery UI */
    $("#cliente").autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: '?c=Comprobante&a=ClienteBuscar',
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            direccion: item.Direccion,
                            ruc: item.RUC,
                        }
                    }))
                }
            })
        },
        select: function (e, ui) {
            $("#cliente_id").val(ui.item.id);
            $("#direccion").val(ui.item.direccion);
            $("#ruc").val(ui.item.ruc);
            $(this).blur();
            
            facturador.detalle.cliente_id = ui.item.id;
        }
    })
    
    /* Autocomplete de producto, jquery UI */
    $("#producto").autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: '?c=Comprobante&a=ProductoBuscar',
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            precio: item.Precio
                        }
                    }))
                }
            })
        },
        select: function (e, ui) {
            $("#producto_id").val(ui.item.id);
            $("#precio").val(ui.item.precio);
            $("#cantidad").focus();
        }
    })
})

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}