<template>
  <app-card title="All <b>Testimonials</b>"
            body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="testimonial.create">Add New</app-btn-link>
    </template>

    <app-table-sortable :columns="columns"
                        :rows="rows">

      <template slot-scope="{ row }">
        <td width="100">
          <img :src="row.image50"
               style="width:50px;height:50px;border-radius:50%;">
        </td>
        <td>{{ row.name }}</td>
        <td>{{ row.message }}</td>
        <td width="100">
          <app-actions @deleteItem="deleteModel"
                       :actions="{edit:{name: 'testimonial.edit', params: {id: row.id}}, delete:row.id}"></app-actions>
        </td>
      </template>

    </app-table-sortable>
  </app-card>
</template>

<script>
  import Testimonial from "@utils/models/Testimonial";
  import {index, destroy} from "@utils/mixins/Crud";

  export default {
    name: "TestimonialIndex",

    mixins: [index, destroy],

    data() {
      return {
        columns: ["Image", "Name", "Message"],
        rows: {data: [], links: {}, meta: {}},
        model: new Testimonial()
      };
    },

    mounted() {
      this.getModels();
    }
  };
</script>

<style scoped>
  .card-title {
    padding     : 10px 15px;
    margin      : 0;
    font-weight : 400;
    /*background-color : #337AB7;*/
    color       : #666666;
  }
</style>