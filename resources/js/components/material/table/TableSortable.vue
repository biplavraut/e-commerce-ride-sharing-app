<template>
  <div class="root">
    <div class="row" v-if="searchUrl">
      <div class="col-md-11">
        <input
          type="text"
          value=""
          :placeholder="searchPlaceHolder"
          v-model="keyword"
          class="form-control"
        />
      </div>
      <div class="col-md-1">
        <button
          class="btn btn-round btn-xs title-right-action"
          @click="reset"
          title="Refresh"
        >
          <i class="material-icons">refresh</i>
        </button>
      </div>
    </div>
    <app-sortable-list
      lockAxis="y"
      :useDragHandle="true"
      v-model="rows.data"
      v-on="listeners"
    >
      <div class="table-responsive" style="overflow: scroll !important">
        <table class="table table-hover table-striped" style="margin-bottom: 0">
          <thead>
            <tr>
              <th v-if="sortable">
                <span class="material-icons">open_with</span>
              </th>
              <th v-else>SN</th>
              <th v-for="(column, index) in columns" :key="index">
                {{ column }}
              </th>
              <th v-if="actions">Actions</th>
              <th v-if="multiDelete"></th>
            </tr>
          </thead>

          <tbody v-if="keyword.length === 0">
            <tr
              is="app-sortable-item"
              v-for="(row, index) in innerRows.data"
              :disabled="!sortable"
              :index="index"
              :key="index"
              :item="row"
            >
              <td width="50" v-if="sortable" v-handle>
                <span class="handle material-icons">drag_handle</span>
              </td>
              <td width="20" class="text-center" v-else>
                {{
                  innerRows.meta.from ? innerRows.meta.from + index : ++index
                }}
              </td>
              <slot :row="row"></slot>
              <td v-if="multiDelete">
                <div class="checkbox">
                  <label>
                    <input
                      type="checkbox"
                      title="Check to delete"
                      :value="row.id"
                      v-model="deletableIds"
                      multiple
                    />
                  </label>
                </div>
              </td>
            </tr>
            <tr v-if="innerRows.data.length === 0">
              <td colspan="3">No data available.</td>
            </tr>
          </tbody>

          <tbody v-if="filteredRows.data.length > 0 && keyword.length > 0">
            <tr
              is="app-sortable-item"
              v-for="(row, index) in filteredRows.data"
              :disabled="!sortable"
              :index="index"
              :key="index"
              :item="row"
            >
              <td width="50" v-if="sortable" v-handle>
                <span class="handle material-icons">drag_handle</span>
              </td>
              <td width="20" class="text-center" v-else>
                {{
                  filteredRows.meta.from
                    ? filteredRows.meta.from + index
                    : ++index
                }}
              </td>
              <slot :row="row"></slot>
              <td v-if="multiDelete">
                <div class="checkbox">
                  <label>
                    <input
                      type="checkbox"
                      title="Check to delete"
                      :value="row.id"
                      v-model="deletableIds"
                      multiple
                    />
                  </label>
                </div>
              </td>
            </tr>
            <tr v-if="filteredRows.data.length === 0">
              <td colspan="3">No data available.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <button
        class="btn btn-danger"
        title="Delete Selected"
        v-if="deletableIds.length > 0"
        @click.prevent="$emit('deleteMultiple', deletableIds)"
      >
        Delete
      </button>
    </app-sortable-list>

    <div class="text-center" v-if="paginate">
      <app-pagination
        :rows.sync="innerRows"
        v-if="keyword.length === 0"
      ></app-pagination>

      <app-pagination
        :rows.sync="filteredRows"
        v-if="keyword.length > 0 && filteredRows.links"
        :search="'name=' + keyword"
      ></app-pagination>
    </div>
  </div>
</template>

<script>
import { HandleDirective } from "vue-slicksort";

export default {
  name: "TableSortable",

  directives: { handle: HandleDirective },

  data() {
    return {
      orderHasChanged: false,
      innerRows: { data: [], links: {}, meta: {} },
      filteredRows: { data: [], links: {}, meta: {} },
      keyword: "",
      deletableIds: [],
      searchPlaceHolder: "Search (By Name, Mobile)",
    };
  },

  props: {
    columns: {
      type: Array,
      required: true,
    },
    rows: {
      required: true,
    },
    searchUrl: {
      required: false,
    },
    searchHolder: {
      required: false,
    },
    clearKeyword: {
      required: false,
      type: Boolean,
      default: false,
    },
    word: {
      required: false,
    },
    sortable: {
      type: Boolean,
      default: false,
    },
    paginate: {
      type: Boolean,
      default: true,
    },
    actions: {
      type: Boolean,
      default: true,
    },
    multiDelete: {
      default: false,
    },
  },

  methods: {
    searchRows() {
      if (this.searchUrl) {
        axios.get(this.searchUrl + this.keyword).then((response) => {
          try {
            if (response.data.data.length > 0) {
              this.filteredRows.data = response.data.data;
              this.filteredRows.meta = response.data.meta;
              this.filteredRows.links = response.data.links;
            } else {
              this.filteredRows.data = this.rows.data;
              this.filteredRows.meta = this.rows.meta;
              this.filteredRows.links = this.rows.links;
            }
          } catch (error) {}
        });
      }
    },
    reset() {
      this.keyword = "";
    },
  },

  computed: {
    listeners() {
      return {
        ...this.$listeners,
        "sort-end": ({ event, oldIndex, newIndex }) => {
          this.orderHasChanged = oldIndex !== newIndex;
        },
        input: (payload) =>
          this.orderHasChanged ? this.$emit("orderHasChanged", payload) : "",
      };
    },
  },

  mounted() {
    this.innerRows = this.rows;

    if (this.word) {
      setTimeout(() => {
        this.keyword = this.word;
        this.searchRows();
      }, 2000);
    }

    if (this.searchHolder) {
      this.searchPlaceHolder = this.searchHolder;
    }
  },

  watch: {
    rows(newVal) {
      this.innerRows = newVal;
    },
    keyword: debounce(function (e) {
      // console.log(e);
      if (e.length > 0) {
        this.searchRows();
      } else {
        this.filteredRows.data = this.rows.data;
        this.filteredRows.meta = this.rows.meta;
        this.filteredRows.links = this.rows.links;
      }
    }, 1000),

    // keyword: function (val) {
    //   if (val.length > 0) {
    //     this.searchRows();
    //   } else {
    //     this.filteredRows.data = this.rows.data;
    //     this.filteredRows.meta = this.rows.meta;
    //     this.filteredRows.links = this.rows.links;
    //   }
    // },
    "rows.data": function (val) {
      this.keyword = "";
      // this.filteredRows.data = this.rows.data;
    },
    clearKeyword: function (val) {
      if (val === true) {
        this.keyword = "";
      }
    },
  },
};
</script>

<style scoped>
.root {
  position: relative;
  margin-bottom: 20px;
}

.handle {
  opacity: 0.25;
  cursor: row-resize;
  /*line-height : 50px;*/
}

.list {
  min-height: auto;
}

.list-title {
  padding: 10px 15px;
  margin: 0;
  font-weight: 400;
  /*background-color : #DDDDDD;*/
  color: #666666;
}
</style>
