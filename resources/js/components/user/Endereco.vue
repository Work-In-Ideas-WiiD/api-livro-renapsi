<template>
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="col-md-2" style="margin-top: 10px;">
                <a @click="$router.go(-1)" > <i class="far fa-times-circle"></i></a>
            </div>
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista de endereços</h3>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add-address" block>Adicionar</button>
                    </div>
                </div>
            </div>
            
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="budget">Nome</th>
                    <th scope="col" class="sort" data-sort="budget">Endereço</th>
                    <th scope="col" class="sort" data-sort="status">Tipo</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="endereco in enderecos" v-bind:key="endereco.id" style="margin-bottom: 5px;">
                        <td>{{ endereco.nome }}</td>
                        <td>
                            {{ capitalize(endereco.rua) }}{{ capitalize(endereco.numero != null ? ', ' + endereco.numero : '') }} | {{ capitalize(endereco.bairro) }} | 
                            {{ capitalize(endereco.cidade) }} - {{ endereco.estado.toUpperCase() }}{{ endereco.complemento != null ? ' | ' + capitalize(endereco.complemento) : '' }}
                        </td>
                        <td>{{ endereco.tipo }}</td>
                        <td>{{ getHumanDate(endereco.created_at) }}</td>
                        <td>
                            <button class="btn-circle btn-sm btn-primary" data-toggle="modal" data-target="#modal-form-edit" block @click="editEndereco(endereco)"><i class="fas fa-fw fa-edit"></i></button>
                            <button class="btn-circle btn-sm btn-danger" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(endereco)"><i class="fas fa-fw fa-trash-alt"></i></button>
                            <button v-if="endereco.tipo != 'Principal'" class="btn-circle btn-sm btn-info" block @click="setMain(endereco.id)"><i class="fas fa-fw fa-check"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>
            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :limit="12" :data="clientes" @pagination-change-page="getUsers"></pagination>
            </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Excluir Endereço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-block">
                    <p>Tem certeza que deseja deletar o endereço: <b>{{ deleteEnderecoData.rua }}</b></p>
                </div>
                <b-button class="mt-3" variant="outline-danger" block @click="deleteEndereco(deleteEnderecoData)">Excluir</b-button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add-address" tabindex="-1" role="dialog" aria-labelledby="modal-create" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Endereço</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body px-lg-5 row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-folder-17"></i></span>
                                    </div>
                                    <input class="form-control" v-model="adicionar.nome" placeholder="Descrição do endereço" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                    <strong>{{errors.nome[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                                    </div>
                                    <input class="form-control" v-model="adicionar.cep" placeholder="CEP" type="text" v-mask="'#####-###'" v-on:keyup="buscar('adicionar')">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.cep">
                                    <strong>{{errors.cep[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-address-book"></i></span>
                                    </div>
                                    <input class="form-control" v-model="adicionar.rua" placeholder="Rua / Avenida" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.rua">
                                    <strong>{{errors.rua[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input class="form-control" v-model="adicionar.bairro" placeholder="Bairro" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.bairro">
                                    <strong>{{errors.bairro[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <input class="form-control" v-model="adicionar.numero" placeholder="Número" type="number">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.numero">
                                    <strong>{{errors.numero[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                                    </div>
                                    <input class="form-control" v-model="adicionar.complemento" placeholder="Complemento" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.complemento">
                                    <strong>{{errors.complemento[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-lighter"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input disabled class="form-control" v-model="adicionar.cidade" placeholder="Cidade" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.cidade">
                                    <strong>{{errors.cidade[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-lighter"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input disabled class="form-control" v-model="adicionar.estado" placeholder="Estado" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.estado">
                                    <strong>{{errors.estado[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-12 text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="postEndereco()" class="btn btn-primary my-4">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Endereço</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body px-lg-5 row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-folder-17"></i></span>
                                    </div>
                                    <input class="form-control" v-model="editar.nome" placeholder="Descrição do endereço" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                    <strong>{{errors.nome[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                                    </div>
                                    <input class="form-control" v-model="editar.cep" placeholder="CEP" type="text" v-mask="'#####-###'" v-on:keyup="buscar('editar')">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.cep">
                                    <strong>{{errors.cep[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-address-book"></i></span>
                                    </div>
                                    <input class="form-control" v-model="editar.rua" placeholder="Rua / Avenida" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.rua">
                                    <strong>{{errors.rua[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input class="form-control" v-model="editar.bairro" placeholder="Bairro" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.bairro">
                                    <strong>{{errors.bairro[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <input class="form-control" v-model="editar.numero" placeholder="Número" type="number">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.numero">
                                    <strong>{{errors.numero[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                                    </div>
                                    <input class="form-control" v-model="editar.complemento" placeholder="Complemento" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.complemento">
                                    <strong>{{errors.complemento[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-lighter"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input disabled class="form-control" v-model="editar.cidade" placeholder="Cidade" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.cidade">
                                    <strong>{{errors.cidade[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-lighter"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input disabled class="form-control" v-model="editar.estado" placeholder="Estado" type="text">
                                </div>
                                <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.estado">
                                    <strong>{{errors.estado[0]}}</strong>
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-12 text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="postEditEndereco()" class="btn btn-primary my-4">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</template>
<script>
import moment from 'moment/moment';
export default {
    data() {
        return {
            has_error: false,
            enderecos: {},
            errors: {},
            deleteEnderecoData: {},
            adicionar: {
                nome: '',
                rua: '',
                numero: '',
                complemento: '',
                bairro: '',
                cidade: 'Goiânia',
                estado: 'GO',
                cep: ''
            },
            editar: {}
        }
    },
    mounted() {
      this.getEnderecos()
    },
    methods: {
        setMain(endereco_id) {
            this.$http({
                url: `endereco/set/main/${endereco_id}`,
                method: 'post'
            })
            .then(response => {		
                this.flashMessage.success({
                    message: 'Endereço alterado para principal!',
                    time: 5000
                })

                this.getEnderecos()
            }, error => {
                this.flashMessage.error({
                    message: 'Erro ao definir endereço como principal!',
                    time: 5000
                });
            })
        },
        getEnderecos() {
            this.$http({
                url: `endereco?cliente_id=` + this.$route.params.id,
                method: 'GET'
            }).then(response => {
                this.enderecos = response.data;
            }).catch(error=>{
                this.has_error = true
            })
        },
        postEndereco() {
            var app = this
            this.$http({
                url: `endereco`,
                method: 'post',
                params: {
                    cliente_id: app.$route.params.id,
                    nome: app.adicionar.nome,
                    rua: app.adicionar.rua,
                    numero: app.adicionar.numero,
                    complemento: app.adicionar.complemento,
                    bairro: app.adicionar.bairro,
                    cep: app.adicionar.cep,
                    cidade: app.adicionar.cidade,
                    estado: app.adicionar.estado
                }
            })
            .then(response => {		
                
                app.flashMessage.success({
                    message: 'Endereço cadastrado com sucesso!',
                    time: 5000
                })

                app.hideModal()
                app.getEnderecos()
            }, error => {
                switch(error.response.status){
                    case 422:
                        app.errors = error.response.data.errors;
                        app.flashMessage.error({
                            message: 'Erro nos dados do endereço, por favor revise as informações!',
                            time: 5000
                        });
                        break;
                    default:
                        app.hideModal()
                        app.flashMessage.error({
                            message: 'Erro ao cadastrar endereço!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        postEditEndereco() {
            var app = this
            this.$http({
                url: `endereco/${app.editar.id}`,
                method: 'put',
                params: {
                    cliente_id: app.$route.params.id,
                    nome: app.editar.nome,
                    rua: app.editar.rua,
                    numero: app.editar.numero,
                    complemento: app.editar.complemento,
                    bairro: app.editar.bairro,
                    cep: app.editar.cep,
                    cidade: app.editar.cidade,
                    estado: app.editar.estado
                }
            })
            .then(response => {		
                
                app.flashMessage.success({
                    message: 'Endereço atualizado com sucesso!',
                    time: 5000
                })

                app.hideEditModal()
                app.getEnderecos()
            }, error => {
                switch(error.response.status){
                    case 422:
                        app.errors = error.response.data.errors;
                        app.flashMessage.error({
                            message: 'Erro nos dados do endereço, por favor revise as informações!',
                            time: 5000
                        });
                        break;
                    default:
                        app.hideModal()
                        app.flashMessage.error({
                            message: 'Erro ao editar endereço!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        getHumanDate : function (date) {
            return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
        },
        hideModal() {
            $('#modal-add-address').modal('hide')
        },
        hideEditModal() {
            $('#modal-form-edit').modal('hide')
        },
        editEndereco(endereco) {
            this.editar.id = endereco.id;
            this.editar.nome = endereco.nome;
            this.editar.cep = endereco.cep;
            this.editar.rua = endereco.rua;
            this.editar.bairro = endereco.bairro;
            this.editar.complemento = endereco.complemento;
            this.editar.numero = endereco.numero;
            this.editar.cidade = endereco.cidade;
            this.editar.estado = endereco.estado;
            this.errors = {};
        },
        showDelete(endereco){
            this.deleteEnderecoData = endereco;
        },
        hideDeleteModal() {
            $('#modal-delete').modal('hide')
        },
        deleteEndereco(endereco) {

            if(endereco.tipo == 'Principal'){
                this.hideDeleteModal();
                this.flashMessage.error({
                    message: 'Impossível excluir um endereço principal!',
                    time: 5000
                });
                return false;
            }
        
            this.$http({
                url: `endereco/` + endereco.id,
                method: 'DELETE',
            })

            .then(response=>{

                this.getEnderecos();
                this.hideDeleteModal();

                this.flashMessage.success({
                    message: 'Endereço deletado com sucesso!',
                    time: 5000
                });
                
            }, error =>{
                
                this.flashMessage.error({
                    message: 'Error ao deletar Endereço!',
                    time: 5000
                });
            })
        },
        capitalize(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        buscar(field){
            var self = this;
                    
            if(/^[0-9]{5}-[0-9]{3}$/.test(self[field].cep)){
                $.getJSON("https://viacep.com.br/ws/" + self[field].cep + "/json/", function(endereco){
                    if(endereco.erro){
                        self.flashMessage.error({
                            message: 'CEP não localizado!',
                            time: 5000
                        })
                    }
                    self[field].rua = endereco.logradouro
                    self[field].bairro = endereco.bairro
                    self[field].cidade = endereco.localidade
                    self[field].estado = endereco.uf
                });
            }else{
                self[field].rua = ''
                self[field].bairro = ''
                self[field].cidade = 'Goiânia'
                self[field].estado = 'GO'
            }
        },
    }
}
</script>