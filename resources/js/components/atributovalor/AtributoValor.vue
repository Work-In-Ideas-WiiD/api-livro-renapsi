<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <!-- <h3 class="mb-0">Lista Atributos</h3> -->
                        <h3 v-if="this.atributovalores.data" class="mb-0"> {{this.atributovalores.data[0].atributo.nome}} </h3>
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
                  <th scope="col" class="sort" data-sort="status">Valor</th>
                    <th scope="col" class="sort" data-sort="status">Atributo</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="atributovalor in atributovalores.data" v-bind:key="atributovalor.id" style="margin-bottom: 5px;">
                        <td>{{ atributovalor.valor }}</td>
                        <td>{{ atributovalor.atributo.nome }}</td>
                        <td>{{ getHumanDate(atributovalor.created_at) }}</td>
                        <td>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-primary" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="editAtributoValor(atributovalor)"><i class="fas fa-fw fa-edit"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(atributovalor)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :data="atributovalores" @pagination-change-page="getAtributoValores"></pagination>
            </div>
          </div>
        </div>
      </div>

 <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Cadastrar Valor</small></h5>
            </div>   	
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-collection"></i></span>
                                </div>
                                <input class="form-control" v-model="valor" placeholder="Valor" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.valor">
                                <strong>{{errors.valor[0]}}</strong>
                            </span>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="postAtributoValor" class="btn btn-primary my-4">Salvar</button>
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
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Editar Valor</small></h5>
            </div>   	
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-collection"></i></span>
                                </div>
                                <input class="form-control" v-model="editAtributoValorData.valor" placeholder="Valor" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.valor">
                                <strong>{{errors.valor[0]}}</strong>
                            </span>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="updateAtributoValor" class="btn btn-primary my-4">Salvar</button>
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
                <p>Tem certeza que deseja deletar o valor: <b>{{ deleteAtributoValorData.valor }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteAtributo(deleteAtributoValorData)">Excluir</b-button>
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
        atributovalores: {},
        valor: null,
        errors: {},
        editAtributoValorData: {},
        deleteAtributoValorData: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
      }
    },
    mounted() {
      this.getAtributoValores()
    },
    methods: {
        searchAdmin(payload){
            this.$http({
                url: 'atributo_valor',
                method: 'GET',
                params: {
                like: payload.dados.nome,
                order: payload.dados.ordem,
                mostrar: payload.dados.mostrar,
                atributo_id: this.$route.params.id,
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
        getAtributoValores(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `atributo_valor?page=` + page,
          method: 'GET',
          params: {
            atributo_id: this.$route.params.id,
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.atributovalores = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      postAtributoValor() {
        var app = this
        let formData = new FormData()
        formData.append('valor',  app.valor)
        formData.append('atributo_id',  this.$route.params.id)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `atributo_valor`,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.getAtributoValores();
                this.hideModal();

                this.flashMessage.success({
                    message: 'Valor cadastrado com sucesso!',
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
                            message: 'Error ao cadastrado valor!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        hideEditModal() {
          $('#modal-form-edit').removeClass('show')
        },
        editAtributoValor(atributovalor) {
          this.editAtributoValorData = atributovalor;
          this.errors = {};
          // this.showEditModal();
        },
        updateAtributoValor() {
        var app = this
        let formData = new FormData()
        formData.append('_method', 'PUT');
        formData.append('valor',  app.editAtributoValorData.valor)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `atributo_valor/` + app.editAtributoValorData.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.getAtributoValores();
                this.hideEditModal();

                this.flashMessage.success({
                    message: 'Valor atualizado com sucesso!',
                    time: 5000
                });

                this.valor = ''

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao atualizado valor!',
                            time: 5000
                        });
                        break;
                }
            })
        },
      showDelete(atributovalor){
        this.deleteAtributoData = atributovalor;
      },
      hideDeleteModal() {
        $('#modal-delete').removeClass('show')
      },
      deleteAtributo(atributovalor) {
    
        this.$http({
            url: `atributo_valor/` + atributovalor.id,
            method: 'DELETE',
          })

          .then(response=>{		
            this.atributovalores.data = this.atributovalores.data.filter(obj => {
                return obj.id != atributovalor.id;
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