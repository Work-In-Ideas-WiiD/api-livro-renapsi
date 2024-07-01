<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista produtos</h3>
                    </div>
                    <div class="col-4 text-right">
                        <router-link v-if="$auth.check([1,6])" type="button" to="produto_criar" class="btn btn-sm btn-primary">Adicionar</router-link >
                        <a v-if="$auth.check([1,6])" @click="exportRelatorio" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-success">Exportar</a>
                    </div>
                </div>
            </div>

            <!-- Light table -->
            <search v-on:search="searchProdutos" ></search>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="status">Produto</th>
                    <th scope="col" class="sort" data-sort="status" >Codigo</th>
                    <th scope="col" class="sort" data-sort="status" >Quantidade</th>
                    <th scope="col" class="sort" data-sort="status" >Categoria</th>
                    <th scope="col" class="sort" data-sort="status" >Images</th>
                    <th scope="col" class="sort" data-sort="completion">Status</th>
                    <th scope="col" class="sort" data-sort="completion">Data Cadastro</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="produto in produtos.data" v-bind:key="produto.id" style="margin-bottom: 5px;">
                        <td>
                            <div class="media align-items-center">
                              <a href="#" class="avatar mr-3">
                                <img v-bind:alt="produto.nome" v-bind:src="produto.capa">
                              </a>
                              <div class="media-body">
                                <span class="name mb-0 text-sm">{{ produto.nome }}</span>
                              </div>
                            </div>
                        </td>
                        <td>{{ produto.sku }}</td>
                        <td>{{ produto.quantidade }}</td>
                        <td>
                            <span v-for="(categoria, index) in produto.categoria" :key="categoria.id">
                                {{ categoria.nome }}{{ ((index + 1) == produto.categoria.length ? '' : ', ') }}
                            </span>
                        </td>
                        <td>
                            <div class="avatar-group">
                                <a v-for="image in produto.images" v-bind:key="image.id" class="avatar avatar-sm rounded-circle" data-toggle="tooltip">
                                    <img v-bind:src="image.url">
                                </a>
                            </div>
                        </td>
                        <td v-if="produto.status == 1"><a class="badge badge-pill badge-success"> Ativo </a> </td>
                        <td v-else ><a class="badge badge-pill badge-danger"> Inativo </a> </td>
                        <td>{{ getHumanDate(produto.created_at) }}</td>
                        <td>
                            <button v-if="$auth.check([1,3,6])" class="btn-circle btn-sm btn-primary" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="$router.push({name: 'produto.edit', params: { id: produto.id },})"><i class="fas fa-fw fa-edit"></i></button>
                            <button v-if="$auth.check([1,6])" class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(produto)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr v-if="produtos.data.length < 1">
                        <td class="text-center" colspan="8">Nenhum registro foi encontrado.</td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :limit="12" :data="produtos" @pagination-change-page="getProdutos"></pagination>
            </div>
          </div>
        </div>
      </div>



    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Excluir Produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar o produto: <b>{{ deleteProdutoData.nome }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteProduto(deleteProdutoData)">Excluir</b-button>
        </div>
        </div>
    </div>
    </div>

</div>


</template>
<script>
import moment from 'moment/moment';
import search from '../search/SearchProduto.vue';
export default {
     data() {
      return {
        has_error: false,
        produtos: {},
        errors: {},
        deleteProdutoData: {},
        searchLike: '',
        searchCategoria: '',
        searchCodigo: '',
        searchOder: '',
        searchMostrar: ''
      }
    },
    mounted() {
      this.getProdutos()
      this.getTipoVenda()
    },
    methods: {
        exportRelatorio(){
          this.$http({
            url: `export/produto`,
            method: 'GET'
            })
            .then(response=>{
                window.open(response.data.url)
            }, error=>{
                this.has_error = true
            })
        },
        searchProdutos(payload){
            this.$http({
                url: 'produto',
                method: 'GET',
                params: {
                like: payload.dados.nome,
                categoria: payload.dados.categoria,
                codigo: payload.dados.codigo,
                order: payload.dados.ordem,
                mostrar: payload.dados.mostrar,
                status: true
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchCategoria = payload.dados.categoria;
                this.searchCodigo = payload.dados.codigo;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.produtos = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        hideModal() {
          $('#modal-form').removeClass('show')
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
            categoria: this.searchCategoria,
            codigo: this.searchCodigo,
            order: this.searchOder,
            mostrar: this.searchMostrar,
            status: true
          },
        })
          .then(response => {
              this.produtos = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getTipoVenda(){
        var app = this
        this.$http({
        url: `user/atacado`,
        method: 'POST',
        params: {
            atacado: false,
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
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
        hideEditModal() {
          $('#modal-form-edit').removeClass('show')
        },
        editCategoria(produto) {
          this.editProdutoData = produto;
          this.errors = {};
          // this.showEditModal();
        },
      showDelete(produto){
        this.deleteProdutoData = produto;
      },
      hideDeleteModal() {
        $('#modal-delete').removeClass('show')
      },
      deleteProduto(produto) {

        this.$http({
            url: `produto/` + produto.id,
            method: 'DELETE',
          })

          .then(response=>{
            this.produtos.data = this.produtos.data.filter(obj => {
                return obj.id != produto.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Produto deletado com sucesso!',
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
            this.imagem = file;
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
