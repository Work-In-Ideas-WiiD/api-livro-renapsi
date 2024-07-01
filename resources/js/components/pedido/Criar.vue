<template>
    <div class="container-fluid mt--6">
    <!-- Outer Row -->
        <div class="card o-hidden border-0 shadow-lg my-5">

        <div class="card-body">
            <form-wizard title="Cadastrar Pedido" subtitle="Cadastro Novo Pedido"
            color="#000"
            nextButtonText="Proximo"
            finishButtonText="Salvar"
            backButtonText="Voltar"
            error-color="#e74c3c"
            @on-complete="postPedido"
            >
                <tab-content title="Buscar Cliente" icon="fas fa-users" :before-change="validateFirstStep" >
                    <ValidationObserver ref="form">
                    <form>

                    <div class="form-group">
                        <ValidationProvider rules="required|is_not:''" v-slot="{ errors }">
                        <label for="nome">Cliente</label>
                        <!-- <b-form-select class="dorder-select" v-model="cliente" :options="clientes"></b-form-select> -->
                        <model-select :options="clientes"
                                v-model="cliente"
                                placeholder="Escolha O Cliente">
                        </model-select>

                        <div class="invalid-feedback" v-if="errors.cliente_id">
                            <p>{{errors.cliente_id[0]}}</p>
                        </div>

                        <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                            <strong>{{ errors[0] }}</strong>
                        </span>
                        </ValidationProvider>

                        <ValidationProvider rules="required|is_not:''" v-slot="{ errors }">
                            <label for="nome">Endereço</label>
                            <!-- <b-form-select class="dorder-select" v-model="cliente" :options="clientes"></b-form-select> -->
                            <model-select :options="enderecos"
                                    v-model="endereco"
                                    placeholder="Escolha O Endereço">
                            </model-select>

                            <div class="invalid-feedback" v-if="errors.endereco_id">
                                <p>{{errors.endereco_id[0]}}</p>
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                        </ValidationProvider>

                        <ValidationProvider rules="required|is_not:''" v-slot="{ errors }">
                        <label for="nome">Tipo Venda</label>
                        <!-- <b-form-select class="dorder-select" v-model="cliente" :options="clientes"></b-form-select> -->
                        <model-select :options="tipos"
                                v-model="tipo_venda"
                                placeholder="Escolha Tipo">
                        </model-select>

                        <div class="invalid-feedback" v-if="errors.atacado">
                            <p>{{errors.atacado[0]}}</p>
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
                <tab-content title="Carinho Compra" :before-change="validateTwoStep" icon="fas fa-shopping-cart">
                    <ValidationObserver ref="form2">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" v-model="searchLike" @change="getProdutos"  placeholder="Pequisar Produto" aria-label="equisar Produto" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="button-addon2" @click="getProdutos()"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    <b-form-group label="Categorias" v-slot="{ ariaDescribedby }">
                    <b-form-radio-group
                        id="btn-radios-1"
                        v-model="categoria"
                        :options="categorias"
                        :aria-describedby="ariaDescribedby"
                        button-variant="primary"
                        @change="getProdutos"
                        buttons
                    ></b-form-radio-group>
                    </b-form-group>

                    <b-card-group deck>
                        <b-card v-for="produto in produtos.data" v-bind:key="produto.id"
                            :title="produto.nome"
                            :sub-title="produto.sku"
                            :img-src="produto.capa"
                            :img-alt="produto.nome"
                            img-top
                            tag="article"
                            style="max-width: 20rem;"
                            class="mb-2"
                        >
                        <b-card-text>
                            {{ produto.descricao }}
                        </b-card-text>
                        <div class="button_tamanho text-center form-group">
                            <b-form-radio
                                v-for="atributo in quantidadeArrays(produto.atributo)"
                                v-bind:key="atributo.id"
                                v-model="atributo_selected"
                                :value="atributo"
                                name="check-button"
                                @change="changeMethod"
                                :style="'border-radius: 50%;'"
                                button button-variant="primary">
                                {{ atributo.atributo_valor.valor }}
                            </b-form-radio>
                        </div>

                        <div class="qty text-center form-group">
                            <span class="minus bg-dark" @click="minusQty(produto)">-</span>
                            <input type="number" class="count" v-model="produto.status" name="qty" >
                            <span class="plus bg-dark" @click="plusQty(produto)" >+</span>
                        </div>
                        <div class="col text-center">
                            <b-button href="#" variant="primary" @click="addCart(produto)"><i class="fas fa-plus"></i> Adicionar</b-button>
                            <b-button href="#" variant="primary" @click="addCartGrade(produto)"><i class="fas fa-plus"></i> Grade</b-button>
                        </div>

                        </b-card>
                    </b-card-group>

                    <div class="card-footer py-4">
                        <pagination :limit="12" align="right" :data="produtos" @pagination-change-page="getProdutos"></pagination>
                    </div>

                    </ValidationObserver>

                    <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status" >Produto</th>
                            <th scope="col" class="sort" data-sort="status" >Tamanho</th>
                            <th scope="col" class="sort" data-sort="status" >Referência</th>
                            <th scope="col" class="sort"></th>
                            <th scope="col" class="sort"></th>
                            <th scope="col" class="sort" data-sort="completion">Total</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr v-for="item in cart.cartItems" v-bind:key="item.id" style="margin-bottom: 5px;">
                                <td>
                                    <div class="media align-items-center">
                                    <a href="#" class="avatar mr-3">
                                       <img v-bind:alt="item.produto.nome" v-bind:src="item.produto.capa">
                                    </a>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">{{ item.produto.nome }}</span>
                                    </div>
                                    </div>
                                </td>
                                <td>{{ item.atributo ? item.atributo.atributo_valor.valor : '' }}</td>
                                <td>{{ item.produto ? item.produto.sku : '' }}</td>
                                <td>
                                    <div class="qty text-center">
                                        <span class="minus bg-dark" @click="minusCart(item)">-</span>
                                        <input type="number" class="count" v-model="item.quantidade" name="qty" >
                                        <span class="plus bg-dark" @click="plusCart(item)" >+</span>
                                    </div>
                                </td>
                                <td>
                                    <b-button class="text-danger" :disabled="clicked" @click="removeCart(item)">Remover</b-button>
                                </td>
                                <td>{{ item.preco }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <p class="card-text"><small class="text-muted">Quantidade:</small> <strong>{{ cart.quantidade}}</strong> </p>
                        <p class="card-text"><small class="text-muted">Total:</small> <strong>{{ cart.total.toFixed(2)}}</strong> </p>
                    </div>
                    </div>

                    <hr>
                </tab-content>
                <tab-content title="Finalizar Pedido" icon="far fa-share-square" >
                    <h1 class="display-2 text-center">Revisar Pedido</h1>
                    <h1 class="display-4 text-center"><strong>Cliente:</strong>  {{ resumo.text }}</h1>
                    <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status">Capa</th>
                            <th scope="col" class="sort" data-sort="status" >Produto</th>
                            <th scope="col" class="sort" data-sort="status" >Tamanho</th>
                            <th scope="col" class="sort" data-sort="status" >Referência</th>
                            <th scope="col" class="sort"></th>
                            <th scope="col" class="sort"></th>
                            <th scope="col" class="sort" data-sort="completion">Total</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr v-for="item in cart.cartItems" v-bind:key="item.id" style="margin-bottom: 5px;">
                                <td>
                                    <div class="avatar-group">
                                        <a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" v-bind:data-original-title="item.produto.nome">
                                            <img v-bind:alt="item.produto.nome" v-bind:src="item.produto.capa">
                                        </a>
                                    </div>
                                </td>
                                <td>{{ item.produto.nome }}</td>
                                <td>{{ item.atributo ? item.atributo.atributo_valor.valor : '' }}</td>
                                <td>{{ item.produto ? item.produto.sku : '' }}</td>
                                <td>
                                    <div class="qty text-center">
                                        <span class="minus bg-dark" @click="minusCart(item)">-</span>
                                        <input type="number" class="count" v-model="item.quantidade" name="qty" >
                                        <span class="plus bg-dark" @click="plusCart(item)" >+</span>
                                    </div>
                                </td>
                                <td>
                                    <a class="text-danger" @click="removeCart(item)">Remover</a>
                                </td>
                                <td>{{ item.preco }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <p class="card-text"><small class="text-muted">Quantidade:</small> <strong>{{ cart.quantidade}}</strong> </p>
                        <p class="card-text"><small class="text-muted">Total:</small> <strong>{{ cart.total}}</strong> </p>
                    </div>
                    </div>
                    <b-overlay spinner-variant="primary" :show="show" no-wrap>
                        <template v-slot:overlay>
                            <div class="text-center">
                            <b-icon icon="stopwatch" variant="primary" font-scale="3" animation="cylon"></b-icon>
                            <p id="cancel-label">Por favor, espere...</p>
                            </div>
                        </template>
                    </b-overlay>

                </tab-content>

           </form-wizard>
        </div>
        </div>
    </div>
</template>
<style lang="scss">
@import 'https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.2.0/dist/style.css';
@import 'https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css';
@import 'vue-search-select/dist/VueSearchSelect.css';
.qty .count {
    color: #000;
    display: inline-block;
    vertical-align: top;
    font-size: 25px;
    font-weight: 700;
    line-height: 30px;
    padding: 0 2px
    ;min-width: 35px;
    text-align: center;
}
.qty .plus {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    }
.qty .minus {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    background-clip: padding-box;
}
.minus:hover{
    background-color: #717fe0 !important;
}
.plus:hover{
    background-color: #717fe0 !important;
}
/*Prevent text selection*/
span{
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}
input{
    border: 0;
    width: 2%;
}
nput::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input:disabled{
    background-color:white;
}
.button_tamanho{
    border-radius: 50%
}
.qty-minus {
  float: left;
  width: 20px;
  line-height: 100px;
  margin-left: 60px;
  text-align: center;
  cursor: pointer;
}
.qty-plus {
  float: left;
  width: 20px;
  line-height: 100px;
  margin-left: 20px;
  text-align: center;
  cursor: pointer;
}
</style>
<script>
import moment from 'moment';
import { ModelSelect } from 'vue-search-select'
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
        nome:  '',
        sku: '',
        marca: 0,
        marcas: {0: 'Selecione Marca'},
        preco: '',
        quantidade: '',
        atributo_quantidade: '',
        atributo_preco: '',
        descricao: '',
        status: 1,
        opcoes: [
          { text: 'Ativo', value: 1 },
          { text: 'Inativo', value: 2 },
        ],
        tipos: [
          { text: 'Atacado', value: true },
          { text: 'Varejo', value: false },
        ],
        tipo_venda: true,
        capa: null,
        url_capa: null,
        cliente: '',
        clientes: [],
        endereco: '',
        enderecos: [],
        atributo: 0,
        atributos: {0: 'Selecione Atributo'},
        categoria: '',
        categorias: [],
        selectedCat: null,
        valor: 0,
        valores: {0: 'Selecione Valor'},
        selectedvalor: null,
        errors: {},
        password: '',
        password_confirmation: '',
        images: [],
        produto_id: null,
        atributo_produtos: {},
        clicked: false,
        atributo_selected: {},
        users: [{
        name: 'user 1',
        id: '1'
        },{
        name: 'user 2',
        id: '2'
        }],
        produtos: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
        cart: {},
        resumo: {},
        show: false,
      }
    },
    mounted() {
        this.getClientes();
        this.getProdutos();
        this.getCart();
        this.getCategorias();
    },
    methods: {
        getEnderecos() {
            this.$http({
                url: `endereco_lista/${this.cliente}`,
                method: 'GET'
            }).then(response=>{
                this.enderecos = response.data;
            }).catch(error=>{
                this.has_error = true
            })
        },
        quantidadeArrays(atributos) {
            atributos = atributos.filter(obj => {
                return obj.quantidade > 0;
            });
            return atributos;
        },
      changeMethod(){
        console.log(this.atributo_selected)
      },
      minusQty(produto)
      {
        if(produto.status === 1)
            produto.status
        else
            produto.status--
      },
      plusQty(produto)
      {
          produto.status++
      },
      plusCart(item)
      {
        item.quantidade++;
        this.$http({
          url: `cart/` + item.id,
          method: 'PUT',
          params: {
            quantidade: item.quantidade,
          },
            })
            .then(response=>{

                this.getCart();

            }, error => {
                item.quantidade--;
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error: Add Carrinho - ' + error.response.data.mensagem,
                            time: 5000
                        });
                        break;
                }
            })
      },
      minusCart(item)
      {
        item.quantidade--;
        this.$http({
          url: `cart/` + item.id,
          method: 'PUT',
          params: {
            quantidade: item.quantidade,
          },
            })
            .then(response=>{

                this.getCart();

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao retirar quantidade!',
                            time: 5000
                        });
                        break;
                }
            })
      },
      getTipoVenda(){
          var app = this
        this.$http({
        url: `user/atacado`,
        method: 'POST',
        params: {
            atacado: app.tipo_venda,
        },
        })
        .then(response=>{

        }, error => {
           this.flashMessage.error({
                message: 'Error ao deletar produto do carinho!',
                time: 5000
            });
        })
      },
      getProdutos(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `produto?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: 5,
            categoria_id: this.categoria
          },
        })
          .then(response => {
              this.produtos = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getClientes() {
        this.$http({
          url: `lista_cliente`,
          method: 'GET'
        })
            .then(response=>{

            this.clientes = response.data;

            }).catch(error=>{
              this.has_error = true
            })
      },
      getCart() {
        this.$http({
          url: `cart`,
          method: 'GET'
        })
            .then(response=>{

            this.cart = response.data;

            }).catch(error=>{
              this.has_error = true
            })
      },
      removeCart(item) {
        this.clicked = true;
        this.$http({
          url: `cart/` + item.id,
          method: 'DELETE'
        })
            .then(response=>{
            this.clicked = false;
            this.getCart();

            }).catch(error=>{
              this.clicked = false;
              switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao deletar produto do carinho!',
                            time: 5000
                        });
                        break;
                }
            })
      },
      addCart(produto)
      {
        var app = this
        this.$http({
        url: `cart`,
        method: 'POST',
        params: {
        produto_id: produto.id,
        atributo_id: app.atributo_selected.id,
        quantidade: produto.status,
        tipo_venda: this.tipo_venda
        },
        })
        .then(response=>{

            this.getCart();

        }, error => {
            switch(error.response.status){
                case 422:
                    this.errors=error.response.data.errors;
                    break;
                default:
                    this.flashMessage.error({
                        message: 'Error: Add Carrinho - ' + error.response.data.mensagem,
                        time: 5000
                    });
                    break;
            }
        })
      },
      addCartGrade(produto)
      {
        var app = this
        this.$http({
        url: `cart_grade`,
        method: 'POST',
        params: {
        produto_id: produto.id,
        tipo_venda: this.tipo_venda
        },
        })
        .then(response=>{

            this.getCart();

        }, error => {
            switch(error.response.status){
                case 422:
                    this.errors=error.response.data.errors;
                    break;
                default:
                    this.flashMessage.error({
                        message: 'Error ao adicionar grade!',
                        time: 5000
                    });
                    break;
            }
        })
      },
      searchCliente(id)
      {
          const result = this.clientes.find( cliente => cliente.value === id );
          this.resumo = result;
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
          url: `lista_categoria_pedido`,
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
        validateFirstStep() {
            return new Promise((resolve, reject) => {
             this.$refs.form.validate().then(success => {
                if (!success) {
                        reject('error')
                    }
                    this.getTipoVenda();
                    resolve(true)
                });
           })
         },
        validateTwoStep(){
            return new Promise((resolve, reject) => {
             this.$refs.form2.validate().then(success => {
                if (!success) {
                        reject('error')
                    }
                    this.searchCliente(this.cliente);
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
        postPedido(){
        this.show = true
        var app = this
        let formData = new FormData()
        formData.append('cliente_id',  app.cliente)
        formData.append('endereco_id',  app.endereco)
        formData.append('administrador_id',  this.$auth.user().id)
        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
        url: `pedido`,
        method: 'post',
        data: formData,
        config: config,

            })
            .then(response=>{

                this.show = false;

                this.$router.push({name: 'pedido.list'})

                this.flashMessage.success({
                    message: 'Pedido cadastrada com sucesso!',
                    time: 5000
                });

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.show = false;
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.show = false;
                        this.flashMessage.error({
                            message: error.response.data.mensagem ? error.response.data.mensagem : 'Error ao cadastrado Pedido!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        }
    },
    watch: {
        cliente(value) {
            if(value.trim() != ''){
                this.endereco = ''
                this.getEnderecos()
            }
        }
    },
    components: {
      FormWizard,
      TabContent,
      ValidationProvider,
      ValidationObserver,
      ModelSelect
    },
}
</script>
