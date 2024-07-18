<template>
  <canvas :id="id"
          :width="width"
          :height="height"></canvas>
</template>

<script>
  export default {
    name: "Chart",

    props: {
      id      : {type: String, default: 'bar-chart-' + parseInt(Math.random() * 10000)},
      type    : {type: String, default: 'bar'},
      width   : {type: String, default: "400"},
      height  : {type: String, default: "200"},
      xLabels : {type: Array, required: true},
      datasets: {required: true}
    },

    mounted() {
      let ctx = document.getElementById(this.id).getContext('2d');
      let myChart = new Chart(ctx, {
        type   : this.type,
        data   : {
          labels  : this.xLabels,
          datasets: this.datasets
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      });
    }
  }
</script>

<style scoped>

</style>