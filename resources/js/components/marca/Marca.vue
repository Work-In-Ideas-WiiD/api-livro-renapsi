<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista Marcas</h3>
                    </div>
                    <div class="col-4 text-right">
                        <a v-if="$auth.check(1)" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary">Adicionar</a>
                    </div>
                </div>
            </div>
            
            <!-- Light table -->
            <search v-on:search="searchAdmin" ></search>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="status">Nome</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="marca in marcas.data" v-bind:key="marca.id" style="margin-bottom: 5px;">
                        <td>{{ marca.nome }}</td>
                        <td>{{ getHumanDate(marca.created_at) }}</td>
                        <td>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-primary" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="editMarca(marca)"><i class="fas fa-fw fa-edit"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(marca)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :data="marcas" @pagination-change-page="getMarcas"></pagination>
            </div>
          </div>
        </div>
      </div>

 <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Cadastrar Marca</small></h5>
            </div>   	
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-tag"></i></span>
                                </div>
                                <input class="form-control" v-model="nome" placeholder="Nome" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                <strong>{{errors.nome[0]}}</strong>
                            </span>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="postMarca" class="btn btn-primary my-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
                
            </div>
            
        </div>
    </div>

    <div class="modal fade" ref="modal" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Editar Marca</small></h5>
            </div>   	
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-tag"></i></span>
                                </div>
                                <input class="form-control" v-model="editMarcaData.nome" placeholder="Nome" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                <strong>{{errors.nome[0]}}</strong>
                            </span>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="updateMarca" class="btn btn-primary my-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
                
            </div>
            
        </div>
    </div>

    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Excluir Marca</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar a marca: <b>{{ deleteMarcaData.nome }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteMarca(deleteMarcaData)">Excluir</b-button>
        </div>
        </div>
    </div>
    </div>

</div>


</template>
<script>
import moment from 'moment/moment';
import search from '../search/Search.vue';
export default {
     data() {
      return {
        has_error: false,
        marcas: {},
        nome: null,
        errors: {},
        editMarcaData: {},
        deleteMarcaData: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
      }
    },
    mounted() {
      this.getMarcas()
    },
    methods: {
        searchAdmin(payload){
            this.$http({
                url: 'categoria',
                method: 'GET',
                params: {
                like: payload.dados.nome,
                order: payload.dados.ordem,
                mostrar: payload.dados.mostrar
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.users = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        hideModal() {
          $('#modal-form').removeClass('show')
        },
        getMarcas(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `marca?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.marcas = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      postMarca() {
        var app = this
        let formData = new FormData()
        formData.append('nome',  app.nome)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `marca`,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.hideModal();
                this.marcas.data.unshift(response.data);

                this.flashMessage.success({
                    message: 'Marca cadastrada com sucesso!',
                    time: 5000
                });

                this.nome = ''

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao cadastrado Marca!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        hideEditModal() {
          $('#modal-form-edit').removeClass('show')
        },
        editMarca(marca) {
          this.editMarcaData = marca;
          this.errors = {};
          // this.showEditModal();
        },
        updateMarca() {
        var app = this
        let formData = new FormData()
        formData.append('_method', 'PUT');
        formData.append('nome',  app.editMarcaData.nome)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `marca/` + app.editMarcaData.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.getMarcas();
                this.hideEditModal();

                this.flashMessage.success({
                    message: 'Marca atualizado com sucesso!',
                    time: 5000
                });

                this.nome = ''

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao atualizado marca!',
                            time: 5000
                        });
                        break;
                }
            })
        },
      showDelete(marca){
        this.deleteMarcaData = marca;
      },
      hideDeleteModal() {
        $('#modal-delete').removeClass('show')
      },
      deleteMarca(marca) {
    
        this.$http({
            url: `marca/` + marca.id,
            method: 'DELETE',
          })

          .then(response=>{		
            this.marcas.data = this.marcas.data.filter(obj => {
                return obj.id != marca.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Marca deletada com sucesso!',
                time: 5000
            });
            
          }, error =>{
            
             this.flashMessage.error({
                message: 'Error ao deletar Marca!',
                time: 5000
            });
          })
      },
    },
    components: {
      search
    }
}
</script>