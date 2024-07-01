<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista categorias</h3>
                    </div>
                    <div class="col-4 text-right">
                        <a v-if="$auth.check(1)" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary">Adicionar</a>
                    </div>
                </div>
            </div>
            
            <!-- Light table -->
            <search v-on:search="searchCategorias" ></search>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="status">Categoria</th>
                    <th scope="col" class="sort" data-sort="completion">Status</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="categoria in categorias.data" v-bind:key="categoria.id" style="margin-bottom: 5px;">
                        <td>
                            <div class="media align-items-center">
                              <a href="#" class="avatar mr-3">
                                <img v-bind:alt="categoria.nome" v-bind:src="'../storage/categorias/' + categoria.capa">
                              </a>
                              <div class="media-body">
                                <span class="name mb-0 text-sm">{{ categoria.nome }}</span>
                              </div>
                            </div>
                        </td>
                        <td v-if="categoria.status == 1"><a class="badge badge-pill badge-success"> Ativo </a> </td>
                         <td v-else ><a class="badge badge-pill badge-danger"> Inativo </a> </td>
                        <td>{{ getHumanDate(categoria.created_at) }}</td>
                        <td>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-primary" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="editCategoria(categoria)"><i class="fas fa-fw fa-edit"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(categoria)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :data="categorias" @pagination-change-page="getCategorias"></pagination>
            </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Cadastrar Categoria</small></h5>
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
                        <div class="form-group">
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
                        <div class="form-group">
                            <p style="text-align: center;" v-if="url_imagem"> <img width="150px" :src="url_imagem" ></p>
                            <!-- <p style="text-align: center;" v-else> <img width="150px" v-bind:src="'../storage/imagem_perfil/' + this.editCampanhaData.imagem_perfil" ></p> -->
                            <b-form-file
                            v-model="imagem"
                            @change="onImagemChange"
                            placeholder="Selecione capa da categoria"
                            drop-placeholder="Solte a imagem aqui..."
                            ></b-form-file>
                            <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, outros formatos não serão aceitos como: JPEG, PNG</small>
                            
                            <div class="invalid-feedback" v-if="errors.capa">
                                <p>{{errors.capa[0]}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Descrição</label>
                            <textarea v-model="descricao" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="postCategoria" class="btn btn-primary my-4">Salvar</button>
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
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Editar Categoria</small></h5>
            </div>   	
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-folder-17"></i></span>
                                </div>
                                <input class="form-control" v-model="editCategoriaData.nome" placeholder="Nome" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                <strong>{{errors.nome[0]}}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                </div>
                                 <b-form-select v-model="editCategoriaData.status" :options="opcoes"></b-form-select>
                            </div>
                                
                            <div class="invalid-feedback" v-if="errors.status">
                                <p>{{errors.status[0]}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <p style="text-align: center;" v-if="this.url_imagem_update"> <img width="150px" :src="url_imagem_update" ></p>
                            <p style="text-align: center;" v-else> <img width="150px" v-bind:src="'../storage/categorias/' + this.editCategoriaData.capa" ></p>     
                            <b-form-file
                            v-model="imagem_update"
                            @change="onImagemChangeUpdate"
                            placeholder="Selecione capa da categoria"
                            drop-placeholder="Solte a imagem aqui..."
                            ></b-form-file>
                            <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, outros formatos não serão aceitos como: JPEG, PNG</small>
                            
                            <div class="invalid-feedback" v-if="errors.capa">
                                <p>{{errors.capa[0]}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Descrição</label>
                            <textarea v-model="editCategoriaData.descricao" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="updateCategoria" class="btn btn-primary my-4">Salvar</button>
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
            <h5 class="modal-title" id="exampleModalLabel">Excluir Categoria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar a categoria: <b>{{ deleteCategoriaData.nome }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteCategoria(deleteCategoriaData)">Excluir</b-button>
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
        categorias: {},
        nome: null,
        password: null,
        password_confirmation: null,
        errors: {},
        editCategoriaData: {},
        deleteCategoriaData: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
        status: 1,
        opcoes: [
          { text: 'Ativo', value: 1 },
          { text: 'Inativo', value: 2 },
        ],
        descricao: '',
        imagem: null,
        imagem_update: null,
        url_imagem: null,
        url_imagem_update: null,
      }
    },
    mounted() {
      this.getCategorias()
    },
    methods: {
        searchCategorias(payload){
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
                this.categorias = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        hideModal() {
          $('#modal-form').modal('hide')
        },
        getCategorias(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `categoria?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.categorias = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      postCategoria() {
        var app = this
        let formData = new FormData()
        formData.append('nome',  app.nome)
        formData.append('capa',  app.imagem)
        formData.append('status',  app.status)
        formData.append('slug',  this.string_to_slug(app.nome))
        formData.append('descricao',  app.descricao)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `categoria`,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.hideModal();
                this.categorias.data.unshift(response.data);

                this.flashMessage.success({
                    message: 'Categoria cadastrada com sucesso!',
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
                            message: 'Error ao cadastrado categoria!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        hideEditModal() {
          $('#modal-form-edit').modal('hide')
        },
        editCategoria(categoria) {
          this.editCategoriaData = categoria;
          this.errors = {};
          // this.showEditModal();
        },
        updateCategoria() {
        var app = this
        let formData = new FormData()
        formData.append('_method', 'PUT');
        formData.append('nome',  app.editCategoriaData.nome)
        formData.append('capa',  app.imagem_update)
        formData.append('status',  app.editCategoriaData.status)
        formData.append('slug',  this.string_to_slug(app.editCategoriaData.nome))
        formData.append('descricao',  app.editCategoriaData.descricao)
    
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `categoria/` + app.editCategoriaData.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.getCategorias();
                this.hideEditModal();

                this.flashMessage.success({
                    message: 'Categoria atualizado com sucesso!',
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
                            message: 'Error ao atualizado categoria!',
                            time: 5000
                        });
                        break;
                }
            })
        },
      showDelete(categoria){
        this.deleteCategoriaData = categoria;
      },
      hideDeleteModal() {
        $('#modal-delete').modal('hide')
      },
      deleteCategoria(categoria) {
    
        this.$http({
            url: `categoria/` + categoria.id,
            method: 'DELETE',
          })

          .then(response=>{		
            this.categorias.data = this.categorias.data.filter(obj => {
                return obj.id != categoria.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Categoria deletada com sucesso!',
                time: 5000
            });
            
          }, error =>{
            
             this.flashMessage.error({
                message: 'Error ao deletar Categoria!',
                time: 5000
            });
          })
      },
        onImagemChange(e) {
            const file = e.target.files[0];
            this.imagem = file;
            this.url_imagem = URL.createObjectURL(file);
        },
        onImagemChangeUpdate(e) {
            const file = e.target.files[0];
            this.imagem_update = file;
            this.url_imagem_update = URL.createObjectURL(file);
        },
        string_to_slug(str) {
            str = str.replace(/^\s+|\s+$/g, ""); // trim
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
            var to = "aaaaaaeeeeiiiioooouuuunc------";

            for (var i = 0, l = from.length; i < l; i++) {
                str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
            }

            str = str
                .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
                .replace(/\s+/g, "-") // collapse whitespace and replace by -
                .replace(/-+/g, "-") // collapse dashes
                .replace(/^-+/, "") // trim - from start of text
                .replace(/-+$/, ""); // trim - from end of text

            return str;
        },
    },
    components: {
      search
    }
}
</script>