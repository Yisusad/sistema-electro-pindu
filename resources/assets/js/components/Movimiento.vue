<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Movimientos
                        <button type="button" @click="abrirModal('movimiento','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Insertar Movimiento
                        </button>
                        <button type="button" @click="abrirModal()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Apertura de Caja
                        </button>
                        <button type="button" @click="abrirModal()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Cierre de Caja</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Concepto</th>
                                    <th>Usuario</th>
                                    <th>Ingreso</th>
                                    <th>Egreso</th>
                                    <th>Fecha_hota</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="movimiento in arrayMovimiento" :key="movimiento.id">
                                    <td>
                                        <button type="button" @click="abrirModal('movimiento','actualizar',movimiento)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>&nbsp;
                                    </td>
                                    <td v-text="movimiento.descripcion_concepto"></td>
                                    <td v-text="movimiento.usuario"></td>
                                    <td>
                                        <div v-if="movimiento.tipo">
                                        <td v-text="movimiento.importe_i=importe"></td>
                                        <td v-text="movimiento.importe_e=0"></td>
                                        </div>
                                        <div v-else>
                                        <td v-text="movimiento.importe_i=0"></td>
                                        <td v-text="movimiento.importe_e=importe"></td>
                                        </div>                    
                                    </td>
                                    <td v-text="movimiento.fecha_hora"></td>
                                    <td v-text="movimiento.estado"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-9">
                                        <input type="datetime-local" v-model="fecha_hora" class="form-control">                                    
                                    </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Movimiento</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idcaja_conceptos">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="concepto in arrayConcep" :key="concepto.id" :value="concepto.id" v-text="concepto.descripcion"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Importe</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="importe" class="form-control" placeholder="Nombre del contacto">
                                    </div>
                                </div>
                                <div v-show="errorPersona" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMovimiento()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMovimiento()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                movimiento_id: 0,
                idcaja_conceptos : 0,
                importe : 0,
                tipo : 0,
                descripcion_concepto : '',
                idusuario : '',
                importe_i:0.0,
                importe_e:0.0, 
                estado : 'Activo',
                arrayMovimiento : [],                
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMovimiento : 0,
                errorMostrarMsjMovimiento : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                arrayConcep : [],
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarMovimiento (page){
                let me=this;
                var url= '/movimiento?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayMovimiento = respuesta.caja_movimientos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarMovimiento(page);
            },
            registrarMovimiento(){
                if (this.validarMovimiento()){
                    return;
                }
                
                let me = this;

                axios.post('/movimiento/registrar',{
                    'fecha_hora' : this.fecha_hora,
                    'idcaja_conceptos': this.idcaja_conceptos,
                    'importe_i': this.importe_i,
                    'importe_e': this.importe_e,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarMovimiento();
                }).catch(function (error) {
                    console.log(error);
                });
            },           
            validarMovimiento(){
                this.errorMovimiento=0;
                this.errorMostrarMsjMovimiento =[];


                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.fecha_hora='',
                this.idcaja_conceptos=0,
                this.descripcion_concepto='',
                this.importe_i=0,0,
                this.importe_e=0,0,
                this.errorPersona=0;

            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "movimiento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Realizar Movimiento';
                                this.fecha_hora='',
                                this.idcaja_conceptos=0,
                                this.descripcion_concepto='',
                                this.importe_i=0,0,
                                this.importe_e=0,0,
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                            }
                        }
                    }
                    this.selectConcepto();
                }
            },
            selectConcepto(){
                let me=this;
                var url= '/concepto/selectConcepto';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayConcep = respuesta.caja_conceptos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarUsuario(id){
               swal({
                title: 'Esta seguro de desactivar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarUsuario(id){
               swal({
                title: 'Esta seguro de activar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            }
        },
        mounted() {
            this.listarMovimiento();
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
