<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista Entrega</h3>
                    </div>
                    <div class="col-4 text-right">
                        <a href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary">Adicionar</a>
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
                    <th scope="col" class="sort" data-sort="status">Descrição</th>
                    <th scope="col" class="sort" data-sort="status">É grátis</th>
                    <th scope="col" class="sort" data-sort="status">Custo</th>
                    <th scope="col" class="sort" data-sort="status">Status</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="entrega in entregas.data" v-bind:key="entrega.id" style="margin-bottom: 5px;">
                        <td>{{ entrega.nome }}</td>
                        <td>{{ entrega.descricao }}</td>
                         <td v-if="entrega.entrega_gratis == 1"><a class="badge badge-pill badge-success"> É grátis? </a> </td>
                         <td v-else ><a class="badge badge-pill badge-danger"> É grátis? </a> </td>
                        <td>{{ formatPrice(entrega.preco) }}</td>
                         <td v-if="entrega.status == 1"><a class="badge badge-pill badge-success"> Ativo </a> </td>
                         <td v-else ><a class="badge badge-pill badge-danger"> Inativo </a> </td>
                        <td>
                            <button class="btn-circle btn-sm btn-primary" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="editEntrega(entrega)"><i class="fas fa-fw fa-edit"></i></button>
                            <button class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(entrega)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :data="entregas" @pagination-change-page="getEntregas"></pagination>
            </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Cadastrar Entrega</small></h5>
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
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-send"></i></span>
                                    </div>
                                    <input class="form-control" v-model="url" placeholder="URL" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.url">
                                    <strong>{{errors.url[0]}}</strong>
                                </span>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                        </div>
                                        <input class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="preco" placeholder="Custo" type="text">
                                    </div>
                                    <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.preco">
                                        <strong>{{errors.preco[0]}}</strong>
                                    </span>
                                </div>
                            <div class="form-group col-md-6">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-delivery-fast"></i></span>
                                        </div>
                                        <input class="form-control" v-mask="['####']" v-model="prazo" placeholder="Prazo" type="text">
                                    </div>
                                    <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.prazo">
                                        <strong>{{errors.prazo[0]}}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                        </div>
                                        <b-form-select v-model="entrega_gratis" :options="opcoes_entrega"></b-form-select>
                                    </div>

                                    <div class="invalid-feedback" v-if="errors.entrega_gratis">
                                        <p>{{errors.entrega_gratis[0]}}</p>
                                    </div>
                                </div>
                            <div class="form-group col-md-6">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                        </div>
                                        <b-form-select v-model="status" :options="opcoes"></b-form-select>
                                    </div>

                                    <div class="invalid-feedback" v-if="errors.status">
                                        <p>{{errors.status[0]}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                    </div>
                                    <b-form-select v-model="tipo_entrega" :options="opcoes_tipo_entrega"></b-form-select>
                                </div>

                                <div class="invalid-feedback" v-if="errors.tipo_entrega">
                                    <p>{{errors.tipo_entrega[0]}}</p>
                                </div>
                            </div>

                            <div class="form-group" v-if="tipo_entrega == 1">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                    </div>
                                    <b-form-select v-model="tipo_correio" :options="opcoes_tipo_correio"></b-form-select>
                                </div>

                                <div class="invalid-feedback" v-if="errors.tipo_correio">
                                    <p>{{errors.tipo_correio[0]}}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Descrição</label>
                                <textarea v-model="descricao" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                                <button type="button" @click="postEntrega" class="btn btn-primary my-4">Salvar</button>
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
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Editar Entrega</small></h5>
            </div>
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-tag"></i></span>
                                </div>
                                <input class="form-control" v-model="editEntregaData.nome" placeholder="Nome" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                <strong>{{errors.nome[0]}}</strong>
                            </span>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-send"></i></span>
                                </div>
                                <input class="form-control" v-model="editEntregaData.url" placeholder="URL" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.url">
                                <strong>{{errors.url[0]}}</strong>
                            </span>
                        </div>

                        <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                        </div>
                                        <input class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="editEntregaData.preco" placeholder="Custo" type="text">
                                    </div>
                                    <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.preco">
                                        <strong>{{errors.preco[0]}}</strong>
                                    </span>
                                </div>
                            <div class="form-group col-md-6">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-delivery-fast"></i></span>
                                        </div>
                                        <input class="form-control" v-mask="['####']" v-model="editEntregaData.prazo" placeholder="Prazo" type="text">
                                    </div>
                                    <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.prazo">
                                        <strong>{{errors.prazo[0]}}</strong>
                                    </span>
                                </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                    </div>
                                    <b-form-select v-model="editEntregaData.entrega_gratis" :options="opcoes_entrega"></b-form-select>
                                </div>

                                <div class="invalid-feedback" v-if="errors.entrega_gratis">
                                    <p>{{errors.entrega_gratis[0]}}</p>
                                </div>
                            </div>
                        <div class="form-group col-md-6">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                    </div>
                                    <b-form-select v-model="editEntregaData.status" :options="opcoes"></b-form-select>
                                </div>

                                <div class="invalid-feedback" v-if="errors.status">
                                    <p>{{errors.status[0]}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                </div>
                                <b-form-select v-model="editEntregaData.tipo_entrega" :options="opcoes_tipo_entrega"></b-form-select>
                            </div>

                            <div class="invalid-feedback" v-if="errors.tipo_entrega">
                                <p>{{errors.tipo_entrega[0]}}</p>
                            </div>
                        </div>

                        <div class="form-group" v-if="editEntregaData.tipo_entrega == 1">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                </div>
                                <b-form-select v-model="editEntregaData.tipo_correio" :options="opcoes_tipo_correio"></b-form-select>
                            </div>

                            <div class="invalid-feedback" v-if="errors.tipo_correio">
                                <p>{{errors.tipo_correio[0]}}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Descrição</label>
                            <textarea v-model="editEntregaData.descricao" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>

                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="updateEntrega" class="btn btn-primary my-4">Salvar</button>
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
            <h5 class="modal-title" id="exampleModalLabel">Excluir Entrega</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar a entrega: <b>{{ deleteEntregaData.nome }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteEntrega(deleteEntregaData)">Excluir</b-button>
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
        entregas: {},
        nome: '',
        descricao: '',
        url: '',
        entrega_gratis: 2,
        status: 1,
        tipo_entrega: 0,
        tipo_correio: 'sedex',
        prazo: '',
        preco: '',
        errors: {},
        editEntregaData: {},
        deleteEntregaData: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
        opcoes: [
          { text: 'Ativo', value: 1 },
          { text: 'Inativo', value: 2 },
        ],
        opcoes_entrega: [
          { text: 'Sim Gratis', value: 1 },
          { text: 'Não Gratis', value: 2 },
        ],
        opcoes_tipo_entrega: [
          { text: 'Transportadora', value: 0 },
          { text: 'Correio', value: 1 },
        ],
        opcoes_tipo_correio: [
          { text: 'Pac', value: 'pac' },
          { text: 'Sedex', value: 'sedex' },
        ],
      }
    },
    mounted() {
      this.getEntregas()
    },
    methods: {
        formatPrice(value) {
          let val = (value/1).toFixed(2).replace('.', ',')
          return 'R$ ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        searchAdmin(payload){
            this.$http({
                url: 'entrega',
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
                this.entregas = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        hideModal() {
          $('#modal-form').modal('hide')
        },
        getEntregas(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `entrega?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.entregas = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      postEntrega() {
        var app = this
        let formData = new FormData()
        formData.append('nome',  app.nome)
        formData.append('url',  app.url)
        formData.append('descricao',  app.descricao)
        formData.append('entrega_gratis',  app.entrega_gratis)
        formData.append('status',  app.status)
        formData.append('tipo_entrega',  app.tipo_entrega)
        formData.append('tipo_correio',  app.tipo_correio)
        formData.append('prazo',  app.prazo)
        formData.append('preco',  app.preco)

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `entrega`,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{

                this.hideModal();
                this.entregas.data.unshift(response.data);

                this.flashMessage.success({
                    message: 'Entrega cadastrada com sucesso!',
                    time: 5000
                });

                this.nome = ''
                this.url = ''
                this.descricao = ''
                this.preco = ''
                this.prazo = ''

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao cadastrado Entrega!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        hideEditModal() {
          $('#modal-form-edit').modal('hide')
        },
        editEntrega(entrega) {
          this.editEntregaData = entrega;
          this.errors = {};
          // this.showEditModal();
        },
        updateEntrega() {
        var app = this
        let formData = new FormData()
        formData.append('_method', 'PUT');
        formData.append('nome',  app.editEntregaData.nome)
        formData.append('url',  app.editEntregaData.url)
        formData.append('descricao',  app.editEntregaData.descricao)
        formData.append('entrega_gratis',  app.editEntregaData.entrega_gratis)
        formData.append('status',  app.editEntregaData.status)
        formData.append('tipo_entrega',  app.editEntregaData.tipo_entrega)
        formData.append('tipo_correio',  app.editEntregaData.tipo_correio)
        formData.append('prazo',  app.editEntregaData.prazo)
        formData.append('preco',  app.editEntregaData.preco)

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `entrega/` + app.editEntregaData.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{

                this.getEntregas();
                this.hideEditModal();

                this.flashMessage.success({
                    message: 'Entrega atualizada com sucesso!',
                    time: 5000
                });

                this.nome = ''
                this.url = ''
                this.descricao = ''
                this.preco = ''
                this.prazo = ''

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
      showDelete(entrega){
        this.deleteEntregaData = entrega;
      },
      hideDeleteModal() {
        $('#modal-delete').modal('hide')
      },
      deleteEntrega(entrega) {

        this.$http({
            url: `entrega/` + entrega.id,
            method: 'DELETE',
          })

          .then(response=>{
            this.entregas.data = this.entregas.data.filter(obj => {
                return obj.id != entrega.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Entrega deletada com sucesso!',
                time: 5000
            });

          }, error =>{

             this.flashMessage.error({
                message: 'Error ao deletar Entrega!',
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
