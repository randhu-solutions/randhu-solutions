<template>
  <div id="pageDashboard">
    <v-container grid-list-xl fluid>
      <v-row>
        <v-col cols="12" lg="8">
          <v-widget title="Ventas mensuales" content-bg="white">
            <v-btn slot="widget-header-action" icon>
              <v-icon class="text--secondary">refresh</v-icon>
            </v-btn>
            <div slot="widget-content">
              <highcharts
                :options="{
                  title: {
                    text: ''
                  },
                  rangeSelector: {
                    selected: 1
                  },
                  legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                  },
                  xAxis: {
                    type: 'datetime',
                    tickInterval: 1000 * 3600 * 24 * 30 // 1 month
                  },
                  yAxis: {
                    title: {
                      text: 'Ventas'
                    }
                  },
                  series: [
                    {
                      name: 'Ventas por mes',
                      type: 'column',
                      data: [
                        { x: Date.UTC(2018, 0, 1), y: 15 },
                        { x: Date.UTC(2018, 1, 1), y: 12 },
                        { x: Date.UTC(2018, 2, 1), y: 3 },
                        { x: Date.UTC(2018, 3, 1), y: 46 },
                        { x: Date.UTC(2018, 4, 1), y: 25 },
                        { x: Date.UTC(2018, 5, 1), y: 16 },
                        { x: Date.UTC(2018, 6, 1), y: 17 },
                        { x: Date.UTC(2018, 7, 1), y: 8 },
                        { x: Date.UTC(2018, 8, 1), y: 9 },
                        { x: Date.UTC(2018, 9, 1), y: 10 },
                        { x: Date.UTC(2018, 10, 1), y: 11 },
                        { x: Date.UTC(2018, 11, 1), y: 12 },
                        { x: Date.UTC(2019, 0, 1), y: 6 },
                        { x: Date.UTC(2019, 1, 1), y: 2 },
                        { x: Date.UTC(2019, 2, 1), y: 11 },
                        { x: Date.UTC(2019, 3, 1), y: 11 },
                        { x: Date.UTC(2019, 4, 1), y: 5 }
                      ]
                    }
                  ]
                }"
              ></highcharts>
            </div>
          </v-widget>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import VWidget from "@/components/VWidget";
import Material from "vuetify/es5/util/colors";
import { Chart } from "highcharts-vue";

export default {
  components: {
    VWidget,
    highcharts: Chart
  },
  data: () => ({
    color: Material,
    selectedTab: "tab-1"
  }),
  computed: {}
};
</script>
