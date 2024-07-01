<template>
    <div class="container-fluid mt--6">
    <!-- Outer Row -->
        <div class="card o-hidden border-0 shadow-lg my-5">

        <div class="card-body">
            <form-wizard title="Cadastrar Usuário" subtitle="Cadastro do seu usuário" 
            color="#000" 
            nextButtonText="Proximo"
            finishButtonText="Salvar"
            backButtonText="Voltar"
            error-color="#e74c3c"
            @on-complete="postUsuario"
            >
                <tab-content title="Dados Principais" icon="fas fa-building" :before-change="validateFirstStep" >
                    <ValidationObserver ref="form">
                    <form>
                    <div class="form-row">  
                        <div class="form-group col-md-6">
                            <ValidationProvider rules="required" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                </div>
                                <input class="form-control" v-model="nome" placeholder="Nome do Usuário" type="text">
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.nome">
                                <strong>{{error.nome[0]}}</strong>
                            </span>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>

                            </ValidationProvider>
                        </div>
                        <div class="form-group col-md-6">
                            <ValidationProvider rules="required" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-badge"></i></span>
                                </div>
                                <input @keyup="onKeyUpDocumento()" class="form-control" v-mask="['###.###.###-##', '##.###.###/####-##']" v-model="documento" placeholder="CPF ou CNPJ" type="text">
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.documento">
                                <strong>{{error.documento[0]}}</strong>
                            </span>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>

                            </ValidationProvider>
                        </div>
                    </div>
                    <div class="form-row">  
                        <div v-bind:class="'form-group ' +  col_md">
                            <ValidationProvider rules="required|email" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control" v-model="email" placeholder="Email" type="email">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.email">
                                <strong>{{error.email[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div v-bind:class="'form-group ' +  col_md">
                            <ValidationProvider rules="required" v-slot="{ errors }">
                            
                            <datepicker :bootstrap-styling="true" v-model="data_nascimento" :placeholder="'Data Nascimento'" :language="ptBR" ></datepicker>
 
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.data_nascimento">
                                <strong>{{error.data_nascimento[0]}}</strong>
                            </span>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div v-bind:class="'form-group ' +  col_md">
                            <ValidationProvider rules="required|max:16" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone-square-alt"></i></span>
                                </div>
                                <input class="form-control" v-mask="['(##) # ####-####', '(##) ####-####']" v-model="celular" placeholder="Celular" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.celular">
                                <strong>{{error.celular[0]}}</strong>
                            </span>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                        <div v-bind:class="'form-group ' +  col_md" v-show="is_cnpj">
                            <ValidationProvider v-bind:rules="is_cnpj ? 'max:20' : 'max:20'" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input class="form-control" v-mask="'####################'" v-model="inscricao_estadual" placeholder="Inscrição Estadual" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.inscricao_estadual">
                                <strong>{{error.inscricao_estadual[0]}}</strong>
                            </span>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>
                    </div>
                    </form>
                    </ValidationObserver>
                    <hr>
                </tab-content>
                <tab-content title="Dados Endereço" :before-change="validateTwoStep" icon="far fa-address-book">
                    <ValidationObserver ref="form2">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <ValidationProvider rules="required|max:9" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                                </div>
                                <input class="form-control" type="text" v-model="cep" v-mask="'#####-###'" v-on:keyup="buscar" placeholder="CEP">
                            </div>
                           
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.endereco">
                                <strong v-if="error.endereco.cep">{{error.endereco.cep[0]}}</strong>
                            </span>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>
                            </ValidationProvider>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="input-group input-group-merge input-group-alternative">
                                <input class="form-control" type="text" v-model="rua" placeholder="Rua Silva bueno">
                            </div>
                           
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.endereco">
                                <strong v-if="error.endereco.rua">{{error.endereco.rua[0]}}</strong>
                            </span>
                        
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="input-group input-group-merge input-group-alternative">
                                <input class="form-control" type="text" v-model="bairro" placeholder="São joão">
                            </div>
                           
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.endereco">
                                <strong v-if="error.endereco.bairro" >{{error.endereco.bairro[0]}}</strong>
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="input-group input-group-merge input-group-alternative">
                                <input class="form-control" type="text" v-model="cidade" placeholder="Goiânia"  readonly>
                            </div>
                           
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.endereco">
                                <strong v-if="error.endereco.cidade">{{error.endereco.cidade[0]}}</strong>
                            </span>
                            
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <div class="input-group input-group-merge input-group-alternative">
                            <input class="form-control" type="text" v-model="uf" placeholder="GO"  readonly>
                        </div>
                        
                        <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.endereco">
                            <strong  v-if="error.endereco.uf">{{error.endereco.uf[0]}}</strong>
                        </span>
                        </div>

                        <div class="form-group col-md-6">

                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                </div>
                                <input class="form-control" type="text" v-model="complemento" placeholder="Complemento endereço">
                            </div>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.endereco">
                                <strong>{{error.endereco.complemento[0]}}</strong>
                            </span>
                           
                        </div>
                    </div>
                    </ValidationObserver>
                    <hr>
                </tab-content>
                <tab-content title="Dados Acessos" icon="fas fa-key" >
                    <ValidationObserver ref="form3">
                    <div class="form-group">
                        <ValidationProvider rules="required" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-key-25"></i></span>
                                </div>
                                 <input class="form-control" v-model="password" placeholder="Senha" type="password">
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.password">
                                <strong>{{error.password[0]}} teste</strong>
                            </span>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>

                        </ValidationProvider>
                    </div>
                     <div class="form-group">
                        <ValidationProvider rules="'required|confirmed:password'" v-slot="{ errors }">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-key-25"></i></span>
                                </div>
                                <input class="form-control" v-model="password_confirmation" placeholder="Confirmar Senha" type="password">
                            </div>

                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="error.password_confirmation">
                                <strong>{{error.password_confirmation[0]}}</strong>
                            </span>
                            
                            <span class="invalid-feedback" style="display: block;" role="alert" v-show="errors">
                                <strong>{{ errors[0] }}</strong>
                            </span>

                        </ValidationProvider>
                    </div>
                    </ValidationObserver>
                    <hr>
                </tab-content>
                
           </form-wizard>    
        </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Datepicker from 'vuejs-datepicker';
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
        email: '',
        documento: '',
        celular: '',
        data_nascimento: null,
        cep: '',
        rua: '',
        bairro: '',
        cidade: '',
        uf: '',
        complemento: '',
        site: '',
        descricao: '',
        capa: null,
        url_capa: null,
        latitude: '',
        longitude: '',
        error: {},
        errors: {},
        password: '',
        password_confirmation: '',
        inscricao_estadual: '',
        clicked: false,
        is_cnpj: false,
        col_md: 'col-md-4',
      }
    },
    mounted() {

    },
    methods: {
        onKeyUpDocumento() {
            if(this.documento.length > 14) {
                this.is_cnpj = true
                this.col_md = 'col-md-3'
            }else{
                this.is_cnpj = false
                 this.col_md = 'col-md-4'
            }
        },
      postUsuario() {
        this.clicked = true;
        var app = this
        let formData = new FormData()
        formData.append('nome',  app.nome)
        formData.append('email',  app.email)
        formData.append('documento',  app.documento)
        formData.append('celular',  app.celular)
        formData.append('password',  app.password)
        formData.append('password_confirmation',  app.password_confirmation)
        formData.append('data_nascimento',  this.customFormatter(app.data_nascimento))
        formData.append('endereco[cep]', app.cep)
        formData.append('endereco[rua]', app.rua)
        formData.append('endereco[bairro]', app.bairro)
        formData.append('endereco[cidade]', app.cidade)
        formData.append('endereco[estado]', app.uf)
        formData.append('endereco[complemento]', app.complemento)
        formData.append('inscricao_estadual', app.inscricao_estadual)

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `cliente`,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{		
                
                this.flashMessage.success({
                    message: 'Usuário cadastrado com sucesso!',
                    time: 5000
                });

                this.$router.push({name: 'user.list'})

            }, error => {
                this.clicked = false;
                switch(error.response.status){
                    case 422:
                        this.error=error.response.data.errors;
                        this.flashMessage.error({
                            message: 'Error no dados do usuário, por favor revise as informações!',
                            time: 5000
                        });
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao cadastrado Usuário!',
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
             this.$refs.form.validate().then(success => {
                if (!success) {
                        reject('error')
                    }
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
        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        },
    },
    components: {
      FormWizard,
      TabContent,
      ValidationProvider,
      ValidationObserver,
      Datepicker
    },
}
</script>