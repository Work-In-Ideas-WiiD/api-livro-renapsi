<template>
    <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="close-menu-mobile sidenav-toggler d-xl-none" data-action="sidenav-pin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
            </div>
        </div>
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header  align-items-center">
        <a class="navbar-brand" href="javascript:void(0)">
          <img src="/assets/img/brand/Grupo_Dema.png" class="navbar-brand-img" alt="...">
        </a>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
               <router-link class="nav-link active" to="/">
                <i class="ni ni-tv-2 text-primary"></i>
                <span class="nav-link-text">Dashboard</span>
              </router-link>
            </li>
            <li v-if="$auth.check(1)" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/administrador">
                <i class="ni ni-single-02 text-primary"></i>
                <span class="nav-link-text">Administradores</span>
              </router-link>
            </li>
            <li v-if="$auth.check([1,2])" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/modulos">
                <i class="ni ni-folder-17 text-info"></i>
                <span class="nav-link-text">Módulo</span>
              </router-link>
            </li>
            <!-- <li v-if="$auth.check([1,2,3,6])" class="nav-item dropdown">
                <a class="nav-link nav-link-icon" href="#" id="navbar-default_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <i class="ni ni-basket text-orange"></i>
                    <span class="nav-link-text">Produtos</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
                   <li v-for="(route, key) in routes.produto" v-bind:key="route.path" class="sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
                      <router-link v-if="$auth.check(route.roles)" class="dropdown-item" :to="{ name : route.path }" :key="key"> <i v-bind:class="'ni ' + route.icon +' text-primary'"></i> {{route.name}}</router-link>
                   </li>
                </div>
            </li> -->
            <li v-if="$auth.check([1,2])" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/usuario">
                <i class="ni ni-circle-08"></i>
                <span class="nav-link-text">Alunos</span>
              </router-link>
            </li>
            <li v-if="$auth.check([1,2])" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/livros">
                <i class="ni ni-books text-success"></i>
                <span class="nav-link-text">Livros</span>
              </router-link>
            </li>
            <!-- <li v-if="$auth.check([1,3,4])" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/pedido_entrega">
                <i class="ni ni-cart text-success"></i>
                <span class="nav-link-text">Pedidos Para Entrega</span>
              </router-link>
            </li>
            <li v-if="$auth.check([1,5])" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/relatorio_pedido">
                <i class="ni ni-bullet-list-67 text-success"></i>
                <span class="nav-link-text">Relatório Venda</span>
              </router-link>
            </li>
             <li v-if="$auth.check([1,2])" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/entrega">
                <i class="ni ni-delivery-fast text-primary"></i>
                <span class="nav-link-text">Entregas</span>
              </router-link>
            </li>
            <li v-if="$auth.check(1)" class="nav-item sidenav-toggler p-0" data-action="sidenav-pin" data-target="#sidenav-main">
              <router-link class="nav-link" to="/banners">
                <i class="ni ni-folder-17 text-info"></i>
                <span class="nav-link-text">Banners</span>
              </router-link>
            </li> -->
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>
<style>
    .close-menu-mobile {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0 !important;
    }
</style>
<script>
  export default {
    data() {
      return {
        routes: {
          // UNLOGGED
          unlogged: [
            {
              name: 'Inscription',
              path: 'register'
            },
            {
              name: 'Connexion',
              path: 'login'
            }
          ],
          // LOGGED USER
          produto: [
            {
              name: 'Produtos',
              path: 'produto.list',
              icon: 'ni-bag-17',
              roles: [1,2,3,6]
            },
            {
              name: 'Categorias',
              path: 'categoria.admin',
              icon: 'ni-folder-17',
              roles: [1,2,6]
            },
            {
              name: 'Atributos',
              path: 'atributo.admin',
              icon: 'ni-collection',
              roles: [1,2,6]
            },
            {
              name: 'Marcas',
              path: 'marca.admin',
              icon: 'ni-tag',
              roles: [1,2,6]
            },
          ],
          // LOGGED ADMIN
          admin: [
            {
              name: 'Dashboard',
              path: 'admin.dashboard'
            }
          ]
        }
      }
    },
    mounted() {
      //
    },
    methods: {
      error () {
        // Pass the element id here
        this.flashMessage.error({
            message: 'Error usuário não sem permissão!',
            time: 5000
        });
      },
    }
  }
</script>
