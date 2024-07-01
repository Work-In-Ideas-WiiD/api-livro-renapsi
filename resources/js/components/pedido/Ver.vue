<template>
    <div class="container-fluid mt--6" ref="testHtml">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
            <img src="/assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <img src="/assets/img/theme/avatar_blue.jpg" class="rounded-circle">
                  </a>
                </div>
              </div>
            </div>
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
              <div class="d-flex justify-content-between">
                <!-- <a href="#" class="btn btn-sm btn-info  mr-4 ">Connect</a>
                <a href="#" class="btn btn-sm btn-default float-right">Message</a> -->
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col">
                  <div class="card-profile-stats d-flex justify-content-center">
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h5 class="h3">
                  {{ pedido.cliente.nome }}<span class="font-weight-light">, 27</span>
                </h5>
                <h5 class="h3">
                 {{ this.name }}
                </h5>
                <div class="h5 font-weight-300">
                  <i class="ni location_pin mr-2"></i> {{ pedido.cliente.documento }} - {{ pedido.cliente.email }}
                </div>
                <div class="h5 mt-4">
                  <i class="ni business_briefcase-24 mr-2"></i>CEP: {{ pedido.endereco.cep }}
                  <i class="ni business_briefcase-24 mr-2"></i>{{ pedido.endereco.bairro }} - {{ pedido.endereco.rua }}
                  <i class="ni business_briefcase-24 mr-2"></i>{{ pedido.endereco.cidade }} - {{ pedido.endereco.estado }}
                </div>
                <div>
                  <i class="ni education_hat mr-2"></i>{{ pedido.endereco.complemento }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8 order-xl-1">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-12 text-right mb-2">
                    <div v-if="pedido.comprovante_envio" class="d-inline-block mr-3">
                        <a v-if="$auth.check([2,4])" type="button" v-bind:href="'/storage/comprovantes_envio/' + pedido.comprovante_envio" class="btn btn-sm btn-success" download>Baixar Comprovante Envio</a >
                        <a v-if="$auth.check([2,4])" href="#" type="button" data-toggle="modal" data-target="#modal-form-envio" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a >
                    </div>
                    <div v-else class="d-inline-block mr-3">
                        <a v-if="$auth.check([2,4])" href="#" type="button" data-toggle="modal" data-target="#modal-form-envio" class="btn btn-sm btn-info">Enviar Comprovante Envio</a >
                    </div>
                    <div v-if="pedido.comprovante_entrega" class="d-inline-block mr-3">
                        <a v-if="$auth.check([1,2,3,4,6])" type="button" v-bind:href="'/storage/comprovantes_entrega/' + pedido.comprovante_entrega" class="btn btn-sm btn-success" download>Baixar Comprovante Entrega</a >
                        <a v-if="$auth.check([1,2,4,6])" href="#" type="button" data-toggle="modal" data-target="#modal-form-entrega" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a >
                    </div>
                    <div v-else class="d-inline-block mr-3">
                        <a v-if="$auth.check([1,2,3,4,6])" href="#" type="button" data-toggle="modal" data-target="#modal-form-entrega" class="btn btn-sm btn-info">Enviar Comprovante Entrega</a >
                    </div>
                    <div v-if="pedido.comprovante" class="d-inline-block">
                        <a v-if="$auth.check([1,2,3,5,6])" type="button" v-bind:href="'/storage/comprovantes/' + pedido.comprovante" class="btn btn-sm btn-success" download>Baixar Comprovante</a >
                        <a v-if="$auth.check([1,2,5,6])" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a >
                    </div>
                    <div v-else class="d-inline-block">
                        <a v-if="$auth.check([1,2,5,6])" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-info">Comprovantes</a >
                    </div>
                </div>
                <div class="col-6">
                  <h3 class="mb-0">Referência: {{ pedido.id }} </h3>
                </div>
                <div class="col-12"></div>
                <div class="col-4 mt-3">
                  <h3 class="mb-0">
                    Nota Fiscal:
                      <span v-if="pedido.nota_fiscal == null" class="text-danger">Não emitida</span>
                      <span v-else-if="pedido.nota_fiscal != null && pedido.nota_fiscal.status == 0" class="text-warning">Aguardando Emissão</span>
                      <span v-else-if="pedido.nota_fiscal != null && pedido.nota_fiscal.status == 2" class="text-danger">Erro Na Emissão</span>
                      <span v-else class="text-success">Emitida</span>
                  </h3>
                  <span v-if="pedido.nota_fiscal != null && pedido.nota_fiscal.status == 2 && pedido.nota_fiscal.erro != null"><strong>Erro: </strong><span class="text-muted">{{pedido.nota_fiscal.erro}}</span></span>
                </div>
                <div class="col-8 mt-3 text-right">
                  <a v-if="$auth.check([1,2,5]) && !loadingNF && pedido.nota_fiscal != null && pedido.nota_fiscal.status == 1" v-bind:href="'/ver_nota_fiscal/'+ pedido.id" type="button" class="btn btn-sm btn-warning" target="_blank">Ver Nota Fiscal</a >
                  <a v-if="$auth.check([1,2,5]) && !loadingNF && pedido.nota_fiscal != null && pedido.nota_fiscal.status == 1" v-bind:href="'/download_nota_fiscal/'+ pedido.id" type="button" class="btn btn-sm btn-success" v-bind:download="'Nota Fiscal - Pedido ' + pedido.id + '.xml'">Baixar XML</a >
                  <a v-if="$auth.check([1,2,5]) && !loadingNF && pedido.nota_fiscal != null && pedido.nota_fiscal.status == 2" @click="createNF(true)" href="#" type="button" class="btn btn-sm btn-info">Reemitir Nota Fiscal</a >
                  <a v-if="$auth.check([1,2,5]) && !loadingNF && pedido.nota_fiscal == null" @click="createNF()" href="#" type="button" class="btn btn-sm btn-info">Emitir Nota Fiscal</a >
                  <a v-else-if="$auth.check([1,2,5]) && !loadingNF && pedido.nota_fiscal != null && pedido.nota_fiscal.status == 1" @click="sendNF()" href="#" type="button" class="btn btn-sm btn-info">Enviar Nota Fiscal Por E-mail</a >
                  <span v-else-if="loadingNF"><i class="fa fa-spin fa-spinner"></i></span>
                </div>
              </div>
            </div>
            <div class="card-body" id="printMe">
                <h6 v-if="pedido.observacoes != null && pedido.observacoes.trim() != ''" class="heading-small text-danger">Observações</h6>
                <div v-if="pedido.observacoes != null && pedido.observacoes.trim() != ''" class="text-left text-muted">
                  {{ pedido.observacoes }}
                  <hr>
                </div>
                <h6 class="heading-small text-muted mb-4">Informações do pedido</h6>
                <div class="text-right">
                  <router-link v-if="$auth.check([1,2,3])" :to="'/pedido_imprimir/' + pedido.id" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Imprimir</router-link >
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status">Data</th>
                            <th scope="col" class="sort" data-sort="status" >Cliente</th>
                            <th scope="col" class="sort" data-sort="status" >Pagamento</th>
                            <th scope="col" class="sort" data-sort="completion">Status</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td>{{ getHumanDate(pedido.created_at) }}</td>
                                <td>{{ pedido.cliente.nome }}</td>
                                <td>{{ pedido.pagamento }}</td>
                                <td v-if="pedido.status.nome != 'Cancelado' && $auth.check([1,2,3,5,4])">
                                    <b-form-select class="dorder-select col-md-10" v-bind:style="'color: ' + pedido.status.color" v-model="pedido.status.id" :options="status"></b-form-select>

                                        <button class="btn btn-icon btn-info" type="button" @click="getStatusPedido()">
                                            <span class="btn-inner--icon"><i class="fas fa-pencil-alt"></i></span>
                                        </button>
                                </td>
                                <td v-else v-bind:style="'color: ' + pedido.status.color">
                                  {{ pedido.status.nome }}
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td></td>
                                <td></td>
                                <td class="td-warning">Data do Pagamento</td>
                                <td class="td-warning" >
                                  {{ pedido.data_pagamento }}
                                  <button v-if="$auth.check(1)" class="btn btn-icon btn-info btn-sm ml-2" type="button" @click="showModal('changePaymentDate')">
                                    <span class="btn-inner--icon"><i class="fas fa-pencil-alt"></i></span>
                                  </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td></td>
                                <td></td>
                                <td class="td-warning">Quantidade Total Produtos</td>
                                <td class="td-warning" >{{ pedido.quantidade_produto }}</td>
                            </tr>
                        </tbody>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td></td>
                                <td></td>
                                <td class="td-warning">Taxa</td>
                                <td class="td-warning" >{{ formatPrice(pedido.taxa) }}</td>
                            </tr>
                        </tbody>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td></td>
                                <td></td>
                                <td class="td-warning" >Desconto</td>
                                <td class="td-warning" >{{ formatPrice(pedido.desconto) }}</td>
                            </tr>
                        </tbody>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td></td>
                                <td></td>
                                <td class="td-warning">Total Produtos</td>
                                <td class="td-warning" >{{ formatPrice(pedido.total_produtos) }}</td>
                            </tr>
                        </tbody>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td></td>
                                <td></td>
                                <td class="td-success">Total</td>
                                <td class="td-success" >{{ formatPrice(pedido.total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="my-4">
                <h6 class="heading-small text-muted mb-4">Items do pedido</h6>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status">Nome</th>
                            <th scope="col" class="sort" data-sort="status">Referência</th>
                            <!-- <th scope="col" class="sort" data-sort="status" >Descrição</th>-->
                            <th scope="col" class="sort" data-sort="status" >Tamanho</th>
                            <th scope="col" class="sort" data-sort="status" >Quantidade</th>
                            <th scope="col" class="sort" data-sort="completion">Preço</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr v-for="produto in sortArrays(pedido.produto)" v-bind:key="produto.id" style="margin-bottom: 5px;">
                                <td>{{ produto.produto != null ? produto.produto.nome : 'Produto não encontrado' }}</td>
                                <td>{{ produto.produto != null ? produto.produto.sku : '' }}</td>
                                <!-- <td>{{ produto.produto.descricao }}</td> -->
                                <td>{{ produto.atributo ? produto.atributo.atributo_valor.valor : '' }}</td>
                                <td>{{ produto.quantidade }}</td>
                                <td>{{ formatPrice(produto.preco) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="my-4">
                <h6 class="heading-small text-muted mb-4">Entrega do pedido</h6>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status">Nome</th>
                            <th scope="col" class="sort" data-sort="status">Data Entrega</th>
                            <th scope="col" class="sort" data-sort="status" >Descrição</th>
                            <th scope="col" class="sort" data-sort="status" >Link</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td>{{ pedido.entrega.nome }}</td>
                                <td>{{ pedido.data_entrega }}</td>
                                <td>{{ pedido.entrega.descricao }}</td>
                                <td>{{ pedido.entrega.url }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="my-4">
                <h6 class="heading-small text-muted mb-4">Endereço de entrega</h6>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status" >CEP</th>
                            <th scope="col" class="sort" data-sort="status" >Endereço</th>
                            <th scope="col" class="sort" data-sort="status" >Complemento</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td>{{ pedido.endereco.cep }}</td>
                                <td>{{ pedido.endereco.rua }} {{ pedido.endereco.bairro }} {{ pedido.endereco.cidade }} - {{ pedido.endereco.estado }} </td>
                                <td>{{ pedido.endereco.complemento }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="my-4">
                <h6 v-if="pedido.administrador" class="heading-small text-muted mb-4">Administardor</h6>
                <div v-if="pedido.administrador" class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="status">Administardor</th>
                            <th scope="col" class="sort" data-sort="status">Celular</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <tr style="margin-bottom: 5px;">
                                <td>{{ pedido.administrador.email }}</td>
                                <td>{{ pedido.administrador.celular }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Enviar Comprovante</small></h5>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card-body px-lg-5">
                            <form role="form">
                                <div class="form-group">
                                    <!-- <p style="text-align: center;" v-if="url_imagem"> <img width="150px" :src="url_imagem" ></p> -->
                                    <!-- <p style="text-align: center;" v-if="url_pdf"> <img width="150px" src="/assets/img/theme/pdf_icon.png" ></p> -->
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-xl-4" v-for="(item, index) in comprovantes" :key="index">
                                            <div v-if="item.file != null && item.file.type == 'application/pdf'" class="card">
                                                <img class="card-img-top" src="/images/logo-pdf.png" height="180" width="100%">
                                                <div class="card-body text-center">
                                                    <a @click.prevent="removeComprovante(index)" href="#" class="btn btn-sm btn-danger">Remover</a>
                                                </div>
                                            </div>
                                            <div v-else-if="item.file != null" class="card">
                                                <img class="card-img-top" :src="getUrl(item.file)" height="180" width="100%">
                                                <div class="card-body text-center">
                                                    <a @click.prevent="removeComprovante(index)" href="#" class="btn btn-sm btn-danger">Remover</a>
                                                </div>
                                            </div>
                                            <div v-if="item.database != null && item.database.url.split('.')[1] != 'pdf'" class="card">
                                                <img class="card-img-top" :src="`/storage/comprovantes/${item.database.url}`" height="180" width="100%">
                                                <div class="card-body text-center">
                                                    <a :href="`/storage/comprovantes/${item.database.url}`" class="btn btn-sm btn-info" download>Baixar</a>
                                                    <a @click.prevent="removeComprovante(index)" href="#" class="btn btn-sm btn-danger">Remover</a>
                                                </div>
                                            </div>
                                            <div v-else-if="item.database != null" class="card">
                                                <img class="card-img-top" src="/images/logo-pdf.png" height="180" width="100%">
                                                <div class="card-body text-center">
                                                    <a :href="`/storage/comprovantes/${item.database.url}`" class="btn btn-sm btn-info" download>Baixar</a>
                                                    <a @click.prevent="removeComprovante(index)" href="#" class="btn btn-sm btn-danger">Remover</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="nome">Arquivo</label>
                                    <!-- <p style="text-align: center;" v-else> <img width="150px" v-bind:src="'../storage/imagem_perfil/' + this.editCampanhaData.imagem_perfil" ></p> -->
                                    <div class="custom-file b-form-file">
                                        <input @change="onChangeComprovante" ref="file" type="file" class="custom-file-input" id="selecionar-arquivos" style="z-index: -5;" multiple="multiple" accept=".pdf,.png,.jpg,.jpeg" />
                                        <label data-browse="Browse" class="custom-file-label" for="selecionar-arquivos"><span class="d-block form-file-text" style="pointer-events: none;">Selecione um ou mais arquivos</span></label>
                                    </div>

                                    <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, PNG, PDF</small>

                                    <div class="invalid-feedback" v-if="errors.capa">
                                        <p>{{errors.capa[0]}}</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                                    <button type="button" @click="postCompravante" class="btn btn-primary my-4">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="modal fade" id="modal-form-entrega" tabindex="-1" role="dialog" aria-labelledby="modal-form-entrega" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Enviar Comprovante Entrega</small></h5>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card-body px-lg-5">
                            <form role="form">
                                <div class="form-group">
                                    <p style="text-align: center;" v-if="url_imagem"> <img width="150px" :src="url_imagem" ></p>
                                    <p style="text-align: center;" v-if="url_pdf"> <img width="150px" src="/assets/img/theme/pdf_icon.png" ></p>
                                    <label for="nome">Arquivo</label>
                                    <!-- <p style="text-align: center;" v-else> <img width="150px" v-bind:src="'../storage/imagem_perfil/' + this.editCampanhaData.imagem_perfil" ></p> -->
                                    <b-form-file
                                    v-model="imagem"
                                    @change="onImagemChange"
                                    placeholder="Selecione arquivo de comprovante"
                                    drop-placeholder="Solte o aqruivo aqui..."
                                    ></b-form-file>
                                    <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, PNG, PDF</small>

                                    <div class="invalid-feedback" v-if="errors.capa">
                                        <p>{{errors.capa[0]}}</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                                    <button v-if="!loading_comprovante" type="button" @click="postCompravanteEntrega" class="btn btn-primary my-4">Enviar</button>
                                    <button v-else type="button" class="btn btn-primary my-4 disabled" disabled>Aguarde</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="modal fade" id="modal-form-envio" tabindex="-1" role="dialog" aria-labelledby="modal-form-envio" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Enviar Comprovante Envio</small></h5>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card-body px-lg-5">
                            <form role="form">
                                <div class="form-group">
                                    <p style="text-align: center;" v-if="url_imagem"> <img width="150px" :src="url_imagem" ></p>
                                    <p style="text-align: center;" v-if="url_pdf"> <img width="150px" src="/assets/img/theme/pdf_icon.png" ></p>
                                    <label for="nome">Arquivo</label>
                                    <!-- <p style="text-align: center;" v-else> <img width="150px" v-bind:src="'../storage/imagem_perfil/' + this.editCampanhaData.imagem_perfil" ></p> -->
                                    <b-form-file
                                    v-model="imagem"
                                    @change="onImagemChange"
                                    placeholder="Selecione arquivo de comprovante"
                                    drop-placeholder="Solte o aqruivo aqui..."
                                    ></b-form-file>
                                    <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, PNG, PDF</small>

                                    <div class="invalid-feedback" v-if="errors.capa">
                                        <p>{{errors.capa[0]}}</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                                    <button v-if="!loading_comprovante" type="button" @click="postCompravanteEnvio" class="btn btn-primary my-4">Enviar</button>
                                    <button v-else type="button" class="btn btn-primary my-4 disabled" disabled>Aguarde</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="modal fade" id="modal-confirm-status" tabindex="-1" role="dialog" aria-labelledby="modal-confirm-status-label" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-muted" id="modal-confirm-status-label">Confirmar Alteração de Status</h5>
                    </div>
                    <div class="modal-body p-0">
                      <div class="card-body px-lg-5">
                        <div v-if="status[pedido.status.id] == 'Em separação'" class="form-group">
                          <label for="field-data-entrega">Data Entrega:  <span class="text-muted"><sup>(Opcional)</sup></span></label>
                          <input v-model="data_entrega" type="date" class="form-control" id="field-data-entrega">
                        </div>
                        <div v-if="status[pedido.status.id] == 'Em separação'" class="form-group">
                          <label for="field-transportadora">Transportadora:</label>
                          <select v-model="transportadora" type="date" class="form-control" id="field-transportadora">
                            <option v-for="(row, index) in transportadoras" :key="index" :value="row.id">
                              {{ row.nome }} - {{ row.entrega_gratis == 1 ? 'GRÁTIS' : 'PAGO' }}
                            </option>
                          </select>
                        </div>
                        <div v-if="status[pedido.status.id] == 'Em separação'" class="form-group">
                          <label for="field-observacoes">Observações: <span class="text-muted"><sup>(Opcional)</sup></span></label>
                          <textarea v-model="observacoes" class="form-control" id="field-observacoes" rows="3"></textarea>
                        </div>
                        <div class="pt-2 pb-2 text-center">Esta ação é irreversível, deseja continuar?</div>
                        <div class="text-center">
                          <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Cancelar</button>
                          <button type="button" @click="getStatusPedido(true)" class="btn btn-primary my-4">Continuar</button>
                        </div>
                      </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="modal fade" id="changePaymentDate" tabindex="-1" role="dialog" aria-labelledby="changePaymentDateLabel" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Alterar data de pagamento</small></h5>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card-body px-lg-5">
                            <form role="form">
                                <div class="form-group">
                                    <label for="field-data-pagamento">Data Pagamento:</label>
                                    <input v-model="data_pagamento_edit" type="date" class="form-control" id="field-data-pagamento">
                                </div>
                                <div class="text-center">
                                    <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                                    <button v-if="!loading_pagamento" type="button" @click="postDataPagamento" class="btn btn-primary my-4">Salvar</button>
                                    <button v-else type="button" class="btn btn-primary my-4 disabled" disabled>Aguarde</button>
                                </div>
                            </form>
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
        id: this.$auth.user().id,
        name: this.$auth.user().name,
        email: this.$auth.user().email,
        password: null,
        password_confirmation: null,
        status: {},
        errors: {},
        pedido: {},
        imagem: null,
        url_imagem: null,
        url_pdf: false,
        todos: [
          {title: 'todo 1', description: 'description 1'},
          {title: 'todo 2', description: 'description 2'},
          {title: 'todo 3', description: 'description 3'},
          {title: 'todo 4', description: 'description 4'},
          {title: 'todo 5', description: 'description 5'}
        ],
        loadingNF: false,
        roles_status: {},
        observacoes: null,
        data_entrega: null,
        transportadora: '',
        transportadoras: [],
        comprovantes: [],
        data_pagamento_edit: '',
        loading_comprovante: false,
        loading_pagamento: false,
      }
    },
    mounted() {
        this.getPedido(() => {
          if(this.pedido.nota_fiscal.status == 0){
            this.verificarNF();
            setTimeout(() => {
              if(this.pedido.nota_fiscal.status == 0){
                this.verificarNF();
              }
            }, 1000 * 30)
          }
        });
        this.getStatus();
    },
    methods: {
        postDataPagamento(){
            this.$http({
                url: `pedido/data/pagamento/${this.pedido.id}`,
                method: 'post',
                params: {
                    data_pagamento: this.data_pagamento_edit,
                }
            }).then(response=>{
                this.hideModalPayment();
                this.getPedido();

                this.flashMessage.success({
                    message: 'Data pagamento alterada com sucesso!',
                    time: 5000
                });

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao excluir comprovante!',
                            time: 5000
                        });
                        break;
                }
            })
        },
        removeComprovante(index){
            let item = this.comprovantes[index]
            if(item.file != null) {
                this.comprovantes.splice(index, 1)
            }else{
                this.$http({
                    url: `pedido_comprovante/${item.database.id}/remover`,
                    method: 'post',
                }).then(response=>{
                    this.getPedido();

                    this.flashMessage.success({
                        message: 'Comprovante excluído com sucesso!',
                        time: 5000
                    });

                }, error => {
                    switch(error.response.status){
                        case 422:
                            this.errors=error.response.data.errors;
                            break;
                        default:
                            this.flashMessage.error({
                                message: 'Error ao excluir comprovante!',
                                time: 5000
                            });
                            break;
                    }
                })
            }
        },
        getUrl(file){
            return URL.createObjectURL(file);
        },
        onChangeComprovante() {
            for( var i = 0; i < this.$refs.file.files.length; i++ ){
                this.comprovantes.push({
                    file: this.$refs.file.files[i],
                    database: null,
                })
            }
            document.getElementById('selecionar-arquivos').value = null;
        },
      sendNF() {
        this.loadingNF = true
        this.$http({
          url: `enviar_nota_fiscal/` + this.pedido.id,
          method: 'GET'
        })
          .then(response => {
            if(response.data.success){
              this.flashMessage.success({
                    message: 'Nota Fiscal enviada com sucesso!',
                    time: 5000
                });
            }else{
              this.flashMessage.error({
                    message: response.data.msg,
                    time: 5000
                });
            }
            this.loadingNF = false
          }).catch(error=>{
               this.flashMessage.error({
                    message: 'Error ao emitir nota fiscal!',
                    time: 5000
                });
                this.loadingNF = false
          })
      },
      verificarNF() {
        this.loadingNF = true
        this.$http({
          url: `verificar-nota-fiscal/` + this.pedido.id,
          method: 'GET'
        }).then(response => {
          this.getPedido()
          this.loadingNF = false
        }).catch(error=>{
              this.loadingNF = false
        })
      },
      createNF(reemitir = false) {
        this.loadingNF = true
        this.$http({
          url: `emitir_nota_fiscal/` + this.pedido.id,
          method: 'GET',
          params: {
            reemitir: reemitir
          }
        })
          .then(response => {
            if(response.data.success){
              this.flashMessage.success({
                    message: 'Nota Fiscal emitida com sucesso!',
                    time: 5000
                });
            }else{
              this.flashMessage.error({
                  message: response.data.msg,
                  time: 5000
              });
            }
            this.getPedido(() => {
              setTimeout(() => {
                if(this.pedido.nota_fiscal.status == 0){
                  this.verificarNF();
                }
              }, 1000 * 30)
            });
            this.loadingNF = false
          }).catch(error=>{
               this.flashMessage.error({
                    message: 'Error ao emitir nota fiscal!',
                    time: 5000
                });
                this.loadingNF = false
          })
      },
      sortArrays(arrays) {
          return _.orderBy(arrays, ['produto.sku', 'atributo.atributo_valor.valor'], ['asc', 'asc']);
      },
      print () {
        // Pass the element id here
        this.$htmlToPaper('printMe');
      },
      formatPrice(value) {
        let val = (value/1).toFixed(2).replace('.', ',')
        return 'R$ ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      },
      showModal(id) {
        $('#'+id).modal('show')
      },
      hideModalPayment() {
        $('#changePaymentDate').modal('hide')
      },
      showModalConfirmStatus() {
        $('#modal-confirm-status').modal('show')
      },
      hideModalConfirmStatus() {
        $('#modal-confirm-status').modal('hide')
      },
      getStatusPedido(ignore_modal = false) {
        if(this.status[this.pedido.status.id] == this.pedido.status.nome) {
          this.flashMessage.error({
              message: 'Este é o atual status do pedido!',
              time: 5000
          });
          return
        }

        if(!this.$auth.check(this.roles_status[this.pedido.status.id])){
          this.flashMessage.error({
              message: 'Você não tem permissão para definir esse status para o pedido!',
              time: 5000
          });
          return
        }

        if(!ignore_modal){
          if(
            (this.status[this.pedido.status.id] == 'Em separação' && this.$auth.check(2))
            || (this.status[this.pedido.status.id] == 'Aguardando entrega (Pagamento pendente)' && this.$auth.check(3))
            || (this.status[this.pedido.status.id] == 'Aguardando entrega (Pagamento concluído)' && this.$auth.check(3))
            || (this.status[this.pedido.status.id] == 'Concluído' && this.$auth.check(4))
            || (this.status[this.pedido.status.id] == 'Aguardando' && this.$auth.check(5))
          ){
            this.showModalConfirmStatus()
            return
          }
        }else{
          this.hideModalConfirmStatus()
        }

        this.$http({
          url: `pedido/` + this.pedido.id,
          method: 'PUT',
          params: {
            pedido_status_id: this.pedido.status.id,
            observacoes: this.observacoes,
            data_entrega: this.data_entrega,
            entrega_id: this.transportadora
          },
        })
          .then(response => {
              this.getPedido(() => {
                this.data_entrega = ''
                this.transportadora = this.pedido.entrega_id
              })
              this.flashMessage.success({
                    message: 'Status atualizado com sucesso!',
                    time: 5000
                });
          }).catch(error=>{
               this.flashMessage.error({
                    message: 'Error ao atualizar status!',
                    time: 5000
                });
          })
      },
      getStatus() {
        this.$http({
          url: `pedido_status`,
          method: 'GET'
        })
            .then(response=>{

            let all_status = {}
            for(var k in response.data) {
              let roles = []
              response.data[k].roles.split(',').forEach(element => {
                roles.push(parseInt(element))
              })

              this.roles_status[response.data[k].id] = roles
              all_status[response.data[k].id] = response.data[k].nome
            }

            this.status = all_status;

            }).catch(error=>{
              this.has_error = true
            })
      },
      getPedido(callback = null) {
        this.$http({
        url: `pedido/` + this.$route.params.id,
        method: 'GET'
        })
        .then(response=>{

            this.pedido = response.data;

            this.data_pagamento_edit = this.pedido.data_pagamento;

            this.comprovantes = [];
            if(this.pedido.comprovantes.length > 0){
                for( var k in this.pedido.comprovantes) {
                    this.comprovantes.push({
                        database: this.pedido.comprovantes[k],
                        file: null
                    })
                }
            }

            if(this.pedido.data_entrega != null){
              let data_entrega = this.pedido.data_entrega.split('-')
              this.pedido.data_entrega = `${data_entrega[2]}/${data_entrega[1]}/${data_entrega[0]}`
            }

            if(this.pedido.data_pagamento != null){
              let data_pagamento = this.pedido.data_pagamento.split('-')
              this.pedido.data_pagamento = `${data_pagamento[2]}/${data_pagamento[1]}/${data_pagamento[0]}`
            }

            this.getTransportadoras()

            if(callback != null && callback instanceof Function){
              if(this.pedido.nota_fiscal != null && this.pedido.nota_fiscal.status != 1){
                callback.call(true)
              }
            }

        }).catch(error=>{
            this.has_error = true
        })
      },
      getTransportadoras() {
        this.$http({
          url: 'pedido/transportadoras',
          method: 'GET'
        }).then(response=>{

            this.transportadoras = response.data;
            this.transportadora = this.pedido.entrega_id

            if(callback != null && callback instanceof Function){
              if(this.pedido.nota_fiscal != null && this.pedido.nota_fiscal.status != 1){
                callback.call(true)
              }
            }

        }).catch(error=>{
            this.has_error = true
        })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      hideModal() {
          $('#modal-form').modal('hide')
        },
      hideModalEnvio() {
        $('#modal-form-envio').modal('hide')
      },
      hideModalEntrega() {
          $('#modal-form-entrega').modal('hide')
        },
      postCompravante() {
        var app = this
        let formData = new FormData()
        let verify = 0;
        this.comprovantes.forEach((item, index) => {
            if(item.file != null){
                verify += 1;
                formData.append('comprovantes[' + index + ']', item.file);
            }
        })

        if(verify < 1){
        this.hideModal();
            this.flashMessage.success({
                message: 'Comprovantes salvos com sucesso!',
                time: 5000
            });
        }else{
            let config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            this.$http({
                url: `pedido_comprovante/` + app.pedido.id,
                method: 'post',
                data: formData,
                config: config,
            }).then(response=>{
                this.hideModal();
                this.imagem = null;
                this.url_imagem = null;
                this.getPedido();

                this.flashMessage.success({
                    message: 'Comprovantes salvos com sucesso!',
                    time: 5000
                });

            }, error => {
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao enviar comprovante!',
                            time: 5000
                        });
                        break;
                }
            })
        }

        },
        postCompravanteEnvio() {
        var app = this
        app.loading_comprovante = true;
        let formData = new FormData()
        formData.append('comprovante',  app.imagem)

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `pedido_comprovante_envio/` + app.pedido.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{
                this.url_imagem = null;
                this.imagem = null;
                app.loading_comprovante = false;
                this.getPedido();
                this.hideModalEnvio();

                this.flashMessage.success({
                    message: 'Comprovante enviado com sucesso!',
                    time: 5000
                });

            }, error => {
                app.loading_comprovante = false;
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao enviar comprovante!',
                            time: 5000
                        });
                        break;
                }
            })
        },
      postCompravanteEntrega() {
        var app = this
        app.loading_comprovante = true;
        let formData = new FormData()
        formData.append('comprovante',  app.imagem)

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `pedido_comprovante_entrega/` + app.pedido.id,
          method: 'post',
          data: formData,
          config: config,

            })
            .then(response=>{
                this.url_imagem = null;
                this.imagem = null;
                app.loading_comprovante = false;
                this.getPedido();
                this.hideModalEntrega();

                this.flashMessage.success({
                    message: 'Comprovante enviado com sucesso!',
                    time: 5000
                });

            }, error => {
                app.loading_comprovante = false;
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao enviar comprovante!',
                            time: 5000
                        });
                        break;
                }
            })
        },
      onImagemChange(e) {
        const file = e.target.files[0];
        this.imagem = file;
        if(file.type == 'application/pdf')
        {
          this.url_pdf = true;
          this.url_imagem = null;
        }
        else
        {
          this.url_pdf = false;
          this.url_imagem = URL.createObjectURL(file);
        }

      },
    }
}
</script>
