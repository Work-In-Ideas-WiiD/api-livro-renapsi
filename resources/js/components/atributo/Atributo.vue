<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista Atributos</h3>
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
                    <tr v-for="atributo in atributos.data" v-bind:key="atributo.id" style="margin-bottom: 5px;">
                        <td>{{ atributo.nome }}</td>
                        <td>{{ getHumanDate(atributo.created_at) }}</td>
                        <td>
                            <button class="btn-circle btn-sm btn-info" title="Valores" @click="$router.push({name: 'atributovalor.admin', params: { id: atributo.id },})" > <i class="fas fa-fw fa-eye"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-primary" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="editAtributo(atributo)"><i class="fas fa-fw fa-edit"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(atributo)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :data="atributos" @pagination-change-page="getAtributos"></pagination>
            </div>
          </div>
        </div>
      </div>

 <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Cadastrar Atributo</small></h5>
            </div>   	
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-folder-17"></i></span>
                                </div>
                                <input class="form-control" v-model="nome" placeholder="Nome" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                <strong>{{errors.nome[0]}}</strong>
                            </span>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="postAtributo" class="btn btn-primary my-4">Salvar</button>
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
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Editar Atributo</small></h5>
            </div>   	
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-folder-17"></i></span>
                                </div>
                                <input class="form-control" v-model="editAtributoData.nome" placeholder="Nome" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                <strong>{{errors.nome[0]}}</strong>
                            </span>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="updateAtributo" class="btn btn-primary my-4">Salvar</button>
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
            <h5 class="modal-title" id="exampleModalLabel">Excluir Atributo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar a atributo: <b>{{ deleteAtributoData.nome }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteAtributo(deleteAtributoData)">Excluir</b-button>
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
        atributos: {},
        nome: null,
        password: null,
        password_confirmation: null,
        errors: {},
        editAtributoData: {},
        deleteAtributoData: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
      }
    },
    mounted() {
      this.getAtributos()
    },
    methods: {
        searchAdmin(payload){
            this.$http({
                url: 'atributo',
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
        getAtributos(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `atributo?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.atributos = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      postAtributo() {
        var app = this
        let formData = new FormData()
        formData.append('nome',  app.nome)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `atributo`,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.hideModal();
                this.atributos.data.unshift(response.data);

                this.flashMessage.success({
                    message: 'Atributo cadastrado com sucesso!',
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
                            message: 'Error ao cadastrado atributo!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        hideEditModal() {
          $('#modal-form-edit').removeClass('show')
        },
        editAtributo(atributo) {
          this.editAtributoData = atributo;
          this.errors = {};
          // this.showEditModal();
        },
        updateAtributo() {
        var app = this
        let formData = new FormData()
        formData.append('_method', 'PUT');
        formData.append('nome',  app.editAtributoData.nome)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `atributo/` + app.editAtributoData.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.getAtributos();
                this.hideEditModal();

                this.flashMessage.success({
                    message: 'Atributo atualizado com sucesso!',
                    time: 5000
                });

                this.nome = '',
                this.descricao = '',
                this.imagem = null,
                this.url_imagem = null

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao atualizado Atributo!',
                            time: 5000
                        });
                        break;
                }
            })
        },
      showDelete(categoria){
        this.deleteAtributoData = categoria;
      },
      hideDeleteModal() {
        $('#modal-delete').removeClass('show')
      },
      deleteAtributo(atributo) {
    
        this.$http({
            url: `atributo/` + atributo.id,
            method: 'DELETE',
          })

          .then(response=>{		
            this.atributos.data = this.atributos.data.filter(obj => {
                return obj.id != atributo.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Atributo deletada com sucesso!',
                time: 5000
            });
            
          }, error =>{
            
             this.flashMessage.error({
                message: 'Error ao deletar Atributo!',
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