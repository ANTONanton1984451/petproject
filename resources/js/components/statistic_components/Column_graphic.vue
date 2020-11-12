<script>
import {Bar} from 'vue-chartjs'
export default {
  extends : Bar,
  name: "Column_graphic",
  props : {
      chartData : {
          type : Object,
          default : null
      },
      options : {
          type : Object,
          default: null
      }
  },
  mounted() {
    this.renderChart(this.chartData,this.options)
  }
}
</script>

<style scoped>

</style>
