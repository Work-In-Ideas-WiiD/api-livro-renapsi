<template>
    <!-- <div class="container">
        <div class="card card-default">
            <div class="card-header">Admin Dashboard</div>
            <div class="card-body">
                Bienvenue sur votre dashboard administrateur
            </div>
        </div>
    </div> -->
<div class="main-content corpoDash" id="panel">
  <TopNav></TopNav>
    <div class="header bg-primary pb-6 headerDash">
      <div class="container-fluid">
        <div class="header-body">
          <!-- <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Inicio</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Painel</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Inicio</li>
                </ol>
              </nav>
            </div>
          </div> -->
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-gray d-inline-block mb-0">Dashboard</h6>
            </div>
          </div> 
          <!-- Card stats -->
          <div class="row" v-if="$auth.check(1)">
            <div class="col-xl-4 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body corpoCardDash">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0 textoStats">Total Alunos</h5>
                      <span class="h2 font-weight-bold mb-0">{{this.total['alunos']}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow iconeStats">
                        <i class="ni ni-circle-08"></i>
                      </div>
                    </div>
                  </div>
                  <!-- <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Desde o último mês</span>
                  </p> -->
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body corpoCardDash">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0 textoStats">Total Módulos</h5>
                      <span class="h2 font-weight-bold mb-0">{{this.total['modulos']}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow iconeStats">
                        <i class="ni ni-folder-17 "></i>
                      </div>
                    </div>
                  </div>
                  <!-- <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Desde o último mês</span>
                  </p> -->
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body corpoCardDash">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0 textoStats">Total Livros</h5>
                      <span class="h2 font-weight-bold mb-0">{{this.total['livros']}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow iconeStats">
                        <i class="ni ni-books "></i>
                      </div>
                    </div>
                  </div>
                  <!-- <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Desde o último mês</span>
                  </p> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid mt--6" v-if="!$auth.check(1)">
        <div class="row">
            <div class="col-12 card text-center pt-5 pb-5 d-block">
                <img src="assets/img/brand/Kaoa-preta.png" class="d-inline-block" width="400px"><br>
                <h2 class="mt-5 display-3">Bem vindo!</h2>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6" v-if="$auth.check(1)">
        <!-- <div class="row mb-2">
            <div class="col text-right">
              <date-range-picker
                ref="picker"
                :opens="'left'"
                :ranges="false"
                :locale-data="localeData"
                :singleDatePicker="false"
                :show-week-numbers="true"
                :control-container-class="''"
                :timePicker="true"
                :showDropdowns="true"
                :autoApply="false"
                v-model="dateRange"
                @update="onApply">
                  <template v-slot:input="picker" style="min-width: 350px;" class="teste">
                    <button @click="clearFilter" class="btn btn-secondary btn-sm">Filtro: {{ picker.startDate ? picker.startDate.toLocaleDateString('pt-BR') : '' }} - {{ picker.endDate ? picker.endDate.toLocaleDateString('pt-BR') : '' }}</button>
                  </template>
              </date-range-picker>

            </div>
        </div> -->
      <!-- <div class="row">
        <div class="col-12 col-xl-6">
          <div class="card bg-default">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-light text-uppercase ls-1 mb-1">VISÃO GERAL</h6>
                  <h5 class="h3 text-white mb-0" style="color: #000 !important">Novos pedidos</h5>
                </div>
                <div class="col">
                  <ul class="nav nav-pills justify-content-end">
                    <li class="nav-item mr-2 mr-md-0 toogle-sales-chart-mes" data-toggle="chart-sales" data-target="#chart-sales-dark">
                      <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                        <span class="d-none d-md-block">Mês</span>
                        <span class="d-md-none">M</span>
                      </a>
                    </li>
                    <li class="nav-item toogle-sales-chart-semana" data-toggle="chart-sales" data-target="#chart-sales-dark">
                      <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                        <span class="d-none d-md-block">Semana</span>
                        <span class="d-md-none">S</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="chart" id="chart-sales">
                <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-6">
          <div class="card bg-default">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-light text-uppercase ls-1 mb-1">VISÃO GERAL</h6>
                  <h5 class="h3 text-white mb-0" style="color: #000 !important">Faturamento</h5>
                </div>
                <div class="col">
                  <ul class="nav nav-pills justify-content-end">
                    <li class="nav-item mr-2 mr-md-0 toogle-faturamento-chart-mes" data-toggle="chart-faturamento" data-target="#chart-faturamento-dark">
                      <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                        <span class="d-none d-md-block">Mês</span>
                        <span class="d-md-none">M</span>
                      </a>
                    </li>
                    <li class="nav-item toogle-faturamento-chart-semana" data-toggle="chart-faturamento" data-target="#chart-faturamento-dark">
                      <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                        <span class="d-none d-md-block">Semana</span>
                        <span class="d-md-none">S</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="chart" id="chart-faturamento">
                <canvas id="chart-faturamento-dark" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Status Pedidos</h6>
                  <h5 class="h3 mb-0">Total Pedidos</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="chart" id="chart-bars-html">
                <canvas id="chart-bars" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-gray d-inline-block mb-0">Livros</h6>
        </div>
      </div> 
      <div class="row">
        <div class="col-xl-12">
          <div class="card cardTabela">
            <!-- <div class="card-header border-0">
              <div class="row align-items-center">
                 <div class="col">
                  <h3 class="mb-0">Ultimos Livros</h3>
                </div> 
                <div class="col text-right">
                   <a href="#!" class="btn btn-sm btn-primary">See all</a> 
                </div>
              </div>
            </div> -->
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush tabelaDash">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="status">Título</th>
                    <th scope="col" class="sort" data-sort="completion">Módulos</th>
                    <th scope="col" class="sort" data-sort="completion">Tags</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
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
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</template>
<script>
  import moment from 'moment/moment';
  import TopNav from '../../components/layout/TopNav.vue'
  import DateRangePicker from 'vue2-daterange-picker'
  import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'
  export default {
    data() {
      return {
        livros: {},
        totalRegistroChart: [],
        registros: [],
        total: [],
        dataFaturamentoChartMonth: '',
        dataFaturamentoChartWeek: '',
        dateRange: {},
        localeData: {
          format: 'mm/dd/yyyy',
          separator: ' - ',
          applyLabel: 'Aplicar',
          cancelLabel: 'Cancelar',
          weekLabel: 'S',
          customRangeLabel: 'Custom Range',
          daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
          monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
          firstDay: 0
        }
      }
    },
    mounted() {
      this.getTotal();
      this.getRegistroChart();
      this.getFaturamentoChart();
      this.getTotalRegistroChart();
      this.getPedidos();
      this.bgAlterar();
    },
    methods:{
      onApply(){
        this.getRegistroChart()
        this.getFaturamentoChart()
        this.getTotalRegistroChart()
      },
      clearFilter() {
        this.dateRange = {}
        this.getRegistroChart()
        this.getFaturamentoChart()
        this.getTotalRegistroChart()
      },
      formatPrice(value) {
        let val = (value/1).toFixed(2).replace('.', ',')
        return 'R$ ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      },
      bgAlterar(){
        $('#bg').removeClass('bg-default')
      },
      getTotal() {
        this.$http({
          url: `dashboard/total`,
          method: 'GET',
        })
          .then(response=>{
            this.total = response.data;
          }).catch(error=>{
            this.has_error = true
          })
      },
      getTotalRegistroChart() {

        this.$http({
          url: `dashboard/total_registro`,
          method: 'GET',
          params: {
            startDate: this.dateRange.startDate ? this.dateRange.startDate.toLocaleDateString('pt-BR') : null,
            endDate: this.dateRange.endDate ? this.dateRange.endDate.toLocaleDateString('pt-BR') : null,
          }
        })
          .then(response=>{
            this.totalRegistroChart = response.data.registros;
            this.barsChart(this.totalRegistroChart, response.data.labels);
          }).catch(error=>{
            this.has_error = true
          })
      },
      getRegistroChart() {
        this.$http({
          url: `dashboard/registros`,
          method: 'GET',
          params: {
            startDate: this.dateRange.startDate ? this.dateRange.startDate.toLocaleDateString('pt-BR') : null,
            endDate: this.dateRange.endDate ? this.dateRange.endDate.toLocaleDateString('pt-BR') : null,
          }
        })
          .then(response=>{
            this.registros = response.data.registros.mensal;

            $('.toogle-sales-chart-mes').data('update', {
                "data": {
                    "labels": response.data.label.mensal,
                    "datasets": [{"label": "Performance", "data": response.data.registros.mensal}]
                }
            })

            $('.toogle-sales-chart-semana').data('update', {
                "data": {
                    "labels": response.data.label.semanal,
                    "datasets": [{"label": "Performance", "data": response.data.registros.semanal}]
                }
            })

            if($('.toogle-sales-chart-semana').find('a').hasClass('active')){
                this.salesChart(response.data.registros.semanal, response.data.label.semanal);
            }else{
                this.salesChart(response.data.registros.mensal, response.data.label.mensal);
            }
          }).catch(error=>{
            this.has_error = true
          })
      },
      getFaturamentoChart() {
        this.$http({
          url: `dashboard/faturamento`,
          method: 'GET',
          params: {
            startDate: this.dateRange.startDate ? this.dateRange.startDate.toLocaleDateString('pt-BR') : null,
            endDate: this.dateRange.endDate ? this.dateRange.endDate.toLocaleDateString('pt-BR') : null,
          }
        })
          .then(response=>{
            this.registros = response.data.registros.mensal;

            $('.toogle-faturamento-chart-mes').data('update', {
                "data": {
                    "labels": response.data.label.mensal,
                    "datasets": [{"label": "Performance", "data": response.data.registros.mensal}]
                }
            })

            $('.toogle-faturamento-chart-semana').data('update', {
                "data": {
                    "labels": response.data.label.semanal,
                    "datasets": [{"label": "Performance", "data": response.data.registros.semanal}]
                }
            })

            if($('.toogle-faturamento-chart-semana').find('a').hasClass('active')){
                this.faturamentoChart(response.data.registros.semanal, response.data.label.semanal);
            }else{
                this.faturamentoChart(response.data.registros.mensal, response.data.label.mensal);
            }
          }).catch(error=>{
            this.has_error = true
          })
      },
      getPedidos() {
        this.$http({
          url: `conteudos/livro?per_page=5`,
          method: 'GET'
        })
          .then(response => {
              this.livros = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      barsChart(totalRegistroChart, labels){
        var BarsChart = (function() {

        //
        // Variables
        //

        $('#chart-bars-html').html(`<canvas id="chart-bars" class="chart-canvas"></canvas>`)
        var $chart = $('#chart-bars');


        //
        // Methods
        //

        // Init chart
        function initChart($chart) {

            // Create chart
              var ordersChart = new Chart($chart, {
                type: 'bar',
                data: {
                  labels: labels,
                  datasets: [{
                    label: 'Registro',
                    data: totalRegistroChart
                  }]
                }
              });

            // Save to jQuery object
            $chart.data('chart', ordersChart);
          }


          // Init chart
          if ($chart.length) {
            initChart($chart);
          }

          })();
      },
      salesChart(registros, label){

        var Charts = (function() {

          // Variable

          var $toggle = $('[data-toggle="chart-sales"]');
          var mode = 'light';//(themeMode) ? themeMode : 'light';
          var fonts = {
            base: 'Open Sans'
          }

          // Colors
          var colors = {
            gray: {
              100: '#f6f9fc',
              200: '#e9ecef',
              300: '#dee2e6',
              400: '#ced4da',
              500: '#adb5bd',
              600: '#8898aa',
              700: '#525f7f',
              800: '#32325d',
              900: '#212529'
            },
            theme: {
              'default': '#172b4d',
              'primary': '#5e72e4',
              'secondary': '#f4f5f7',
              'info': '#11cdef',
              'success': '#2dce89',
              'danger': '#f5365c',
              'warning': '#fb6340'
            },
            black: '#12263F',
            white: '#FFFFFF',
            transparent: 'transparent',
          };


          // Methods

          // Chart.js global options
          function chartOptions() {

            // Options
            var options = {
              defaults: {
                global: {
                  responsive: true,
                  maintainAspectRatio: false,
                  defaultColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                  defaultFontColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                  defaultFontFamily: fonts.base,
                  defaultFontSize: 13,
                  layout: {
                    padding: 0
                  },
                  legend: {
                    display: false,
                    position: 'bottom',
                    labels: {
                      usePointStyle: true,
                      padding: 16
                    }
                  },
                  elements: {
                    point: {
                      radius: 0,
                      backgroundColor: colors.theme['primary']
                    },
                    line: {
                      tension: .4,
                      borderWidth: 4,
                      borderColor: colors.theme['primary'],
                      backgroundColor: colors.transparent,
                      borderCapStyle: 'rounded'
                    },
                    rectangle: {
                      backgroundColor: colors.theme['warning']
                    },
                    arc: {
                      backgroundColor: colors.theme['primary'],
                      borderColor: (mode == 'dark') ? colors.gray[800] : colors.white,
                      borderWidth: 4
                    }
                  },
                  tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                  }
                },
                doughnut: {
                  cutoutPercentage: 83,
                  legendCallback: function(chart) {
                    var data = chart.data;
                    var content = '';

                    data.labels.forEach(function(label, index) {
                      var bgColor = data.datasets[0].backgroundColor[index];

                      content += '<span class="chart-legend-item">';
                      content += '<i class="chart-legend-indicator" style="background-color: ' + bgColor + '"></i>';
                      content += label;
                      content += '</span>';
                    });

                    return content;
                  }
                }
              }
            }

            // yAxes
            Chart.scaleService.updateScaleDefaults('linear', {
              gridLines: {
                borderDash: [2],
                borderDashOffset: [2],
                color: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
                drawBorder: false,
                drawTicks: false,
                drawOnChartArea: true,
                zeroLineWidth: 0,
                zeroLineColor: 'rgba(0,0,0,0)',
                zeroLineBorderDash: [2],
                zeroLineBorderDashOffset: [2]
              },
              ticks: {
                beginAtZero: true,
                padding: 10,
                callback: function(value) {
                  if (!(value % 10)) {
                    return value
                  }
                }
              }
            });

            // xAxes
            Chart.scaleService.updateScaleDefaults('category', {
              gridLines: {
                drawBorder: false,
                drawOnChartArea: false,
                drawTicks: false
              },
              ticks: {
                padding: 20
              },
              maxBarThickness: 10
            });

            return options;

          }

          // Parse global options
          function parseOptions(parent, options) {
            for (var item in options) {
              if (typeof options[item] !== 'object') {
                parent[item] = options[item];
              } else {
                parseOptions(parent[item], options[item]);
              }
            }
          }

          // Push options
          function pushOptions(parent, options) {
            for (var item in options) {
              if (Array.isArray(options[item])) {
                options[item].forEach(function(data) {
                  parent[item].push(data);
                });
              } else {
                pushOptions(parent[item], options[item]);
              }
            }
          }

          // Pop options
          function popOptions(parent, options) {
            for (var item in options) {
              if (Array.isArray(options[item])) {
                options[item].forEach(function(data) {
                  parent[item].pop();
                });
              } else {
                popOptions(parent[item], options[item]);
              }
            }
          }

          // Toggle options
          function toggleOptions(elem) {
            var options = elem.data('add');
            var $target = $(elem.data('target'));
            var $chart = $target.data('chart');

            if (elem.is(':checked')) {

              // Add options
              pushOptions($chart, options);

              // Update chart
              $chart.update();
            } else {

              // Remove options
              popOptions($chart, options);

              // Update chart
              $chart.update();
            }
          }

          // Update options
          function updateOptions(elem) {
            var options = elem.data('update');
            var $target = $(elem.data('target'));
            var $chart = $target.data('chart');

            // Parse options
            // parseOptions($chart, options);
            $chart.data.labels = options.data.labels
            $chart.data.datasets = options.data.datasets

            // Toggle ticks
            toggleTicks(elem, $chart);

            // Update chart
            $chart.update();
          }

          // Toggle ticks
          function toggleTicks(elem, $chart) {

            if (elem.data('prefix') !== undefined || elem.data('prefix') !== undefined) {
              var prefix = elem.data('prefix') ? elem.data('prefix') : '';
              var suffix = elem.data('suffix') ? elem.data('suffix') : '';

              // Update ticks
              $chart.options.scales.yAxes[0].ticks.callback = function(value) {
                if (!(value % 10)) {
                  return prefix + value + suffix;
                }
              }

              // Update tooltips
              $chart.options.tooltips.callbacks.label = function(item, data) {
                var label = data.datasets[item.datasetIndex].label || '';
                var yLabel = item.yLabel;
                var content = '';

                if (data.datasets.length > 1) {
                  content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                }

                content += '<span class="popover-body-value">' + prefix + yLabel + suffix + '</span>';
                return content;
              }

            }
          }


          // Events

          // Parse global options
          if (window.Chart) {
            parseOptions(Chart, chartOptions());
          }

          // Toggle options
          if(!$toggle.hasClass('received-event')){
            $toggle.addClass('received-event')
            $toggle.on({
                'change': function() {
                    var $this = $(this);

                    if ($this.is('[data-add]')) {
                        toggleOptions($this);
                    }
                },
                'click': function() {
                    var $this = $(this);
                    updateOptions($this);
                }
            });
          }


          // Return

          return {
            colors: colors,
            fonts: fonts,
            mode: mode
          };

        })();

        var SalesChart = (function() {

        // Variables
        $('#chart-sales').html(`<canvas id="chart-sales-dark" class="chart-canvas"></canvas>`)
        var $chart = $('#chart-sales-dark');


        // Methods

        function init($chart) {

          var salesChart = new Chart($chart, {
            type: 'line',
            options: {
              scales: {
                yAxes: [{
                  gridLines: {
                    lineWidth: 1,
                    color: Charts.colors.gray[900],
                    zeroLineColor: Charts.colors.gray[900]
                  },
                  ticks: {
                    callback: function(value) {
                      if (!(value % 10)) {
                        return value;
                      }
                    }
                  }
                }]
              },
              tooltips: {
                callbacks: {
                  label: function(item, data) {
                    var label = data.datasets[item.datasetIndex].label || '';
                    var yLabel = item.yLabel;
                    var content = '';

                    if (data.datasets.length > 1) {
                      content +=  label;
                    }

                    content +=  yLabel;
                    return content;
                  }
                }
              }
            },
            data: {
              labels: label,
              datasets: [{
                label: 'Performance',
                data: registros
              }]
            }
          });

          // Save to jQuery object

          $chart.data('chart', salesChart);

        };


        // Events

        if ($chart.length) {
            init($chart);
        }

      })();
      },
      faturamentoChart(registros, label){

        var Charts = (function() {

          // Variable

          var $toggle = $('[data-toggle="chart-faturamento"]');
          var mode = 'light';//(themeMode) ? themeMode : 'light';
          var fonts = {
            base: 'Open Sans'
          }

          // Colors
          var colors = {
            gray: {
              100: '#f6f9fc',
              200: '#e9ecef',
              300: '#dee2e6',
              400: '#ced4da',
              500: '#adb5bd',
              600: '#8898aa',
              700: '#525f7f',
              800: '#32325d',
              900: '#212529'
            },
            theme: {
              'default': '#172b4d',
              'primary': '#5e72e4',
              'secondary': '#f4f5f7',
              'info': '#11cdef',
              'success': '#2dce89',
              'danger': '#f5365c',
              'warning': '#fb6340'
            },
            black: '#12263F',
            white: '#FFFFFF',
            transparent: 'transparent',
          };


          // Methods

          // Chart.js global options
          function chartOptions() {

            // Options
            var options = {
              defaults: {
                global: {
                  responsive: true,
                  maintainAspectRatio: false,
                  defaultColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                  defaultFontColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                  defaultFontFamily: fonts.base,
                  defaultFontSize: 13,
                  layout: {
                    padding: 0
                  },
                  legend: {
                    display: false,
                    position: 'bottom',
                    labels: {
                      usePointStyle: true,
                      padding: 16
                    }
                  },
                  elements: {
                    point: {
                      radius: 0,
                      backgroundColor: colors.theme['primary']
                    },
                    line: {
                      tension: .4,
                      borderWidth: 4,
                      borderColor: colors.theme['primary'],
                      backgroundColor: colors.transparent,
                      borderCapStyle: 'rounded'
                    },
                    rectangle: {
                      backgroundColor: colors.theme['warning']
                    },
                    arc: {
                      backgroundColor: colors.theme['primary'],
                      borderColor: (mode == 'dark') ? colors.gray[800] : colors.white,
                      borderWidth: 4
                    }
                  },
                  tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                  }
                },
                doughnut: {
                  cutoutPercentage: 83,
                  legendCallback: function(chart) {
                    var data = chart.data;
                    var content = '';

                    data.labels.forEach(function(label, index) {
                      var bgColor = data.datasets[0].backgroundColor[index];

                      content += '<span class="chart-legend-item">';
                      content += '<i class="chart-legend-indicator" style="background-color: ' + bgColor + '"></i>';
                      content += label;
                      content += '</span>';
                    });

                    return content;
                  }
                }
              }
            }

            // yAxes
            Chart.scaleService.updateScaleDefaults('linear', {
              gridLines: {
                borderDash: [2],
                borderDashOffset: [2],
                color: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
                drawBorder: false,
                drawTicks: false,
                drawOnChartArea: true,
                zeroLineWidth: 0,
                zeroLineColor: 'rgba(0,0,0,0)',
                zeroLineBorderDash: [2],
                zeroLineBorderDashOffset: [2]
              },
              ticks: {
                beginAtZero: true,
                padding: 10,
                callback: function(value) {
                  if (!(value % 10)) {
                    return value
                  }
                }
              }
            });

            // xAxes
            Chart.scaleService.updateScaleDefaults('category', {
              gridLines: {
                drawBorder: false,
                drawOnChartArea: false,
                drawTicks: false
              },
              ticks: {
                padding: 20
              },
              maxBarThickness: 10
            });

            return options;

          }

          // Parse global options
          function parseOptions(parent, options) {
            for (var item in options) {
              if (typeof options[item] !== 'object') {
                parent[item] = options[item];
              } else {
                parseOptions(parent[item], options[item]);
              }
            }
          }

          // Push options
          function pushOptions(parent, options) {
            for (var item in options) {
              if (Array.isArray(options[item])) {
                options[item].forEach(function(data) {
                  parent[item].push(data);
                });
              } else {
                pushOptions(parent[item], options[item]);
              }
            }
          }

          // Pop options
          function popOptions(parent, options) {
            for (var item in options) {
              if (Array.isArray(options[item])) {
                options[item].forEach(function(data) {
                  parent[item].pop();
                });
              } else {
                popOptions(parent[item], options[item]);
              }
            }
          }

          // Toggle options
          function toggleOptions(elem) {
            var options = elem.data('add');
            var $target = $(elem.data('target'));
            var $chart = $target.data('chart');

            if (elem.is(':checked')) {

              // Add options
              pushOptions($chart, options);

              // Update chart
              $chart.update();
            } else {

              // Remove options
              popOptions($chart, options);

              // Update chart
              $chart.update();
            }
          }

          // Update options
          function updateOptions(elem) {
            var options = elem.data('update');
            var $target = $(elem.data('target'));
            var $chart = $target.data('chart');

            // Parse options
            // parseOptions($chart, options);
            $chart.data.labels = options.data.labels
            $chart.data.datasets = options.data.datasets

            // Toggle ticks
            toggleTicks(elem, $chart);

            // Update chart
            $chart.update();
          }

          // Toggle ticks
          function toggleTicks(elem, $chart) {

            if (elem.data('prefix') !== undefined || elem.data('prefix') !== undefined) {
              var prefix = elem.data('prefix') ? elem.data('prefix') : '';
              var suffix = elem.data('suffix') ? elem.data('suffix') : '';

              // Update ticks
              $chart.options.scales.yAxes[0].ticks.callback = function(value) {
                if (!(value % 10)) {
                  return prefix + value + suffix;
                }
              }

              // Update tooltips
              $chart.options.tooltips.callbacks.label = function(item, data) {
                var label = data.datasets[item.datasetIndex].label || '';
                var yLabel = item.yLabel;
                var content = '';

                if (data.datasets.length > 1) {
                  content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                }

                content += '<span class="popover-body-value">' + prefix + yLabel + suffix + '</span>';
                return content;
              }

            }
          }


          // Events

          // Parse global options
          if (window.Chart) {
            parseOptions(Chart, chartOptions());
          }

          // Toggle options
          if(!$toggle.hasClass('received-event')){
            $toggle.addClass('received-event')
            $toggle.on({
                'change': function() {
                    var $this = $(this);

                    if ($this.is('[data-add]')) {
                        toggleOptions($this);
                    }
                },
                'click': function() {
                    var $this = $(this);
                    updateOptions($this);
                }
            });
          }


          // Return

          return {
            colors: colors,
            fonts: fonts,
            mode: mode
          };

        })();

        var SalesChart = (function() {

        // Variables

        $('#chart-faturamento').html(`<canvas id="chart-faturamento-dark" class="chart-canvas"></canvas>`)
        var $chart = $('#chart-faturamento-dark');


        // Methods

        function init($chart) {

          var salesChart = new Chart($chart, {
            type: 'line',
            options: {
              scales: {
                yAxes: [{
                  gridLines: {
                    lineWidth: 1,
                    color: Charts.colors.gray[900],
                    zeroLineColor: Charts.colors.gray[900]
                  },
                  ticks: {
                    callback: function(value) {
                      return value.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                    }
                  }
                }]
              },
              tooltips: {
                callbacks: {
                  label: function(value) {
                    return value.yLabel.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                  }
                }
              }
            },
            data: {
              labels: label,
              datasets: [{
                label: 'Performance',
                data: registros
              }]
            }
          });

          // Save to jQuery object

          $chart.data('chart', salesChart);

        };


        // Events

        if ($chart.length) {
          init($chart);
        }

      })();
      }
    },
    components: {
      TopNav,
      DateRangePicker,
    }
  }
</script>
