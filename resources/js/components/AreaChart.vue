<template>
    <apexchart type="area" :options="chartOptions" :series="chartSeries" height="90"></apexchart>
</template>

<script>
  import VueApexCharts from 'vue3-apexcharts';

  export default {
    components: {
      apexchart: VueApexCharts,
    },
    props: {
      series: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        chartOptions: {
          chart: {
            type: 'area',
            height: 90,
            zoom: {
              enabled: false,
            },
            toolbar: {
              show: false,
            },
          },
          stroke: {
            curve: 'smooth',
            width: 2,
          },
          fill: {
            type: 'gradient',
            gradient: {
              shadeIntensity: 1,
              opacityFrom: 0.4,
              opacityTo: 0.6,
            },
          },
          yaxis: {
            show: false,
          },
          xaxis: {
            show: false,
          },
          grid: {
            show: false,
          },
          tooltip: {
            theme: 'light',
          },
        },
      };
    },
    computed: {
      chartSeries() {
        return [{ name: 'Trend', data: this.series }];
      },
    },
  };
</script>

<style scoped>
</style>
