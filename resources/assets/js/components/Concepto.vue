<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Conceptos
                        <button type="button" @click="abrirModal('concepto','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Tipo Movimiento</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="concepto in arrayConcepto" :key="concepto.id">
                                    <td>
                                        <button type="button" @click="abrirModal('concepto','actualizar',concepto)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td>
                                    <div v-if="concepto.tipo_movimientos=='1'">
                                            <span>Ingreso</span>
                                    </div>  
                                    <div v-if="concepto.tipo_movimientos=='2'">
                                            <span>Egreso</span>
                                    </div> 
                                    </td>                            
                                    <td v-text="concepto.descripcion"></td>
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
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripción del concepto">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                    <div class="col-md-9">
                                        <select v-model="tipo_movimientos" class="form-control">
                                            <option value="1">Ingreso</option>
                                            <option value="2">Egreso</option>
                                        </select>                                    
                                    </div>
                                </div>
                                <div v-show="errorConcepto" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjConcepto" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarConcepto()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarConcepto()">Actualizar</button>
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
                concepto_id: 0,
                tipo_movimientos : '',
                descripcion : '',
                arrayConcepto : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorConcepto : 0,
                errorMostrarMsjConcepto : [],
                 pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
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
            listarConcepto (page){
                let me=this;
                var url= '/concepto?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;                    
                    me.arrayConcepto = respuesta.caja_conceptos.data;
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
                me.listarConcepto(page);
            },
            registrarConcepto(){
                if (this.validarConcepto()){
                    return;
                }
                let me = this;

                axios.post('/concepto/registrar',{
                    'tipo_movimientos': this.tipo_movimientos,
                    'descripcion': this.descripcion
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarConcepto();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarConcepto(){
                if (this.validarConcepto()){
                    return;
                }
                let me = this;

                axios.put('/concepto/actualizar',{
                    'tipo_movimientos': this.tipo_movimientos,
                    'descripcion': this.descripcion,
                    'id': this.concepto_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarConcepto();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarConcepto(){
                this.errorConcepto=0;
                this.errorMostrarMsjConcepto =[];

                if (!this.descripcion) this.errorMostrarMsjConcepto.push("La descripción del concepto no puede estar vacío.");

                if (this.errorMostrarMsjConcepto.length) this.errorConcepto = 1;

                return this.errorConcepto;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.tipo_movimientos='';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "concepto":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Concepto';
                                this.tipo_movimientos= '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Concepto';
                                this.tipoAccion = 2;
                                this.concepto_id = data['id'];
                                this.tipo_movimientos= data['tipo_movimientos'];
                                this.descripcion = data['descripcion'];
                                break;   
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarConcepto();
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
