<template>
    <div class="container-fluid mt--6">
        <div class="row">
        <div class="col-xl-6">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Escala mMRC</h6>
                  <h5 class="h3 mb-0">Total Registros</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Chart -->
              <div class="chart">
                <canvas id="chart-bars-registro" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Escala mMRC</h6>
                  <h5 class="h3 mb-0">Total Mês</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Chart -->
              <div class="chart">
                <canvas id="chart-bars" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Registros Escalas mMRC</h3>
                    </div>
                    <div class="col-4 text-right">
                        <a @click="exportRelatorio" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-success">Exportar</a>
                    </div>
                </div>
            </div>
            
            <!-- Light table -->
            <search v-on:search="searchMmrc" ></search>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="budget">Usuário</th>
                    <th scope="col" class="sort" data-sort="status">Faltar de Ar</th>
                    <th scope="col" class="sort" data-sort="status" >Subindo Rampa</th>
                    <th scope="col" class="sort" data-sort="status" >Andando Devagar</th>
                    <th scope="col" class="sort" data-sort="status" >Andando 100m</th>
                    <th scope="col" class="sort" data-sort="status" >Faltar de Ar Vestindo</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="escala in escalas.data" v-bind:key="escala.id" style="margin-bottom: 5px;">
                        <td>{{ escala.user.name }}</td>
                        <td v-if="escala.sofre_falta_ar" >Sim</td>
                        <td v-else >Não</td>
                        <td v-if="escala.andando_subindo_rampa" >Sim</td>
                        <td v-else >Não</td>
                        <td v-if="escala.andando_devagar">Sim</td>
                        <td v-else >Não</td>
                        <td v-if="escala.andando_menos_100m" >Sim</td>
                        <td v-else >Não</td>
                        <td v-if="escala.sofre_falta_ar_vestindo" >Sim</td>
                        <td v-else >Não</td>
                        <td>{{ getHumanDate(escala.created_at) }}</td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination :data="escalas" @pagination-change-page="getEscalas"></pagination>
            </div>
          </div>
        </div>
      </div>

    </div>
</template>
<script>
import moment from 'moment/moment';
import search from '../search/SearchMmrc.vue';
export default {
    data() {
      return {
        has_error: false,
        escalas: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
        searchEscala: '',
        escalaMmrc: [],
        escalaMesMmrc: []
      }
    },
    mounted() {
      this.getEscalaMmrcChart();
      this.getEscalaMmrcMesChart();
      this.salesChart();
      this.getEscalas();
    },
    methods:{
        exportRelatorio(){
          this.$http({
            url: `export/escala_mmrc`,
            method: 'GET'
            })
            .then(response=>{ 
                window.open('/storage/relatorio/escala_mmrc.xlsx')
            }, error=>{
                this.has_error = true
            })
        },
        getEscalaMmrcChart() {
            this.$http({
            url: `dashboard/escala_mmrc`,
            method: 'GET',
            })
            .then(response=>{ 
                this.escalaMmrc = response.data;
                this.barsChartRegistro(this.escalaMmrc);
            }).catch(error=>{
                this.has_error = true
            })
        },
        getEscalaMmrcMesChart() {
            this.$http({
            url: `dashboard/escala_mes_mmrc`,
            method: 'GET',
            })
            .then(response=>{ 
                this.escalaMesMmrc = response.data;
                this.barsChart(this.escalaMesMmrc);
            }).catch(error=>{
                this.has_error = true
            })
        },
        searchMmrc(payload){
            this.$http({
                url: 'relatorio/mmrc',
                method: 'GET',
                params: {
                like: payload.dados.nome,
                order: payload.dados.ordem,
                mostrar: payload.dados.mostrar,
                escala: payload.dados.escala,
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.searchEscala = payload.dados.escala;
                this.escalas = response.data;

            }, error=>{
                this.has_error = true
            })
        },
      getEscalas(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }

        this.$http({
          url: `relatorio/mmrc?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar,
            escala: this.searchEscala,
          },
        })
          .then(response => {
              this.escalas = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      barsChart(escalaMesMmrc){
        var BarsChart = (function() {

        //
        // Variables
        //

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
                  labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                  datasets: [{
                    label: 'Quantidade',
                    data: escalaMesMmrc
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
      barsChartRegistro(escalaMmrc){
        var BarsChart = (function() {

        //
        // Variables
        //

        var $chart = $('#chart-bars-registro');


        //
        // Methods
        //

        // Init chart
        function initChart($chart) {

            // Create chart
              var ordersChart = new Chart($chart, {
                type: 'bar',
                data: {
                  labels: ["Só sofre de falta", "Falta de ar subindo", "Anda mais devagar", "Menos de 100m ", "Sente tanta falta de ar"],
                  datasets: [{
                    label: 'Registro',
                    data: escalaMmrc
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
      salesChart(){

        var Charts = (function() {

          // Variable

          var $toggle = $('[data-toggle="chart"]');
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
            parseOptions($chart, options);

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
          $toggle.on({
            'change': function() {
              var $this = $(this);

              if ($this.is('[data-add]')) {
                toggleOptions($this);
              }
            },
            'click': function() {
              var $this = $(this);

              if ($this.is('[data-update]')) {
                updateOptions($this);
              }
            }
          });


          // Return

          return {
            colors: colors,
            fonts: fonts,
            mode: mode
          };

        })();
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
    },
    components: {
      search
    }
}
</script>