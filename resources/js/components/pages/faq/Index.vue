<template>
  <app-card title="All <b>FAQs</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="faq.create">Add New</app-btn-link>
    </template>
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/faq/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.faqTitle }}</td>
        <td>{{ row.faqDescription }}</td>
        <td>{{ row.order }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'faq.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Faq from "@utils/models/Faq";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "FaqIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["FAQ Title", "FAQ Description", "Display Order"],
      rows: { data: [], links: {}, meta: {} },
      model: new Faq(),
    };
  },

  methods: {},

  mounted() {
    this.getModels();
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>
<style scoped>
</style>