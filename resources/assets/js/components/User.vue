<template>
  <main class="main">
    <!-- Breadcrumb  class="breadcrumb" -->
    <ol >
      <!-- <li class="breadcrumb-item active"><a href="/">SISTEMA DE COMPRA Y VENTA - CLIENTES</a></li> -->
    </ol> 
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col">
              <h2>Listado de Usuarios</h2>
            </div>
            <div class="col text-right">
              <button class="btn btn-primary btn-lg" type="button" @click="abrirModal( 'usuario', 'registrar' )">
                <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Usuario
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
                <input type="text" @keyup.enter="listarUsuario(1, buscar, criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                <button type="submit" @click="listarUsuario(1, buscar, criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm text-center">
              <thead>
                <tr class="bg-primary">
                  <th>Nombre</th>
                  <th>Tipo de Documento</th>
                  <th>Numero</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th>Usuario</th>
                  <th>Rol</th>
                  <th>Imagen</th>
                  <th>Editar</th>
                  <th>Cambiar estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="usuario in arrayUsuario" :key="usuario.id">        
                  <td v-text="usuario.nombre"></td>
                  <td v-text="usuario.tipo_documento"></td>
                  <td v-text="usuario.num_documento"></td>
                  <td v-text="usuario.telefono"></td>
                  <td v-text="usuario.email"></td>
                  <td v-text="usuario.usuario"></td>          
                  <td v-text="usuario.rol"></td>         
                  <td>
                    <img :src="'img/usuario/'+usuario.imagen" class="img-responsive" width="50px" height="50px">
                  </td>
                  <td>
                    <button type="button" class="btn btn-info btn-md" @click="abrirModal( 'usuario', 'actualizar', usuario)" title="EDITAR">
                      <i class="fa fa-edit fa-2x"></i>
                    </button> &nbsp;
                  </td> 
                  <td>         
                    <template v-if="usuario.condicion">              
                      <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(usuario.id)">
                        <i class="fa fa-lock fa-2x"></i> Desactivar
                      </button>
                    </template>
                    <template v-else>              
                      <button type="button" class="btn btn-success btn-sm" @click="activarUsuario(usuario.id)">
                        <i class="fa fa-check fa-2x"></i> Activar
                      </button>
                    </template>
                  </td>               
                </tr>                  
              </tbody>
            </table>
          </div>
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
            <div v-show="errorUsuario" class="form-group row div-error">                              
              <div class="text-center text-error">                                  
                <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error"></div>
              </div>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                <div class="col-md-9">
                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del usuario">                    
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

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                <div class="col-md-9">
                  <select v-model="idrol" class="form-control">
                    <option value="0">Seleccione un Rol</option>
                    <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                  </select>                   
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                <div class="col-md-9">
                  <input type="text" v-model="usuario" class="form-control" placeholder="Usuario">                    
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Password</label>
                <div class="col-md-9">
                    <input type="password" v-model="password" class="form-control">                    
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Imagen</label>
                <div class="col-md-9">                  
                    <!--poniendo :src se llama a la variable imagen que esta declarada en la propiedad data-->
                      <!--poner this.imagen=""; en cerrarModal para limpiar el campo ya que aparecia la imagen al registrar un registro-->
                  <div v-if="tipoAccion == 1">
                    <input type="file" @change="subirImagen" class="form-control" placeholder="">
                    <img :src="imagen" class="img-responsive" width="100px" height="100px">
                  </div>                   
                  <div v-if="tipoAccion == 2">
                    <input type="file" @change="subirImagen" class="form-control" placeholder="">
                    <img :src="imagen" class="img-responsive" width="100px" height="100px"> 
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
            <button type="button" @click="registrarUsuario()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
            <button type="button" @click="actualizarUsuario()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>
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
        usuario_id: 0,
        nombre: '',
        tipo_documento: 'CEDULA',
        num_documento: '',
        direccion: '',
        telefono: '',
        email: '',
        usuario: '',
        password: '',
        idrol: 0,
        imagen: '',
        arrayUsuario: [],
        arrayRol: [],
        modal: 0,
        tituloModal: '',
        tipoAccion: 0,
        errorUsuario: 0,
        errorMostrarMsjUsuario: [],
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
      listarUsuario(page, buscar, criterio){
        let me = this;
        var url = '/usuario?page=' + page+ '&buscar=' + buscar + '&criterio=' + criterio;

        axios.get(url).then(function(response){
          var respuesta = response.data;
          me.arrayUsuario = respuesta.usuarios.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error){
          console.log(error);
        });
      },
      selectRol(){
        let me = this;
        var url = '/rol/selectRol';

        axios.get(url).then(function(response){
          var respuesta = response.data;
          me.arrayRol = respuesta.roles;
        })
        .catch(function (error){
          console.log(error);
        });
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        me.pagination.current_page = page;
        me.listarUsuario(page, buscar, criterio);
      },
      registrarUsuario(){
        if( this.validarUsuario() ){
          return;
        }
        let me = this;
        axios.post('/usuario/registrar', {
          'nombre': this.nombre,
          'tipo_documento': this.tipo_documento,
          'num_documento': this.num_documento,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'email': this.email,
          'usuario': this.usuario,
          'password': this.password,
          'idrol': this.idrol,
          'imagen': this.imagen
        }).then(function(response){
          me.cerrarModal();
          me.listarUsuario(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      subirImagen(e){                
        let me = this;
        if( e.target.files.length == 1 ){ //Bloque if else Creacion 
          let file = e.target.files[0];
          console.log(file);
          let reader = new FileReader();
          reader.onloadend = (file) => {            
              //console.log('RESULT', reader.result)
              me.imagen = reader.result;
          }
          reader.readAsDataURL(file);
        } else {
          me.imagen = "";
        }
      },
      actualizarUsuario(){
        if( this.validarUsuario() ){
          return;
        }
        let me = this;
        axios.put('/usuario/actualizar', {
          'id': this.usuario_id,
          'nombre': this.nombre,
          'tipo_documento': this.tipo_documento,
          'num_documento': this.num_documento,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'email': this.email,
          'usuario': this.usuario,
          'password': this.password,
          'idrol': this.idrol,
          'imagen': this.imagen
        }).then(function(response){
          me.cerrarModal();
          me.listarUsuario(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      validarUsuario(){
        this.errorUsuario = 0;
        this.errorMostrarMsjUsuario = [];
        if( !this.nombre ){
          this.errorMostrarMsjUsuario.push("(*)El nombre no puede estar vacio");
        }
        if( !this.usuario ){
          this.errorMostrarMsjUsuario.push("(*)El nombre del usuario no puede estar vacio");
        }
        if( !this.password ){
          this.errorMostrarMsjUsuario.push("(*)El password del usuario no puede estar vacio");
        }
        if( this.idrol == 0 ){
          this.errorMostrarMsjUsuario.push("(*)Debe seleccionar un rol para el usuario");
        }
        if( this.imagen == 0 ){
          this.errorMostrarMsjUsuario.push("(*)Debe subir  una imagen");
        }
        if( this.errorMostrarMsjUsuario.length ){
          this.errorUsuario = 1;
        }
        return this.errorUsuario;
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
        this.usuario = '';
        this.password = '';
        this.idrol = 0;
        this.imagen = '';
        this.errorUsuario = 0;
      },
      abrirModal( modelo, accion, data=[] ){
        this.selectRol();

        switch( modelo ){
          case "usuario":
          {
            switch( accion ){
              case "registrar":
              {
                this.modal = 1;
                this.tituloModal = "Agregar usuario";
                this.nombre = '';
                this.tipo_documento = 'CEDULA';
                this.num_documento = '';
                this.direccion = '';
                this.telefono = '';
                this.email = '';
                this.usuario = '';
                this.password = '';
                this.idrol = 0;
                this.tipoAccion = 1;
                break;
              }
              case "actualizar":
              {
                this.modal = 1;
                this.tituloModal = "Editar usuario";
                this.nombre = data["nombre"];
                this.tipo_documento = data["tipo_documento"];
                this.num_documento = data["num_documento"];
                this.direccion = data["direccion"];
                this.telefono = data["telefono"];
                this.email = data["email"];
                this.usuario = data["usuario"];
                this.password = data["password"];
                this.idrol = data["idrol"];
                this.usuario_id = data["id"];
                this.tipoAccion = 2;          
                break;
              }
            }
          }
        }
      },
      desactivarUsuario(id){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
          title: '¿Estas seguro de desactivar el usuario?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
          cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
            axios.put('/usuario/desactivar', {
              'id' : id
            }).then(function(response){
              me.listarUsuario(1, '', 'nombre');
              swalWithBootstrapButtons.fire(
                'Desactivado!',
                'El registro ha sido desactivado con exito.',
                'success'
              )
            })
            .catch(function (error){
              console.log(error);        
            });
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ) {
           
          }
        })
      },
      activarUsuario(id){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
          title: '¿Estas seguro de activar el usuario?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
          cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
            axios.put('/usuario/activar', {
              'id' : id
            }).then(function(response){
              me.listarUsuario(1, '', 'nombre');
              swalWithBootstrapButtons.fire(
                'Activado!',
                'El registro ha sido activado con exito.',
                'success'
              )
            })
            .catch(function (error){
              console.log(error);        
            });
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ) {           
          }
        })
      }
    },

    mounted() {
      this.listarUsuario(1, this.buscar, this.criterio);
    }

  }
</script>

<style>
  .modal-content{
    width:100% !important;
    position:absolute !important;
  }

  .mostrar{
    height: 1000px;
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