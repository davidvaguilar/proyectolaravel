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
          <h2>Listado de Roles</h2>          
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-8">
              <div class="input-group">
                <select class="form-control col-md-5" v-model="criterio">
                  <option value="nombre">Rol</option>
                  <option value="descripcion">Descripción</option>
                </select>
                <input type="text" @keyup.enter="listarRol(1, buscar, criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                <button type="submit" @click="listarRol(1, buscar, criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm text-center">
            <thead>
              <tr class="bg-primary">
                <th>Rol</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="rol in arrayRol" :key="rol.id">        
                <td v-text="rol.nombre"></td>
                <td v-text="rol.descripcion"></td>
                <td>                  
                  <i class="fa fa-check fa-2x" v-if="rol.condicion"></i>               
                  <i class="fa fa-times fa-2x" v-else></i>                  
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
  </main>
</template>

<script>
  export default {

    data(){
      return {
        rol_id: 0,
        nombre: '',
        descripcion: '',
        arrayRol: [],  
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
      listarRol(page, buscar, criterio){
        let me = this;
        var url = '/rol?page=' + page+ '&buscar=' + buscar + '&criterio=' + criterio;

        axios.get(url).then(function(response){
          var respuesta = response.data;
          me.arrayRol = respuesta.roles.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error){
          console.log(error);
        });
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        me.pagination.current_page = page;
        me.listarRol(page, buscar, criterio);
      }
    },

    mounted() {
      this.listarRol(1, this.buscar, this.criterio);
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