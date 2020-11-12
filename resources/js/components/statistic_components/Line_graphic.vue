<script>
import {Line} from 'vue-chartjs'
export default {
    extends : Line,
    name : 'Line_graphic',
    props: {
        chartdata: {
            type: Object,
            default: null
        },
        options: {
            type: Object,
            default: null
        }
    },
    mounted() {
      this.renderChart(this.chartdata,this.options)
    },
}
</script>

<style scoped>

</style>
