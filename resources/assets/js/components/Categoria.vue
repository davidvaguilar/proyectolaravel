<template>
  <main class="main">
    <!-- Breadcrumb class="breadcrumb" -->
    <ol>
    <!--  <li class="breadcrumb-item active"><a href="/">SISTEMA DE COMPRA Y VENTA - CATEGORIAS</a></li> -->
    </ol>  
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col">
              <h2>Listado de Categorías</h2>
            </div>
            <div class="col text-right">
              <button class="btn btn-primary btn-lg" type="button" @click="abrirModal( 'categoria', 'registrar')">
                <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Categoría
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-8">
              <div class="input-group">
                <select class="form-control col-md-5" v-model="criterio">
                  <option value="nombre">Categoría</option>
                  <option value="descripcion">Descripción</option>
                </select>
                <input type="text" @keyup.enter="listarCategoria(1, buscar, criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                <button type="submit" @click="listarCategoria(1, buscar, criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm text-center">
            <thead>
              <tr class="bg-primary">
                <th>Categoría</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Editar</th>
                <th>Cambiar Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="categoria in arrayCategoria" :key="categoria.id">        
                <td v-text="categoria.nombre"></td>
                <td v-text="categoria.descripcion"></td>
                <td>            
                  <i class="fa fa-check fa-2x" v-if="categoria.condicion"></i>              
                  <i class="fa fa-times fa-2x" v-else></i>                 
                </td>
                <td>
                  <button type="button" class="btn btn-info btn-md" @click="abrirModal( 'categoria', 'actualizar', categoria)">
                    <i class="fa fa-edit fa-2x"></i>
                  </button> &nbsp;
                </td>
                <td>         
                  <template v-if="categoria.condicion">              
                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)">
                      <i class="fa fa-lock fa-2x"></i> Desactivar
                    </button>
                  </template>
                  <template v-else>              
                    <button type="button" class="btn btn-success btn-sm" @click="activarCategoria(categoria.id)">
                      <i class="fa fa-check fa-2x"></i> Activar
                    </button>
                  </template>
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
            <div v-show="errorCategoria" class="form-group row div-error">                              
              <div class="text-center text-error">                                  
                <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error"></div>
              </div>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                <div class="col-md-9">
                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                <div class="col-md-9">
                <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripcion">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
            <button type="button" @click="registrarCategoria()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
            <button type="button" @click="actualizarCategoria()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>

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
        categoria_id: 0,
        nombre: '',
        descripcion: '',
        arrayCategoria: [],
        modal: 0,
        tituloModal: '',
        tipoAccion: 0,
        errorCategoria: 0,
        errorMostrarMsjCategoria: [],
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
      listarCategoria(page, buscar, criterio){
        let me = this;
        var url = '/categoria?page=' + page+ '&buscar=' + buscar + '&criterio=' + criterio;

        axios.get(url).then(function(response){
          var respuesta = response.data;
          me.arrayCategoria = respuesta.categorias.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error){
          console.log(error);
        });
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        me.pagination.current_page = page;
        me.listarCategoria(page, buscar, criterio);
      },
      registrarCategoria(){
        if( this.validarCategoria() ){
          return;
        }
        let me = this;
        axios.post('/categoria/registrar', {
          'nombre': this.nombre,
          'descripcion': this.descripcion
        }).then(function(response){
          me.cerrarModal();
          me.listarCategoria(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      actualizarCategoria(){
        if( this.validarCategoria() ){
          return;
        }
        let me = this;
        axios.put('/categoria/actualizar', {
          'id': this.categoria_id,
          'nombre': this.nombre,
          'descripcion': this.descripcion
        }).then(function(response){
          me.cerrarModal();
          me.listarCategoria(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      desactivarCategoria(id){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
          title: '¿Estas seguro de desactivar la categoria?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
          cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
            axios.put('/categoria/desactivar', {
              'id' : id
            }).then(function(response){
              me.listarCategoria(1, '', 'nombre');
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
      activarCategoria(id){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
          title: '¿Estas seguro de activar la categoria?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
          cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
            axios.put('/categoria/activar', {
              'id' : id
            }).then(function(response){
              me.listarCategoria(1, '', 'nombre');
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
      },
      validarCategoria(){
        this.errorCategoria = 0;
        this.errorMostrarMsjCategoria = [];
        if( !this.nombre ){
          this.errorMostrarMsjCategoria.push("(*)El nombre de la categoria no puede estar vacio");
        }
        if( this.errorMostrarMsjCategoria.length ){
          this.errorCategoria = 1;
        }
        return this.errorCategoria;
      },
      cerrarModal(){
        this.modal = 0;
        this.tituloModal = "";
        this.nombre = "";
        this.descripcion = "";
      },
      abrirModal( modelo, accion, data=[] ){
        switch( modelo ){
          case "categoria":
          {
            switch( accion ){
              case "registrar":
              {
                this.modal = 1;
                this.tituloModal = "Agregar categoría";
                this.nombre = "";
                this.descripcion = "";
                this.tipoAccion = 1;
                break;
              }
              case "actualizar":
              {
                this.modal = 1;
                this.tituloModal = "Editar categoria";
                this.tipoAccion = 2;
                this.categoria_id = data["id"];
                this.nombre = data["nombre"];
                this.descripcion = data["descripcion"];
                break;
              }
            }
          }
        }
      }
    },

    mounted() {
      this.listarCategoria(1, this.buscar, this.criterio);
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