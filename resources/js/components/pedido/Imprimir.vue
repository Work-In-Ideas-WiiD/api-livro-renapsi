<template>
<div>
    <div id="acoes">
        <button @click="printDiv" >Imprimir</button>
    </div>
    <div id="printMe">
        <div class="container impressao">

        <div class="row sessao">
            <div class="col-12 col col-topo">
                <p class="stkr mxmz" style="text-align: center;">PEDIDO DE COMPRA</p>
            </div>
        </div>

        <div class="row">
            <div class="col-5">
            <p class="pedido_i" ><span class="stkr mxmz">Pedido nº: </span>{{ pedido.id }}</p>
            </div>
            <div class="col-4">
            <p class="datapedido_i" ><span class="stkr mxmz">Data de emissão: </span>{{ getHumanDate(pedido.created_at) }}</p>
            </div>
            <div class="col-3 col col-lastinarow">
            <p class="statuspedido_i" ><span class="stkr mxmz">Status: </span>{{ pedido.status.nome }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
            <p class="cliente_i" ><span class="stkr mxmz">Cliente: </span>{{ pedido.cliente.nome }}</p>
            </div>
            <div class="col-4">
            <p class="vendedora_i" ><span class="stkr mxmz">Vendedor(a): </span>{{ pedido.administrador.email }}</p>
            </div>
            <div class="col-4 col col-lastinarow">
            <p class="tipopagto_i" ><span class="stkr mxmz">Tipo de Pagto.: </span>{{ pedido.pagamento }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
            <p class="doccliente_i" ><span class="stkr mxmz">CPF/CNPJ: </span>{{ pedido.cliente.documento }}</p>
            </div>
            <div class="col-4">
            <p class="clienteemail_i" ><span class="stkr mxmz">Email: </span>{{ pedido.cliente.email }}</p>
            </div>
            <div class="col-4 col col-lastinarow">
            <p class="clientetel_i" ><span class="stkr mxmz">Telefone: </span>{{ pedido.cliente.celular }}</p>
            </div>
            <div v-if="pedido.observacoes != null && pedido.observacoes.trim() != ''" class="col-12 col col-lastinarow">
            <p class="clientetel_i" ><span class="stkr mxmz">Observações: </span>{{ pedido.observacoes }}</p>
            </div>
        </div>

        <div class="row sessao">
            <div class="col-12 col col-topo">
                <p class="stkr mxmz" style="text-align: center;">ITENS DO PEDIDO</p>
            </div>
        </div>

        <table class="table" id="">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Ref.</th>
                    <th>Item</th>
                    <th>Tam.</th>
                    <th>Valor</th>
                    <th>Qtd.</th>
                    <th>Sub-total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(produto, index) in sortArrays(pedido.produto)" v-bind:key="produto.id">
                    <td class="text-center">{{ index + 1 }}</td>
                    <td >{{ produto.produto.sku }}</td>
                    <td >{{ produto.produto.nome }}</td>
                    <td >{{ produto.atributo ? produto.atributo.atributo_valor.valor : '' }}</td>
                    <td >&#82;&#36; {{ valorproduto(produto.preco, produto.quantidade) }}</td>
                    <td >{{ produto.quantidade }}</td>
                    <td >&#82;&#36; {{ produto.preco }} </td> <!-- Dinâmico -->
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td class="text-center"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><span class="stkr mxmz">TOTAL:</span></td>
                    <td>{{ pedido.quantidade_produto }}</td>                    <!-- Dinâmico -->
                    <td>&#82;&#36; {{ pedido.total }}</td>       <!-- Dinâmico -->
                </tr>
            </tbody>
        </table>

        <div class="row sessao">
            <div class="col-12 col col-topo">
                <p class="stkr mxmz" style="text-align: center;">RESUMO</p>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
            <p class="qtdprod_i" ><span class="stkr mxmz">Qtd. de Prod.: </span>{{ pedido.quantidade_produto }}</p> <!-- Dinâmico -->
            </div>
            <div class="col-3">
            <p class="taxas_i" ><span class="stkr mxmz">Taxas: </span>&#82;&#36; {{ pedido.taxa }}</p>
            </div>
            <div class="col-3">
            <p class="descontos_i" ><span class="stkr mxmz">Descontos: </span>&#82;&#36; {{ pedido.desconto }}</p>
            </div>
            <div class="col-4 col col-lastinarow">
            <p class="totaprod_i" ><span class="stkr mxmz">Total em Produtos: </span>&#82;&#36; {{ pedido.total_produtos }}</p> <!-- Dinâmico -->
            </div>
        </div>
        <div class="row">
            <div class="offset-8 col-4 col col-lastinarow">
            <p class="totalpedido_i" ><span class="stkr mxmz">Total do Pedido: </span>&#82;&#36; {{ pedido.total }}</p> <!-- Dinâmico -->
            </div>
        </div>


        <div class="row sessao">
            <div class="col-12 col col-topo">
                <p class="stkr mxmz" style="text-align: center;">INFORMAÇÕES DE ENTREGA</p>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
            <p class="tipoentrega_i" ><span class="stkr mxmz">Tipo: </span>{{ pedido.entrega.nome }}</p>
            </div>
            <div class="col-6">
            <p class="endentrega_i" ><span class="stkr mxmz">Endereço: </span>{{ pedido.endereco.rua }} {{ pedido.endereco.bairro }}</p>
            </div>
            <div class="col-3 col col-lastinarow">
            <p class="cepentrega_i" ><span class="stkr mxmz">CEP: </span>{{ pedido.endereco.cep }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
            <p class="compentrega_i" ><span class="stkr mxmz">Complemento: </span>{{ pedido.endereco.complemento }}</p>
            </div>
            <div class="col-6 col col-lastinarow">
            <p class="cidadeentrega_i" ><span class="stkr mxmz">Cidade: </span>{{ pedido.endereco.cidade }}</p>
            </div>
        </div>

        </div>
    </div>
</div>
</template>
<style>
.impressao
{
	border: 2px solid #000;
}

.stkr
{
	font-family: Open Sans,sans-serif;
    font-size: 1rem;
    font-weight: 600;
}

.mxmz
{

}

#acoes
{
	text-align: center;
}

.impressao p
{
	margin-bottom: 4px;
	padding: 0;
}

.impressao .col-1, .impressao .col-2, .impressao .col-3, .impressao .col-4,
.impressao .col-5, .impressao .col-6, .impressao .col-7, .impressao .col-8,
.impressao .col-9, .impressao .col-10, .impressao .col-11, .impressao .col-12
{
	border-left: 1px solid #000;
	border-bottom: 1px solid #000;
}

.impressao,
.impressao .table
{
	color: #000;
}

.impressao .sessao:not(:first-child)
{
	margin-top: 3em;
}

.impressao .table th,
.impressao .table td
{
	padding: 0.25rem;
}

.impressao .col-topo
{
	border-right: 1px solid #000;
	border-top: 1px solid #000;
}

.impressao .col-lastinarow
{
	border-right: 1px solid #000;
}
</style>
<script>
import moment from 'moment/moment';
export default {
    data() {
      return {
        has_error: false,
        pedido: {},
        errors: {},
      }
    },
    mounted() {
      this.getPedido();
      this.hidenSideBar();
    },
    methods: {
    sortArrays(arrays) {
        return _.orderBy(arrays, ['produto.sku', 'atributo.atributo_valor.valor'], ['asc', 'asc']);
    },
    printDiv(){
        var css = "@page { margin: 3.8mm 1.5mm 3.8mm 1.5mm; }";
        var head = document.head || document.getElementsByTagName('head')[0];
        var style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet){
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        var printContents = document.getElementById('printMe').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

    },
      print () {
        // Pass the element id here
        this.$htmlToPaper('printMe');
      },
      getPedido() {
        this.$http({
        url: `pedido/${this.$route.params.id}${typeof this.$route.params.estoque != 'undefined' ? '?print' : ''}`,
        method: 'GET'
        })
        .then(response=>{

            this.pedido = response.data;

        }).catch(error=>{
            this.has_error = true
        })
      },
      totalproduto(preco, quantidade) {
        return parseFloat(parseFloat(preco) * parseInt(quantidade)).toFixed(2).replace('.', ',');
      },
      valorproduto(preco, quantidade) {
        return parseFloat(parseFloat(preco) / parseInt(quantidade)).toFixed(2).replace('.', ',');
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY');
      },
      hidenSideBar()
      {
         document.getElementById('sidenav-main').style.display = "none";
      }
    }
}
</script>
