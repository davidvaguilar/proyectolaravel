<template>
  <main class="main">
    <!-- Breadcrumb  class="breadcrumb" -->
    <ol >
      <!-- <li class="breadcrumb-item active"><a href="/">SISTEMA DE COMPRA Y VENTA - CLIENTES</a></li> -->
    </ol> 
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col">
              <h2>Listado de Proveedores</h2><br/>
            </div>
            <div class="col text-right">
              <button class="btn btn-primary btn-lg" type="button" @click="abrirModal( 'proveedor', 'registrar')">
                <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Proveedor
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-8">
              <div class="input-group">
                <select class="form-control col-md-5" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="num_documento">Documento</option>
                  <option value="email">Email</option>
                  <option value="telefono">Teléfono</option>                  
                </select>
                <input type="text" @keyup.enter="listarProveedor(1, buscar, criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                <button type="submit" @click="listarProveedor(1, buscar, criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm text-center">
            <thead>
              <tr class="bg-primary">
                <th>Proveedor</th>
                <th>Tipo de Documento</th>
                <th>Numero Documento</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Editar</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="proveedor in arrayProveedor" :key="proveedor.id">        
                <td v-text="proveedor.nombre"></td>
                <td v-text="proveedor.tipo_documento"></td>
                <td v-text="proveedor.num_documento"></td>
                <td v-text="proveedor.telefono"></td>
                <td v-text="proveedor.email"></td>
                <td v-text="proveedor.direccion"></td>               
                <td>
                  <button type="button" class="btn btn-info btn-md" @click="abrirModal('proveedor', 'actualizar', proveedor)">
                    <i class="fa fa-edit fa-2x"></i>
                  </button> &nbsp;
                </td>                
              </tr>                  
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Anterior</a>
              </li>

              <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
              </li>
                        
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Siguiente</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
              <!-- Fin ejemplo de tabla Listado -->
    </div>
          <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" :class="{'mostrar':modal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" @click="cerrarModal()" class="close" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
                      
          <div class="modal-body">                          
            <div v-show="errorProveedor" class="form-group row div-error">                              
              <div class="text-center text-error">                                  
                <div v-for="error in errorMostrarMsjProveedor" :key="error" v-text="error"></div>
              </div>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                <div class="col-md-9">
                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del proveedor">                    
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Tipo Documento</label>
                <div class="col-md-9">
                  <select v-model="tipo_documento" class="form-control">
                    <option value="DNI">DNI</option>
                    <option value="CEDULA">CEDULA</option>
                    <option value="PASS">PASS</option>
                  </select>
                </div>
              </div>
               <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Número</label>
                <div class="col-md-9">
                    <input type="text" v-model="num_documento" class="form-control" placeholder="Numero de Documento">                    
                </div>
              </div>
               <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                <div class="col-md-9">
                    <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">                    
                </div>
              </div>
               <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                <div class="col-md-9">
                    <input type="text" v-model="telefono" class="form-control" placeholder="Telefono">                    
                </div>
              </div>
               <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Email</label>
                <div class="col-md-9">
                    <input type="email" v-model="email" class="form-control" placeholder="Email">                    
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
            <button type="button" @click="registrarProveedor()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
            <button type="button" @click="actualizarProveedor()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>
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

    data(){
      return {
        proveedor_id: 0,
        nombre: '',
        tipo_documento: 'CEDULA',
        num_documento: '',
        direccion: '',
        telefono: '',
        email: '',
        arrayProveedor: [],
        modal: 0,
        tituloModal: '',
        tipoAccion: 0,
        errorProveedor: 0,
        errorMostrarMsjProveedor: [],
        pagination: {
          'total': 0,
          'current_page': 0,
          'per_page': 0,
          'last_page': 0,
          'from': 0,
          'to': 0,
        },
        offset: 3,
        criterio: 'nombre',
        buscar: ''
      }
    },
    computed:{
      isActived: function(){
        return this.pagination.current_page;
      },
      pagesNumber: function(){
        if( !this.pagination.to ){
          return [];
        }
        var from = this.pagination.current_page-this.offset;
        if( from < 1 ){
          from = 1;
        }
        var to = from +(this.offset*2);
        if( to >= this.pagination.last_page ){
          to = this.pagination.last_page;
        }

        var pagesArray = []; 
        while( from <= to ){
          pagesArray.push(from);
          from++;
        }
        return pagesArray;
      }
    },

    methods:{
      listarProveedor(page, buscar, criterio){
        let me = this;
        var url = '/proveedor?page=' + page+ '&buscar=' + buscar + '&criterio=' + criterio;

        axios.get(url).then(function(response){
          var respuesta = response.data;
          me.arrayProveedor = respuesta.proveedores.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error){
          console.log(error);
        });
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        me.pagination.current_page = page;
        me.listarProveedor(page, buscar, criterio);
      },
      registrarProveedor(){
        if( this.validarProveedor() ){
          return;
        }
        let me = this;
        axios.post('/proveedor/registrar', {
          'nombre': this.nombre,
          'tipo_documento': this.tipo_documento,
          'num_documento': this.num_documento,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'email': this.email
        }).then(function(response){
          me.cerrarModal();
          me.listarProveedor(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      actualizarProveedor(){
        if( this.validarProveedor() ){
          return;
        }
        let me = this;
        axios.put('/proveedor/actualizar', {
          'id': this.proveedor_id,
          'nombre': this.nombre,
          'tipo_documento': this.tipo_documento,
          'num_documento': this.num_documento,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'email': this.email        
        }).then(function(response){
          me.cerrarModal();
          me.listarProveedor(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      validarProveedor(){
        this.errorProveedor = 0;
        this.errorMostrarMsjProveedor = [];
        if( !this.nombre ){
          this.errorMostrarMsjProveedor.push("(*)El nombre del proveedor no puede estar vacio");
        }
        if( this.errorMostrarMsjProveedor.length ){
          this.errorProveedor = 1;
        }
        return this.errorProveedor;
      },
      cerrarModal(){
        this.modal = 0;
        this.tituloModal = '';
        this.nombre = '';
        this.tipo_documento = 'CEDULA';
        this.num_documento = '';
        this.direccion = '';
        this.telefono = '';
        this.email = '';
        this.errorProveedor = 0;
      },
      abrirModal( modelo, accion, data=[] ){
        switch( modelo ){
          case "proveedor":
          {
            switch( accion ){
              case "registrar":
              {
                this.modal = 1;
                this.tituloModal = "Agregar categoría";
                this.nombre = '';
                this.tipo_documento = 'CEDULA';
                this.num_documento = '';
                this.direccion = '';
                this.telefono = '';
                this.email = '';
                this.tipoAccion = 1;
                break;
              }
              case "actualizar":
              {
                this.modal = 1;
                this.tituloModal = "Editar categoria";
                this.nombre = data["nombre"];
                this.tipo_documento = data["tipo_documento"];
                this.num_documento = data["num_documento"];
                this.direccion = data["direccion"];
                this.telefono = data["telefono"];
                this.email = data["email"];
                this.proveedor_id = data["id"];
                this.tipoAccion = 2;          
                break;
              }
            }
          }
        }
      }
    },

    mounted() {
      this.listarProveedor(1, this.buscar, this.criterio);
    }

  }
</script>

<style>
  .modal-content{
    width:100% !important;
    position:absolute !important;
  }

  .mostrar{
    display:list-item !important;
    opacity:1 !important;
    position:absolute !important;
    background-color: #3c29297a !important;
  }

  .div-error{
    display:flex;
    justify-content:center;
  }

  .text-error{
    color: red !important;
    font-weight: bold;
    font-size: 20px;
  }
</style>