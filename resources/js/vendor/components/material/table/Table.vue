<template>
  <div>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
        <tr>
          <th v-if="multiDelete"></th>
          <th>S.N.</th>
          <th v-for="(column, index) in columns"
              :key="index">{{ column }}
          </th>
          <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        <tr v-for="(row, index) in rows.data"
            :key="index">
          <td v-if="multiDelete">
            <div class="checkbox">
              <label>
                <input type="checkbox"
                       :value="row.id"
                       v-model="deletableIds"
                       multiple>
              </label>
            </div>
          </td>
          <td width="20">{{ rows.meta.from + index }}</td>
          <slot :row="row"></slot>
        </tr>
        </tbody>
      </table>
    </div>

    <button class="btn btn-danger"
            v-if="deletableIds.length>0"
            @click.prevent="$emit('deleteMultiple', deletableIds)">Delete
    </button>
  </div>
</template>

<script>
  export default {
    name: "Table",

    props: {
      columns: {
        type: Array,
        required: true
      },
      rows: {
        required: true
      },
      multiDelete: {
        default: false
      }
    },

    data() {
      return {
        paginationRows: {},
        deletableIds: []
      };
    },
  };
</script>

<style scoped>
</style>