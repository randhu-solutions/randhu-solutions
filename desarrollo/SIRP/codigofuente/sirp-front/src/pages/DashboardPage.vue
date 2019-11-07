<template>
  <div id="pageDashboard">
    <v-container grid-list-xl fluid>
      <v-layout row wrap>
        <v-flex lg7 sm12 xs12>
          <plain-table></plain-table>
        </v-flex>
        <v-flex lg5 sm12 xs12>
          <plain-table-order></plain-table-order>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
import PlainTable from "@/components/widgets/PlainTable";
import PlainTableOrder from "@/components/widgets/PlainTableOrder";
export default {
  components: {
    PlainTable,
    PlainTableOrder
  },
  data: () => ({})
  // computed: {
  //   activity () {
  //     return API.getActivity();
  //   },
  //   posts () {
  //     return API.getPost(3);
  //   },
  //   siteTrafficData () {
  //     return API.getMonthVisit;
  //   },
  //   locationData () {
  //     return API.getLocation;
  //   }
  // }
};
</script>
