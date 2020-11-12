<template>
    <div>
        <div class="meta-statistic" :class="toggleTextColor">
            Всего постов : {{postsCount}}
        </div>
        <div class="label" :class="toggleTextColor">
            Просмотры | Upvouted
        </div>
        <line_graphic v-if="loadedLineGraphic"
                      :chartdata="chartdataLineGraphic"
                      :options="options"/>
        <div class="label" :class="toggleTextColor">
            Самые просматриваемые посты
        </div>
        <column_graphic v-if="loadedColumnGraphic"
                        :chart-data="chartdataColumnGraphic"
                        :options="options"/>
    </div>
</template>

<script>
/*
todo: Что будет в графиках :
1.Количество просмотров моих постов за каждый месяц
2.Соотношение лайков,дизлайков в каждом месяце
3.самый просматриваемый пост в каждом месяце
4.Всего постов
 */

import Line_graphic from "./statistic_components/Line_graphic";
import Column_graphic from "./statistic_components/Column_graphic";
import {colorToggle} from "../mixins/colorToggle";

export default {
    name : 'Statistic',
    mixins : [colorToggle],
    components:{
      Line_graphic,
      Column_graphic
    },
    data(){
        return {
            loadedLineGraphic : false,
            loadedColumnGraphic : false,
            statisticArr : [],
            chartdataLineGraphic: {
                labels: [],
                datasets: [
                    {
                        label: 'Просмотры',
                        backgroundColor: '#f87979',
                        data: []
                    },
                    {
                        label: 'Upvouted',
                        backgroundColor: '#798af8',
                        data: []
                    }
                ]
            },
            chartdataColumnGraphic : {
                labels : [],
                datasets : []
            },
            postsCount : 0,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    computed:{

    },
    beforeMount() {
        axios.get('/api/statistic')
        .then(this.setStatisticResponse)
        .then(this.initLineGraphic)
        .then(this.initColumnGraphic)
        .catch(error => {
            console.log(error);
        });
    },
    methods : {
        setStatisticResponse(statisticResponseObj) {
            this.statisticArr = statisticResponseObj.data;
        },
        initLineGraphic() {
            let moment = require('moment'),
                nowInSeconds = new Date()/1000,
                day =  moment.unix(nowInSeconds).date(),
                normalizeTimestamp = moment.unix(nowInSeconds).subtract({'year':1,'day':day-1}).startOf('month').format('X'),
                months = {};

            for(let i = 0; i < 12;i++){
                let monthMomentObj = moment.unix(+normalizeTimestamp).add(i,'month'),
                    monthName = monthMomentObj.startOf('month').format('MMMM'),
                    startMonth = monthMomentObj.startOf('month').format('X'),
                    endMonth = monthMomentObj.endOf('month').format('X');

                months[monthName] = {
                    start : 0,
                    end : 0,
                };
                months[monthName].start = startMonth;
                months[monthName].end = endMonth;
            }

            this.chartdataLineGraphic.labels = Object.keys(months);
            this.chartdataColumnGraphic.labels = Object.keys(months);
            this.postsCount = this.statisticArr.length;

            let viewsArr = [],
                viewsArrForColumn = new Map();
            for (let  key in months) {
                viewsArr[key] = {
                    views : 0,
                    upvouted : 0
                };
                viewsArrForColumn.set(key,[]);
            }

            this.statisticArr.forEach(function (currentItem){
                for (let key in months) {
                    if(currentItem.date > months[key].start && currentItem.date < months[key].end){
                        viewsArrForColumn.get(key).push({'views':currentItem.views,'title':currentItem.title});
                        viewsArr[key].views += currentItem.views;
                        viewsArr[key].upvouted +=(currentItem.toTop - currentItem.toDown);
                    }
                }
            });

            for (let month in viewsArr) {
                this.chartdataLineGraphic.datasets[0].data.push(viewsArr[month].views);
                this.chartdataLineGraphic.datasets[1].data.push(viewsArr[month].upvouted);
            }

            this.loadedLineGraphic = true;
            return viewsArrForColumn;
        },

        initColumnGraphic(viewsArr){

            let mask = new Array(11).fill(0),
                iteration = 0;

            const chartData = this.chartdataColumnGraphic;

            viewsArr.forEach(function(itemMonth) {
                let views = 0;
                const column = {};
                    itemMonth.forEach(function(item) {
                        if(item.views >= views){
                            column.backgroundColor = '#7995f8';
                            column.label = item.title;
                            mask[iteration] = item.views;
                            column.data = mask.slice(0);
                            mask[iteration] = 0;
                            views = item.views;
                        }
                    });
                iteration++;
                chartData.datasets.push(column);
            });
            this.loadedColumnGraphic = true;
            this.statisticArr.length = 0;
        }
    }
}


</script>

<style scoped>
.label,
.meta-statistic{
    font-weight: bold;
}
.meta-statistic{
    font-size : 1.3rem
}

</style>
