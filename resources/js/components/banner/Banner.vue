<template>
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista de banners</h3>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add" block>Adicionar</button>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="row justify-content-md-center">
                    <div class="col-md-auto" v-for="banner in banners" v-bind:key="banner.id">
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" :src="banner.imagem" height="180">
                            <div class="card-body text-center">
                                <a href="#" class="btn btn-danger" @click.prevent="showDelete(banner)">Excluir</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="banners.length == 0" class="row">
                    <div class="col text-center pt-5 pb-5">Nenhum banner encontrado. Clique em "Adicionar" e faça upload!</div>
                </div>
            </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Excluir Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-block">
                    <p>Tem certeza que deseja excluir o banner?</p>
                </div>
                <b-button class="mt-3" variant="outline-danger" block @click="deleteBanner(deleteBannerData)">Excluir</b-button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-create" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Banner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body px-lg-5 row">
                        <div class="col-12">
                            <div class="form-group">
                                <p style="text-align: center;" v-if="url_imagem"> <img width="250" height="150" :src="url_imagem" ></p>
                                <!-- <p style="text-align: center;" v-else> <img width="150px" v-bind:src="'../storage/imagem_perfil/' + this.editCampanhaData.imagem_perfil" ></p> -->
                                <b-form-file
                                v-model="imagem"
                                @change="onImagemChange"
                                placeholder="Selecione o banner"
                                drop-placeholder="Solte a imagem aqui..."
                                ></b-form-file>
                                <small class="text-muted" >Utilize imagens na proporção 16x9</small>
                                
                                <div class="invalid-feedback" v-if="errors.banner">
                                    <p>{{errors.banner[0]}}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button v-if="!loading" type="button" @click="postBanner()" class="btn btn-primary my-4">Salvar</button>
                            <button v-else type="button" disabled class="btn btn-primary my-4">Aguarde</button>
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
            banners: [],
            errors: {},
            deleteBannerData: {},
            imagem: null,
            url_imagem: null,
            loading: false
        }
    },
    mounted() {
      this.getBanners()
    },
    methods: {
        getBanners() {
            this.$http({
                url: `banner`,
                method: 'GET'
            }).then(response => {
                this.banners = response.data;
            }).catch(error=>{
                this.has_error = true
            })
        },
        postBanner() {
            var app = this
            app.loading = true
            let formData = new FormData()
            formData.append('banner',  app.imagem)

            let config = {
                headers: {
                'content-type': 'multipart/form-data'
                }
            }
            this.$http({
                url: `banner`,
                method: 'POST',
                data: formData,
                config: config,
            })
            .then(response => {		
                app.flashMessage.success({
                    message: 'Banner cadastrado com sucesso!',
                    time: 5000
                })
                app.imagem = null
                app.url_imagem = null
                app.hideModal()
                app.getBanners()
                app.loading = false
            }, error => {
                app.hideModal()
                app.loading = false
                console.log(error.response);
                if(error.response.data.hasOwnProperty('message')){
                    app.flashMessage.error({
                        message: error.response.data.message,
                        time: 5000
                    })
                }else{
                    app.flashMessage.error({
                        message: 'Erro ao cadastrar banner!',
                        time: 5000
                    })
                }
            })
        },
        getHumanDate : function (date) {
            return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
        },
        hideModal() {
            $('#modal-add').modal('hide')
        },
        showDelete(banner){
            this.deleteBannerData = banner
            this.showDeleteModal()
        },
        hideDeleteModal() {
            $('#modal-delete').modal('hide')
        },
        showDeleteModal() {
            $('#modal-delete').modal('show')
        },
        deleteBanner(banner) {
            this.$http({
                url: `banner/` + banner.id,
                method: 'DELETE',
            }).then(response=>{

                this.getBanners();
                this.hideDeleteModal();

                this.flashMessage.success({
                    message: 'Banner deletado com sucesso!',
                    time: 5000
                });
            }, error =>{
                this.flashMessage.error({
                    message: 'Error ao deletar banner!',
                    time: 5000
                });
            })
        },
        onImagemChange(e) {
            const file = e.target.files[0];
            this.imagem = file;
            this.url_imagem = URL.createObjectURL(file);
        }
    }
}
</script>