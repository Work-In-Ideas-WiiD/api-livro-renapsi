<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                     <div class="col-8">
                        <!-- <h3 class="mb-0">Lista Livros</h3>  -->
                        <search v-on:search="searchLivros" ></search>
                    </div>
                    <div class="col-4 text-right">
                        <a v-if="$auth.check(1)" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary botaoCadastro"><i class="ni ni-fat-add"></i>Adicionar</a>
                    </div>
                </div>
            </div>

            <!-- Light table -->
            
            <div class="table-responsive">
              <table class="table align-items-center table-flush tabelaDash">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="status">Título</th>
                    <th scope="col" class="sort" data-sort="completion">Módulos</th>
                    <th scope="col" class="sort" data-sort="completion">Tags</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                    <th scope="col" class="sort" data-sort="completion">Ações</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="livro in livros.data" v-bind:key="livro.id" style="margin-bottom: 5px;">
                        <td>
                            <div class="media align-items-center">
                              <a  v-bind:href="livro.arquivo" class="avatar mr-3">
                                <img v-bind:alt="livro.titulo" src="assets/img/theme/pdf_icon.png">
                              </a>
                              <div class="media-body">
                                <span class="name mb-0 text-sm">{{ livro.titulo }}</span>
                              </div>
                            </div>
                        </td>
                        <td>{{ livro.nome_modulos }}</td>
                        <td>{{ livro.nome_tags }}</td>
                        <td>{{ livro.criado_em }}</td>
                        <td>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-primary botaoEditar" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="editLivro(livro)"><i class="fas fa-fw fa-edit"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-danger botaoDeletar" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(livro)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :data="livros" @pagination-change-page="getLivros"></pagination>
            </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Cadastrar Livro</small></h5>
            </div>
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-books"></i></span>
                                </div>
                                <input class="form-control" v-model="titulo" placeholder="Título" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.titulo">
                                <strong>{{errors.titulo[0]}}</strong>
                            </span>
                        </div>
                        <!-- <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                </div>
                                 <b-form-select v-model="status" :options="opcoes"></b-form-select>
                            </div>

                            <div class="invalid-feedback" v-if="errors.status">
                                <p>{{errors.status[0]}}</p>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <!-- <p style="text-align: center;" v-if="url_imagem"> <img width="150px" :src="url_imagem" ></p> -->
                            <!-- <p style="text-align: center;" v-else> <img width="150px" v-bind:src="'../storage/imagem_perfil/' + this.editCampanhaData.imagem_perfil" ></p> -->
                            <b-form-file
                            v-model="imagem"
                            @change="onImagemChange"
                            placeholder="Selecione arquivo do livro"
                            drop-placeholder="Solte a imagem aqui..."
                            ></b-form-file>
                            <small class="text-muted" >tamanho mínimo da imagem - 10 MB, será aceito somente arquivo PDF</small>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.arquivo">
                                <strong>{{errors.arquivo[0]}}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="nome" class="form-control-label">Módulos</label>
                            <multiselect :multiple="true"
                            placeholder="Selecione as Categorias"
                            label="nome"
                            track-by="id"
                            selectLabel="Pressione enter para selecionar"
                            selectGroupLabel="Pressione enter para selecionar o grupo"
                            selectedLabel="Selecionado"
                            deselectLabel="Pressione Enter para remover"
                            deselectGroupLabel="Pressione Enter para desmarcar o grupo"
                            noResult="Lista está vazia"
                            :maxElements="'Máximo de 3 opções selecionadas. Primeiro remova uma opção selecionada para selecionar outra.'"
                            v-model="modulo" :options="modulos" >
                            <template slot="maxElements">Máximo de 3 opções selecionadas. Primeiro remova uma opção selecionada para selecionar outra.</template>
                            <template slot="noOptions">Lista esta vazia.</template>

                            </multiselect>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.modulos">
                                <strong>{{errors.modulos[0]}}</strong>
                            </span>

                        </div>

                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Tags</label>
                            <voerro-tags-input element-id="tags" @tag-removed="onTagRemoved" @tag-added="onTagAdded" v-model="selectedTags"></voerro-tags-input>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.tags">
                                <strong>{{errors.tags[0]}}</strong>
                            </span>
                        </div>
                        <!-- <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Descrição</label>
                            <textarea v-model="descricao" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div> -->
                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4 botaoFecharEditar">Fechar</button>
                            <button type="button" @click="postLivro" class="btn btn-primary my-4">Salvar</button>
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
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Editar Livro</small></h5>
            </div>
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-books"></i></span>
                                </div>
                                <input class="form-control" v-model="editLivroData.titulo" placeholder="Titulo" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.titulo">
                                <strong>{{errors.titulo[0]}}</strong>
                            </span>
                        </div>
                        <!-- <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                </div>
                                 <b-form-select v-model="editCategoriaData.status" :options="opcoes"></b-form-select>
                            </div>

                            <div class="invalid-feedback" v-if="errors.status">
                                <p>{{errors.status[0]}}</p>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <!-- <p style="text-align: center;" v-if="this.url_imagem_update"> <img width="150px" :src="url_imagem_update" ></p>
                            <p style="text-align: center;" v-else> <img width="150px" v-bind:src="this.editModuloData.icone" ></p> -->
                            <b-form-file
                            v-model="imagem_update"
                            @change="onImagemChangeUpdate"
                            placeholder="Selecione capa da categoria"
                            drop-placeholder="Solte a imagem aqui..."
                            ></b-form-file>
                            <small class="text-muted" >tamanho mínimo da imagem - 10 MB, será aceito somente arquivo PDF</small>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.arquivo">
                                <strong>{{errors.arquivo[0]}}</strong>
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="nome" class="form-control-label">Módulos</label>
                            <multiselect :multiple="true"
                            placeholder="Selecione as Categorias"
                            label="nome"
                            track-by="id"
                            selectLabel="Pressione enter para selecionar"
                            selectGroupLabel="Pressione enter para selecionar o grupo"
                            selectedLabel="Selecionado"
                            deselectLabel="Pressione Enter para remover"
                            deselectGroupLabel="Pressione Enter para desmarcar o grupo"
                            noResult="Lista está vazia"
                            :maxElements="'Máximo de 3 opções selecionadas. Primeiro remova uma opção selecionada para selecionar outra.'"
                            v-model="modulo_update" :options="modulos" >
                            <template slot="maxElements">Máximo de 3 opções selecionadas. Primeiro remova uma opção selecionada para selecionar outra.</template>
                            <template slot="noOptions">Lista esta vazia.</template>

                            </multiselect>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.modulos">
                                <strong>{{errors.modulos[0]}}</strong>
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Tags</label>
                            <voerro-tags-input element-id="tags" @tag-removed="onTagRemoved" @tag-added="onTagAdded" v-model="selectedTags_update"></voerro-tags-input>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.tags">
                                <strong>{{errors.tags[0]}}</strong>
                            </span>
                        </div>

                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4 botaoFecharEditar">Fechar</button>
                            <button type="button" @click="updateLivro" class="btn btn-primary my-4">Salvar</button>
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
            <h5 class="modal-title" id="exampleModalLabel">Excluir Módulo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar a livro: <b>{{ deleteLivroData.titulo }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteLivro(deleteLivroData)">Excluir</b-button>
        </div>
        </div>
    </div>
    </div>

</div>


</template>
<script>
import moment from 'moment/moment';
import search from '../search/Search.vue';
import Multiselect from 'vue-multiselect'
import VoerroTagsInput from '@voerro/vue-tagsinput';
export default {
     data() {
      return {
        has_error: false,
        livros: {},
        titulo: '',
        password: null,
        password_confirmation: null,
        errors: {},
        post_tags: [],
        selectedTags: [],
        selectedTags_update: [],
        tags: [],
        modulo: null,
        modulo_update: null,
        modulos: [],
        editLivroData: {},
        deleteLivroData: {},
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
        selectedSub: '',
      }
    },
    mounted() {
      this.getLivros()
      this.getModulos()
    },
    methods: {
        searchLivros(payload){
            this.$http({
                url: 'conteudos/livro',
                method: 'GET',
                params: {
                'filter[search]': payload.dados.nome,
                sort: payload.dados.ordem,
                per_page: payload.dados.mostrar
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.livros = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        hideModal() {
          $('#modal-form').modal('hide')
        },
        getLivros(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `/conteudos/livro?page=` + page,
          method: 'GET',
          params: {
            'filter[search]': this.searchLike,
            sort: this.searchOder,
            per_page: this.searchMostrar
          },
        })
          .then(response => {
              this.livros = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getModulos() {
        this.$http({
          url: `lista_modulo`,
          method: 'GET'
        })
            .then(response=>{

            this.modulos = response.data;


            }).catch(error=>{
              this.has_error = true
            })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      onTagAdded(slug) {
        this.post_tags.push(slug.value);
        // console.log(`Tag added: ${slug}`);
      },
      onTagRemoved(slug) {
        this.post_tags.splice(slug.value);
        //console.log(`Tag removed: ${slug}`);
      },
      postLivro() {
        var app = this
        let formData = new FormData()

        if(this.modulo){ // Check all
            this.modulo.forEach(function (sub) {
                formData.append('modulos[]',  sub.id)
            });
        }

        if(this.selectedTags){ // Check all
            this.selectedTags.forEach(function (sub) {
                formData.append('tags[]',  sub.value)
            });
        }

        formData.append('titulo',  app.titulo)
        formData.append('arquivo',  app.imagem)

        let config = {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `conteudos/livro`,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{

                this.hideModal();
                this.livros.data.unshift(response.data);

                this.flashMessage.success({
                    message: 'Livro cadastrado com sucesso!',
                    time: 5000
                });

                this.titulo = '',
                this.selectedTags = [],
                this.modulo = null,
                this.imagem = null,
                this.url_imagem = null

                this.getLivros();

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        console.log(this.errors);
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao cadastrado Livro!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        hideEditModal() {
          $('#modal-form-edit').modal('hide')
        },
        editLivro(livro) {

          this.getTag(livro);
          this.editLivroData = livro;
          this.modulo_update = livro.modulos;
          this.errors = {};

          // this.showEditModal();
        },
        getTag(livro) {
            this.$http({
            url: `tags/` + livro.id,
            method: 'GET'
            })
            .then(response=>{

            this.selectedTags_update = response.data;

            }).catch(error=>{
              this.has_error = true
            })
        },
        updateLivro() {

        var app = this
        let formData = new FormData()

        if(this.modulo_update){ // Check all
            this.modulo_update.forEach(function (sub) {
                formData.append('modulos[]',  sub.id)
            });
        }

        if(this.selectedTags_update){ // Check all
            this.selectedTags_update.forEach(function (sub) {
                formData.append('tags[]',  sub.value)
            });
        }

        formData.append('_method', 'PUT');
        formData.append('titulo',  app.editLivroData.titulo)
        formData.append('arquivo',  app.imagem_update) 

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `conteudos/livro/` + app.editLivroData.id,
          method: 'put', 
          data: formData, 
          config: config,

            })
            .then(response=>{
                console.log(response.data);
                this.getLivros();
                this.hideEditModal();

                this.flashMessage.success({
                    message: 'Livro atualizado com sucesso!',
                    time: 5000
                });

                this.titulo = '',
                this.imagem = null,
                this.url_imagem = null

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao atualizado livro!',
                            time: 5000
                        });
                        break;
                }
            })
        },
      showDelete(modulo){
        this.deleteModuloData = modulo;
      },
      hideDeleteModal() {
        $('#modal-delete').modal('hide')
      },
      deleteLivro(livro) {

        this.$http({
            url: `conteudos/modulo/` + livro.id,
            method: 'DELETE',
          })

          .then(response=>{
            this.livro.data = this.livro.data.filter(obj => {
                return obj.id != livro.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Livro deletado com sucesso!',
                time: 5000
            });

          }, error =>{

             this.flashMessage.error({
                message: 'Error ao deletar Livro!',
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
      search,
      VoerroTagsInput,
      Multiselect
    }
}
</script>
