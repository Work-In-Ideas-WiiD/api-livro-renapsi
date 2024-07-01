<template>
    <div class="container-fluid mt--6">
    <!-- Outer Row -->
        <div class="card o-hidden border-0 shadow-lg my-5">

        <div class="card-body">
            <form-wizard ref="formEdit" title="Editar Produto" subtitle="Editar cadastro do produto"
            color="#000"
            nextButtonText="Proximo"
            finishButtonText="Salvar"
            backButtonText="Voltar"
            error-color="#e74c3c"
            @on-complete="fimProduto"
            >
                <tab-content title="Dados Principais" icon="fas fa-shopping-bag" :before-change="validateFirstStep" >
                    <ValidationObserver ref="form">
                    <form>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <ValidationProvider rules="required" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bag-17"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-model="produto.nome" placeholder="Nome do Produto" type="text">
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome">
                                <strong>{{errors.nome[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>

                            </ValidationProvider>
                        </div>

                        <div class="form-group col-md-3">
                            <ValidationProvider rules="required" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-model="produto.sku" placeholder="Codígo Referência" type="text">
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.sku">
                                <strong>{{errors.sku[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>

                            </ValidationProvider>
                        </div>

                        <div class="form-group col-md-3">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-tag"></i></span>
                                </div>
                                <b-form-select :disabled="$auth.check(3)" class="dorder-select" v-model="produto.marca_id" :options="marcas" ></b-form-select>
                            </div>

                            <div class="invalid-feedback" v-if="errors.marca_id">
                                <p>{{errors.marca_id[0]}}</p>
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>

                        <div class="form-group col-md-3">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                </div>
                                 <b-form-select :disabled="$auth.check([3,6])" v-model="produto.status" :options="opcoes"></b-form-select>
                            </div>

                            <div class="invalid-feedback" v-if="errors.status">
                                <p>{{errors.status[0]}}</p>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">



                        <div class="form-group col-md-4">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="produto.preco" placeholder="Preço" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.preco">
                                <strong>{{errors.preco[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-4">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="produto.preco_atacado" placeholder="Preço Atacado" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.preco_atacado">
                                <strong>{{errors.preco_atacado[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-4">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="produto.custo" placeholder="Preço Custo" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.custo">
                                <strong>{{errors.custo[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-6">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">

                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-model="produto.referencia" placeholder="Codigo SKU" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.referencia">
                                <strong>{{errors.referencia[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-6">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">

                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bag-17"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="'######'" v-model="produto.quantidade_minima" placeholder="Quantidade Minima Atacado" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.quantidade_minima">
                                <strong>{{errors.quantidade_minima[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>


                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-3">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-weight"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="['#.###']" v-model="produto.peso" placeholder="Peso kg" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.peso">
                                <strong>{{errors.peso[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-3">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="'####'" v-model="produto.comprimento" placeholder="Comprimento cm" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.comprimento">
                                <strong>{{errors.comprimento[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-3">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">

                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-ruler-vertical"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="'#####'" v-model="produto.altura" placeholder="Altura cm" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.altura">
                                <strong>{{errors.altura[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-3">
                            <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">

                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-ruler-horizontal"></i></span>
                                </div>
                                <input :disabled="$auth.check(3)" class="form-control" v-mask="'#####'" v-model="produto.largura" placeholder="Largura cm" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.largura">
                                <strong>{{errors.largura[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>


                    </div>

                    <div class="form-group">
                        <ValidationProvider rules="required|is_not:0" v-slot="{ errors }">
                        <label for="nome" class="form-control-label">Categorias</label>
                        <multiselect :disabled="$auth.check(3)" :multiple="true"
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
                        v-model="categoria" :options="categorias" >
                        <template slot="maxElements">Máximo de 3 opções selecionadas. Primeiro remova uma opção selecionada para selecionar outra.</template>
                        <template slot="noOptions">Lista esta vazia.</template>

                        </multiselect>

                        <div class="invalid-feedback" v-if="errors.categoria">
                            <p>{{errors.categoria[0]}}</p>
                        </div>

                        <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                            <strong>{{ errors[0] }}</strong>
                        </span>

                        </ValidationProvider>
                    </div>

                    <div class="form-group">
                        <ValidationProvider rules="required|image" ref="provider" v-slot="{ errors }">
                            <label for="example-text-input" class="form-control-label">Capa</label>
                            <b-form-file
                            :disabled="$auth.check(3)"
                            v-model="capa"
                            @change="onImagemChange"
                            placeholder="Selecione capa da produto"
                            drop-placeholder="Solte a imagem aqui..."
                            ></b-form-file>
                            <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, outros formatos não serão aceitos como: JPEG, PNG</small>

                            <p style="text-align: center;" v-if="url_capa"> <img width="150px" :src="url_capa" ></p>
                            <p style="text-align: center;" v-else> <img width="150px" v-bind:src="produto.capa" ></p>

                            <div class="invalid-feedback" v-if="errors.capa">
                                <p>{{errors.capa[0]}}</p>
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                        </ValidationProvider>
                    </div>

                    <div class="form-group">
                        <ValidationProvider rules="required" ref="provider" v-slot="{ errors }">
                        <label for="example-text-input" class="form-control-label">Descrição</label>
                        <textarea :disabled="$auth.check(3)" v-model="produto.descricao" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>

                        <div class="invalid-feedback" v-if="errors.descricao">
                            <p>{{errors.descricao[0]}}</p>
                        </div>

                        <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                            <strong>{{ errors[0] }}</strong>
                        </span>
                        </ValidationProvider>
                    </div>
                    </form>
                    </ValidationObserver>
                    <hr>
                </tab-content>
                <tab-content title="Dados Combinações" :before-change="validateTwoStep" icon="fas fa-tags">
                    <ValidationObserver ref="form2">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nome">Atributo</label>
                            <b-form-select class="dorder-select" v-model="atributo" @change="getValorAtributos()" :options="atributos"></b-form-select>

                            <div class="invalid-feedback" v-if="errors.atributo_id">
                                <p>{{errors.atributo_id[0]}}</p>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nome">Tamanho</label>
                            <b-form-select class="dorder-select" v-model="valor" :options="valores"></b-form-select>

                            <div class="invalid-feedback" v-if="errors.atributo_valor_id">
                                <p>{{errors.atributo_valor_id[0]}}</p>
                            </div>

                        </div>
                        <div class="form-group col-md-4">
                            <label for="nome">Quantidade</label>
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bag-17"></i></span>
                                </div>
                                <input class="form-control" v-mask="'######'" v-model="atributo_quantidade" placeholder="Quantidade" type="text">
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.quantidade">
                                <strong>{{ errors.quantidade[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nome">Preço</label>
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                </div>
                                <input class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="atributo_preco" placeholder="Preço" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.preco">
                                <strong>{{errors.preco[0]}}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nome">Preço Atacado</label>
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                </div>
                                <input class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="atributo_preco_atacado" placeholder="Preço Atacado" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.preco_atacado">
                                <strong>{{errors.preco_atacado[0]}}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nome">Preço Custo</label>
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-money-coins"></i></span>
                                </div>
                                <input class="form-control" v-mask="['##.##', '###.##', '####.##']" v-model="atributo_custo" placeholder="Preço Custo" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.atributo_custo">
                                <strong>{{errors.atributo_custo[0]}}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-1" style="margin-top: 34px;">
                            <button class="btn btn-icon btn-primary" type="button" @click="postProdutoAtributo()">
                                <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                            </button>
                        </div>
                    </div>
                    </ValidationObserver>

                    <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status">Atributo</th>
                            <th scope="col" class="sort" data-sort="status" >Tamanho</th>
                            <th scope="col" class="sort" data-sort="completion">Preço</th>
                            <th scope="col" class="sort" data-sort="completion">Preço Atacado</th>
                            <th scope="col" class="sort" data-sort="completion">Preço Custo</th>
                            <th scope="col" class="sort" data-sort="completion">Quantidade</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr v-for="atributo_produto in atributo_produtos.data" v-bind:key="atributo_produto.id" style="margin-bottom: 5px;">
                                <td>{{ atributo_produto.atributo_valor.atributo.nome }}</td>
                                <td>{{ atributo_produto.atributo_valor.valor }}</td>
                                <td>{{ atributo_produto.preco }}</td>
                                <td>{{ atributo_produto.preco_atacado }}</td>
                                <td>{{ atributo_produto.custo }}</td>
                                <td>{{ atributo_produto.quantidade }}</td>
                                <td>
                                    <button class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="deleteProdutoAtributo(atributo_produto)"><i class="fas fa-fw fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                    <hr>
                </tab-content>
                <tab-content title="Dados Images" icon="fas fa-images" >
                    <div v-if="!$auth.check(3)" id="my-strictly-unique-vue-upload-multiple-image" style="display: flex; justify-content: center;">
                        <vue-upload-multiple-image
                            @upload-success="uploadImageSuccess"
                            @before-remove="beforeRemove"
                            @edit-image="editImage"
                            dragText="Arraste imagens (muitas)"
                            markIsPrimaryText="Definir como padrão"
                            primaryText="Padrão"
                            browseText="(ou) Selecione"
                            popupText="Esta imagem será exibida como padrão"
                            :data-images="images"
                            :multiple="true"
                            :limit-exceeded="6"
                            ></vue-upload-multiple-image>
                    </div>
                    <div v-else class="mt-4 mb-4 text-danger text-center">
                        Sem permissão para editar imagens.
                    </div>
                </tab-content>

           </form-wizard>
        </div>
        </div>
    </div>
</template>
<style lang="scss">
@import 'https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.2.0/dist/style.css';
@import 'https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css';
</style>
<script>
import moment from 'moment';
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker';
import VueUploadMultipleImage from 'vue-upload-multiple-image'
import {en, ptBR} from 'vuejs-datepicker/dist/locale'
import {FormWizard, TabContent} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import { ValidationObserver, ValidationProvider, extend, validate } from 'vee-validate';
import { required, email, min, is_not, max, image, confirmed } from 'vee-validate/dist/rules';
extend('required', {
  ...required,
  message: 'Este campo é obrigatório'
});
extend('email', {
  ...email,
  message: 'O campo deve ser um email válido'
});
extend('is_not', {
  ...is_not,
  message: 'Não é válido'
});
extend('max', {
  ...max,
  message: 'O campo pode ter mais de 16 caracteres'
});
extend('image', {
  ...image,
  message: 'O campo deve ser uma imagem'
});
extend('confirmed', {
  ...confirmed,
  message: 'A senha estão deferente'
});
export default {
    data() {
      return {
        has_error: false,
        ptBR: ptBR,
        produto: {},
        nome:  '',
        sku: '',
        marca: 0,
        marcas: {0: 'Selecione Marca'},
        preco: '',
        preco_atacado: '',
        custo: '',
        referencia: '',
        quantidade_minima: '',
        atributo_quantidade: '',
        atributo_preco: '',
        atributo_preco_atacado: '',
        atributo_custo: '',
        descricao: '',
        status: 1,
        opcoes: [
          { text: 'Ativo', value: 1 },
          { text: 'Inativo', value: 2 },
        ],
        capa: null,
        url_capa: null,
        atributo: 0,
        atributos: {0: 'Selecione Atributo'},
        categoria: null,
        categorias: [],
        selectedCat: null,
        valor: 0,
        valores: {0: 'Selecione Tamanho'},
        selectedvalor: null,
        errors: {},
        password: '',
        password_confirmation: '',
        images: [],
        produto_id: null,
        atributo_produtos: {},
        clicked: false,
      }
    },
    mounted() {
        this.getProduto();
        this.getMarcas();
        this.getCategoria();
        this.getCategorias();
        this.getAtributos();
        this.getAtributosProduto();
        this.getImage();
    },
    methods: {
        getProduto() {
            this.$http({
            url: `produto/` + this.$route.params.id,
            method: 'GET'
            })
            .then(response=>{

                this.produto = response.data;
                if(this.$auth.check(6) && this.produto.status == 1){
                    this.opcoes = [
                        { text: 'Ativo', value: 1 }
                    ]
                }else if(this.$auth.check(6) && this.produto.status == 2){
                    this.opcoes = [
                        { text: 'Inativo', value: 2 }
                    ]
                }

            }).catch(error=>{
                this.has_error = true
            })
        },
      getAtributosProduto(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `produto_atributo?page=` + page,
          method: 'GET',
          params: {
            produto_id: this.produto.id
            // like: this.searchLike,
            // order: this.searchOder,
            // mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.atributo_produtos = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getMarcas() {
        this.$http({
          url: `lista_marca`,
          method: 'GET'
        })
            .then(response=>{


            response.data[0] = 'Selecione Marca';

            this.marcas = response.data;

            }).catch(error=>{
              this.has_error = true
            })
      },
      getCategorias() {
        this.$http({
          url: `lista_categoria`,
          method: 'GET'
        })
            .then(response=>{

            this.categorias = response.data;


            }).catch(error=>{
              this.has_error = true
            })
      },
      getAtributos() {
        this.$http({
          url: `lista_atributo`,
          method: 'GET'
        })
            .then(response=>{

            response.data[0] = 'Selecione Atributo';
            this.atributos = response.data;


            }).catch(error=>{
              this.has_error = true
            })
      },
      postProdutoAtributo() {
        var app = this
        this.$http({
          url: `produto_atributo`,
          method: 'POST',
          params: {
            produto_id: app.produto.id,
            atributo_valor_id: app.valor,
            quantidade: app.atributo_quantidade,
            preco: app.atributo_preco,
            preco_atacado: app.atributo_preco_atacado,
            custo: app.atributo_custo,
          },

            })
                .then(response=>{

                    this.getAtributosProduto();

                    this.flashMessage.success({
                        message: 'Combinação cadastrada com sucesso!',
                        time: 5000
                    });

                    this.valor = 0;
                    this.atributo_quantidade = '';
                    this.atributo_preco = '';
                    this.atributo_custo = '';
                    this.errors = {};

                }, error => {
                    switch(error.response.status){
                        case 422:
                            this.errors=error.response.data.errors;
                            break;
                        default:
                            this.flashMessage.error({
                                message: 'Error ao cadastrado Combinação!',
                                time: 5000
                            });
                            break;
                    }
                })
      },
      deleteProdutoAtributo(atributo_produto) {

        this.$http({
            url: `produto_atributo/` + atributo_produto.id,
            method: 'DELETE',
          })

          .then(response=>{

            this.getAtributosProduto();

            this.flashMessage.success({
                message: 'Tamanho Grade deletado com sucesso!',
                time: 5000
            });

          }, error =>{

             this.flashMessage.error({
                message: 'Error ao deletar Grade Tamanho!',
                time: 5000
            });
          })
      },
      getCategoria() {
        this.$http({
        url: `lista_categoria/` + this.$route.params.id,
        method: 'GET'
        })
        .then(response=>{

        this.categoria = response.data;

        }).catch(error=>{
            this.has_error = true
        })
      },
      updateProduto() {
        this.clicked = true;
        var selected = [];
        if(this.categoria){ // Check all
            this.categoria.forEach(function (categoria) {
                selected.push(categoria.id);
            });

            this.selectedCat = selected;
        }

        var app = this
        let formData = new FormData()
        formData.append('_method', 'PUT');
        formData.append('nome',  app.produto.nome)
        formData.append('sku',  app.produto.sku)
        formData.append('slug',  this.string_to_slug(app.produto.nome))
        formData.append('marca_id',  app.produto.marca_id)
        formData.append('preco',  app.produto.preco)
        formData.append('referencia',  app.produto.referencia)
        formData.append('preco_atacado',  app.produto.preco_atacado)
        formData.append('custo',  app.produto.custo)
        formData.append('quantidade_minima',  app.produto.quantidade_minima)
        formData.append('descricao',  app.produto.descricao)
        formData.append('capa',  app.capa)
        formData.append('status',  app.produto.status)
        formData.append('categoria',  app.selectedCat)
        formData.append('peso',  app.produto.peso)
        formData.append('comprimento',  app.produto.comprimento)
        formData.append('altura',  app.produto.altura)
        formData.append('largura',  app.produto.largura)

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `produto/` + app.produto.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{

                this.getAtributosProduto();

                this.flashMessage.success({
                    message: 'Parabens!!! Esta quase acabando.',
                    time: 5000
                });

                this.produto = response.data;

            }, error => {
                this.clicked = false;
                 switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        this.flashMessage.error({
                            message: 'Por Favor! Verifique os dados inseridos!',
                            time: 5000
                        });
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao cadastrado produto!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        showPreviewModal() {
            this.$refs['preview-my-modal'].show()
        },
        hidePreviewModal() {
            this.$refs['preview-my-modal'].hide()
        },
        onImagemChange(e) {
            const file = e.target.files[0];
            this.capa = file;
            this.url_capa = URL.createObjectURL(file);
        },
        getHumanDate : function (date) {
            return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
        },
        buscar: function(){
            var self = this;

            if(/^[0-9]{5}-[0-9]{3}$/.test(this.cep)){
                $.getJSON("https://viacep.com.br/ws/" + this.cep + "/json/", function(endereco){
                    if(endereco.erro){
                        self.flashMessage.error({
                            message: 'CEP não localizado!',
                            time: 5000
                        });
                    }
                    self.rua = endereco.logradouro;
                    self.bairro = endereco.bairro;
                    self.cidade = endereco.localidade;
                    self.uf = endereco.uf;
                });
            }
        },
        validateFirstStep() {
            return new Promise((resolve, reject) => {

                if(this.$auth.check(3)){
                    this.getAtributosProduto();
                }else{
                    this.updateProduto();
                }
                resolve(true)

           })
         },
        validateTwoStep(){
            return new Promise((resolve, reject) => {
             this.$refs.form2.validate().then(success => {
                if (!success) {
                        reject('error')
                    }
                    resolve(true)
                });
           })
        },
        validateThreeStep(){
            return new Promise((resolve, reject) => {
             this.$refs.form3.validate().then(success => {
                if (!success) {
                        reject('error')
                    }
                    resolve(true)
                });
           })
        },
        uploadImageSuccess(formData, index, fileList) {
            var app = this
            formData.append('produto_id',  app.produto.id)
            formData.append('index', index)

            let config = {
                headers: {
                'content-type': 'multipart/form-data'
                }
            }
            this.$http({
                url: `image`,
                method: 'post',
                data: formData,
                config: config,

                })
                .then(response=>{

                    this.flashMessage.success({
                        message: 'Foto adicionda sucesso!!!',
                        time: 5000
                    });

                }, error => {
                    this.flashMessage.error({
                        message: 'Error ao adicionar foto!!!',
                        time: 5000
                    });
                })
        },
        getImage() {
            this.$http({
            url: `image/` + this.$route.params.id,
            method: 'GET'
            })
            .then(response=>{

            this.images = response.data;

            }).catch(error=>{
              this.has_error = true
            })
        },
        beforeRemove (index, done, fileList) {
            this.$http({
            url: `image/` + this.produto.id + '/' + index,
            method: 'DELETE',
            })
            .then(response=>{
                done()
            }).catch(error=>{
                this.flashMessage.error({
                    message: 'Error ao deletar image!',
                    time: 5000
                });
            })
            // var r = confirm("remove image")
            // if (r == true) {
            //     done()
            // } else {
            // }
        },
        editImage (formData, index, fileList) {
            var app = this
            formData.append('_method', 'PUT');
            formData.append('produto_id',  app.produto.id)
            formData.append('index', index)

            let config = {
                headers: {
                'content-type': 'multipart/form-data'
                }
            }
            this.$http({
            url: `image/` + this.produto.id + '/' + index,
            method: 'post',
            data: formData,
            config: config,

            })
            .then(response=>{

                this.flashMessage.success({
                    message: 'Image editada sucesso!!!',
                    time: 5000
                });

            }, error => {
                this.flashMessage.error({
                    message: 'Error ao editar image!',
                    time: 5000
                });
            })
        },
        getValorAtributos(){
            this.$http({
            url: `lista_atributo_valores/` + this.atributo,
            method: 'GET'
            })
                .then(response=>{

                response.data[0] = 'Selecione Tamanho';
                this.valores = response.data;

                }).catch(error=>{
                this.has_error = true
                })
        },
        fimProduto(){
            this.$router.push({name: 'produto.list'})
        },
        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
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
      FormWizard,
      TabContent,
      ValidationProvider,
      ValidationObserver,
      Datepicker,
      VueUploadMultipleImage,
      Multiselect
    },
}
</script>
