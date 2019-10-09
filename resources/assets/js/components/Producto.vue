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
              <h2>Listado de Productos</h2>
            </div>
            <div class="col text-right">
              <button class="btn btn-primary btn-lg" type="button" @click="abrirModal( 'producto', 'registrar')">
                <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Producto
              </button>
              <button class="btn btn-success btn-lg" type="button" @click="cargarPdf()">
                <i class="fa fa-file fa-2x"></i>&nbsp;&nbsp;Reporte PDF
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-8">
              <div class="input-group">
                <select class="form-control col-md-5" v-model="criterio">
                  <option value="nombre">Nombre de Producto</option>
                  <option value="codigo">Codigo de Barra</option>
                </select>
                <input type="text" @keyup.enter="listarProducto(1, buscar, criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                <button type="submit" @click="listarProducto(1, buscar, criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm text-center">
              <thead>
                <tr class="bg-primary">
                  <th>Categoría</th>
                  <th>Producto</th>
                  <th>Codigo</th>
                  <th>Precio Venta</th>
                  <th>Stock</th>
                  <th>Imagen</th>
                  <th>Estado</th>
                  <th>Editar</th>
                  <th>Cambiar estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="producto in arrayProducto" :key="producto.id">        
                  <td v-text="producto.nombre_categoria"></td>
                  <td v-text="producto.nombre"></td>
                  <td v-text="producto.codigo"></td>
                  <td v-text="'$ '+producto.precio_venta"></td>
                  <td v-text="producto.stock"></td>
                  <td>
                    <img :src="'img/producto/'+producto.imagen" class="img-responsive" width="50px" height="50px">
                  </td>
                  <td>
                    <i class="fa fa-check fa-2x" v-if="producto.condicion"></i>
                    <i class="fa fa-times fa-2x" v-else></i>
                  </td>
                  <td>
                    <button type="button" class="btn btn-info btn-md" title="EDITAR" @click="abrirModal( 'producto', 'actualizar', producto)">
                      <i class="fa fa-edit fa-2x"></i>
                    </button> &nbsp;
                  </td>
                  <td>         
                    <template v-if="producto.condicion">              
                      <button type="button" class="btn btn-danger btn-sm" @click="desactivarProducto(producto.id)">
                        <i class="fa fa-lock fa-2x"></i> Desactivar
                      </button>
                    </template>
                    <template v-else>              
                      <button type="button" class="btn btn-success btn-sm" @click="activarProducto(producto.id)">
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
            <div v-show="errorProducto" class="form-group row div-error">                              
              <div class="text-center text-error">                                  
                <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error"></div>
              </div>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                <div class="col-md-9">
                  <select class="form-control" v-model="idcategoria">
                    <option value="0" disabled>Seleccione</option>
                    <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                <div class="col-md-9">
                  <input type="text" v-model="codigo" class="form-control" placeholder="Codigo de barras">
                  <barcode :value="codigo" :options="{format:'EAN-13'}">
                    Creando código de barras.
                  </barcode>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Producto</label>
                <div class="col-md-9">
                  <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del producto">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Precio Venta</label>
                <div class="col-md-9">
                  <input type="number" v-model="precio_venta" class="form-control" placeholder="Nombre del producto">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Stock</label>
                <div class="col-md-9">
                  <input type="number" v-model="stock" class="form-control" placeholder="Nombre del producto">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Imagen</label>
                <div class="col-md-9">
                    <!--poniendo :src se llama a la variable imagen que esta declarada en la propiedad data-->
                    <!--poner this.imagen=""; en cerrarModal para limpiar el campo ya que aparecia la imagen al registrar un registro-->
                  <div v-if="tipoAccion==1">
                    <input type="file" @change="subirImagen" class="form-control" placeholder="">
                    <img :src="imagen" class="img-responsive" width="100px" height="100px">
                  </div>
                  <div v-if="tipoAccion==2">
                    <input type="file" @change="subirImagen" class="form-control" placeholder="">      
                    <img :src="imagen"  width="100px" height="100px">                         
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
            <button type="button" @click="registrarProducto()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
            <button type="button" @click="actualizarProducto()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>
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
  import VueBarcode from 'vue-barcode';

  export default {

    data(){
      return {
        producto_id: 0,
        idcategoria: 0,
        nombre_categoria: '',
        codigo: '',
        nombre: '',
        precio_venta: 0,
        stock: 0,
        imagen: '',
        arrayProducto: [],
        modal: 0,
        tituloModal: '',
        tipoAccion: 0,
        errorProducto: 0,
        errorMostrarMsjProducto: [],
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
        buscar: '',
        arrayCategoria: []
      }
    },
    components: {
      'barcode': VueBarcode
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
      listarProducto(page, buscar, criterio){
        let me = this;
        var url = '/producto?page=' + page+ '&buscar=' + buscar + '&criterio=' + criterio;

        axios.get(url).then(function(response){
          var respuesta = response.data;
          me.arrayProducto = respuesta.productos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error){
          console.log(error);
        });
      },
      cargarPdf(){
        //alert(window.location.host);
        window.open('http://tienda.dyi.cl/producto/listarPdf', '_blank');
      },
      selectCategoria(){
        let me = this;
        var url = '/categoria/selectCategoria';

        axios.get(url).then(function(response){
          var respuesta = response.data;
          me.arrayCategoria = respuesta.categorias;        
        })
        .catch(function (error){
          console.log(error);
        });
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        me.pagination.current_page = page;
        me.listarProducto(page, buscar, criterio);
      },
      registrarProducto(){
        if( this.validarProducto() ){
          return;
        }
        let me = this;
        axios.post('/producto/registrar', {
          'idcategoria': this.idcategoria,
          'codigo': this.codigo,
          'nombre': this.nombre,
          'stock': this.stock,
          'precio_venta': this.precio_venta,
          'imagen': this.imagen
        }).then(function(response){
          me.cerrarModal();
          me.listarProducto(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      subirImagen(e){              
        let me = this;
        if( e.target.files.length == 1 ){
          let file = e.target.files[0];      
            //console.log(file);
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
      actualizarProducto(){
        if( this.validarProducto() ){
          return;
        }
        let me = this;
        axios.put('/producto/actualizar', {
          'idcategoria': this.idcategoria,
          'codigo': this.codigo,
          'nombre': this.nombre,
          'stock': this.stock,
          'precio_venta': this.precio_venta,
          'imagen': this.imagen,
          'id': this.producto_id
        }).then(function(response){
          me.cerrarModal();
          me.listarProducto(1, '', 'nombre');
        })
        .catch(function (error){
          console.log(error);        
        });
      },
      desactivarProducto(id){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
          title: '¿Estas seguro de desactivar el producto?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
          cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
            axios.put('/producto/desactivar', {
              'id' : id
            }).then(function(response){
              me.listarProducto(1, '', 'nombre');
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
      activarProducto(id){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
          title: '¿Estas seguro de activar el producto?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check fa-2x"></i> Aceptar',
          cancelButtonText: '<i class="fa fa-times fa-2x"></i> Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
            axios.put('/producto/activar', {
              'id' : id
            }).then(function(response){
              me.listarProducto(1, '', 'nombre');
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
      validarProducto(){
        this.errorProducto = 0;
        this.errorMostrarMsjProducto = [];
        if( this.idcategoria == 0 ){
          this.errorMostrarMsjProducto.push("(*)Selecciona una categoria");
        }
        if( !this.nombre ){
          this.errorMostrarMsjProducto.push("(*)El nombre del producto no puede estar vacio");
        }
        if( !this.precio_venta ){
          this.errorMostrarMsjProducto.push("(*)El precio venta del producto debe ser un numero y no puede estar vacio");
        }
        if( !this.stock ){
          this.errorMostrarMsjProducto.push("(*)El stock del producto debe ser un numero y no puede estar vacio");
        }
        if( !this.imagen ){
          this.errorMostrarMsjProducto.push("(*)Debe subir una imagen");
        }
        if( this.errorMostrarMsjProducto.length ){
          this.errorProducto = 1;
        }
        return this.errorProducto;
      },
      cerrarModal(){
        this.modal = 0;
        this.tituloModal = "";
        this.idcategoria = 0;
        this.nombre_categoria = "";
        this.codigo = "";
        this.nombre = "";
        this.precio_venta = 0;
        this.stock = 0;
        this.imagen = "";
        this.errorProducto = 0;        
      },
      abrirModal( modelo, accion, data=[] ){
        switch( modelo ){
          case "producto":
          {
            switch( accion ){
              case "registrar":
              {
                this.modal = 1;
                this.tituloModal = "Agregar producto";
                this.idcategoria = 0;
                this.nombre_categoria = "";
                this.codigo = "";
                this.nombre = "";
                this.precio_venta = 0;
                this.stock = 0;
                this.tipoAccion = 1;
                break;
              }
              case "actualizar":
              {
                this.modal = 1;
                this.tituloModal = "Editar producto";
                this.producto_id = data["id"];
                this.idcategoria = data["idcategoria"];
                this.codigo = data["codigo"];
                this.nombre = data["nombre"];
                this.precio_venta = data["precio_venta"];
                this.stock = data["stock"];
                this.tipoAccion = 2;             
                break;
              }
            }
          }
        }
        this.selectCategoria();
      }
    },

    mounted() {
      this.listarProducto(1, this.buscar, this.criterio);
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