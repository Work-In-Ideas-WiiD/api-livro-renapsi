import VueRouter from 'vue-router'
// Pages
import Home from './pages/Home'
import Register from './pages/Register'
import Login from './pages/Login'
import ForgotPassaword from './pages/ForgotPassaword'
import ResetPassaword from './pages/ResetPassaword'
import Dashboard from './pages/user/Dashboard'
import AdminDashboard from './pages/admin/Dashboard'
import ListaAdmin from './pages/admin/user/ListaAdmin'
import ListaUser from './pages/admin/user/ListaUser'
import CriarUser from './pages/admin/user/CriarUser'
import EditarUser from './pages/admin/user/EditarUser'
import PerfilAdmin from './pages/admin/Perfil'
import CategoriaAdmin from './pages/admin/categoria/ListaCategoria'
import ModuloAdmin from './pages/admin/modulo/ListaModulo'
import LivroAdmin from './pages/admin/livro/ListaLivro'
import AtributoAdmin from './pages/admin/atributo/ListaAtributo'
import AtributoValorAdmin from './pages/admin/atributovalor/ListaAtributoValor'
import MarcaAdmin from './pages/admin/marca/ListaMarca'
import EntregaAdmin from './pages/admin/entrega/ListaEntrega'
import ProdutoAdmin from './pages/admin/produto/ListaProduto'
import CriarProduto from './pages/admin/produto/CriarProduto'
import EditarProduto from './pages/admin/produto/EditarProduto'
import PedidoAdmin from './pages/admin/pedido/ListaPedido'
import PedidoVer from './pages/admin/pedido/VerPedido'
import PedidoImprimir from './pages/admin/pedido/ImprimirPedido'
import PedidoCriar from './pages/admin/pedido/CriarPedido'
import PedidoEditar from './pages/admin/pedido/EditarPedido'
import PedidoEntrega from './pages/admin/pedido/PedidoEntrega'
import ListaPedidoRelatorio from './pages/admin/relatorio/ListaPedido'
import ListaMmrc from './pages/admin/relatorio/ListaMmrc'
import ListaCat from './pages/admin/relatorio/ListaCat'
import ListaVideo from './pages/admin/relatorio/ListaVideo'
import ListaEndereco from './pages/admin/user/ListaEndereco'
import ListaBanner from './pages/admin/banner/ListaBanner'


// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: AdminDashboard,
    meta: {
      auth: true
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      auth: false
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      auth: false
    }
  },
  {
    path: '/forgot_passaword',
    name: 'forgot.passaword',
    component: ForgotPassaword,
    meta: {
      auth: undefined
    }
  },
  {
    path: '/reset-password/:token',
    name: 'reset.password',
    component: ResetPassaword,
    meta: {
      auth: undefined
    }
  },
  // USER ROUTES
  {
    path: '/dashboard',
    name: 'dashboard',
    component: AdminDashboard,
    meta: {
      auth: true
    }
  },
  // ADMIN ROUTES
  {
    path: '/admin',
    name: 'admin.dashboard',
    component: AdminDashboard,
    meta: {
      auth: {roles: 1, redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/usuario',
    name: 'user.list',
    component: ListaUser,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/usuario_criar',
    name: 'user.criar',
    component: CriarUser,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/usuario/:id',
    name: 'user.edit',
    component: EditarUser,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/produto',
    name: 'produto.list',
    component: ProdutoAdmin,
    meta: {
      auth: {roles: [1,2,3,6], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/produto_criar',
    name: 'produto.criar',
    component: CriarProduto,
    meta: {
      auth: {roles: [1,6], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/produto/:id',
    name: 'produto.edit',
    component: EditarProduto,
    meta: {
      auth: {roles: [1,3,6], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/pedido',
    name: 'pedido.list',
    component: PedidoAdmin,
    meta: {
      auth: {roles: [1,2,3,4,5], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/pedido_criar',
    name: 'pedido.criar',
    component: PedidoCriar,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/pedido/:id',
    name: 'pedido.ver',
    component: PedidoVer,
    meta: {
      auth: {roles: [1,2,3,4,5], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/pedido_editar/:id',
    name: 'pedido.editar',
    component: PedidoEditar,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/pedido_imprimir/:id/:estoque?',
    name: 'pedido.imprimir.ver',
    component: PedidoImprimir,
    meta: {
      auth: {roles: [1,2,3], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/pedido_entrega',
    name: 'pedido.entrega.list',
    component: PedidoEntrega,
    meta: {
      auth: {roles: [1,3,4], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/administrador',
    name: 'user.admin',
    component: ListaAdmin,
    meta: {
      auth: {roles: 1, redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/perfil',
    name: 'perfil',
    component: PerfilAdmin,
  },
  {
    path: '/modulos',
    name: 'modulo.admin',
    component: ModuloAdmin,
    meta: {
      auth: {roles: [1], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/livros',
    name: 'livro.admin',
    component: LivroAdmin,
    meta: {
      auth: {roles: [1], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/categorias',
    name: 'categoria.admin',
    component: CategoriaAdmin,
    meta: {
      auth: {roles: [1,2,6], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/atributos',
    name: 'atributo.admin',
    component: AtributoAdmin,
    meta: {
      auth: {roles: [1,2,6], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/atributos_valor/:id',
    name: 'atributovalor.admin',
    component: AtributoValorAdmin,
    meta: {
      auth: {roles: [1,2,6], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/marca',
    name: 'marca.admin',
    component: MarcaAdmin,
    meta: {
      auth: {roles: [1,2,6], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/entrega',
    name: 'entrega.admin',
    component: EntregaAdmin,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/relatorio_pedido',
    name: 'relatorio.pedido',
    component: ListaPedidoRelatorio,
    meta: {
      auth: {roles: [1,5], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/relatorio_mmrc',
    name: 'relatorio.mmrc',
    component: ListaMmrc,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/relatorio_cat',
    name: 'relatorio.cat',
    component: ListaCat,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/relatorio_video',
    name: 'relatorio.video',
    component: ListaVideo,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/endereco/:id',
    name: 'endereco.cliente',
    component: ListaEndereco,
    meta: {
      auth: {roles: [1,2], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  },
  {
    path: '/banners',
    name: 'banner.banner',
    component: ListaBanner,
    meta: {
      auth: {roles: [1], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    }
  }
]
const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})
export default router
