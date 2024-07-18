<template>
  <ul class="pagination" v-if="totalPages > 1">
    <li class="disabled" v-if="items.links.prev == null">
      <span>&laquo;</span>
    </li>
    <li v-else>
      <a href="#" rel="prev" @click="goToUrl(items.links.prev, $event)"
        >&laquo;</a
      >
    </li>

    <li
      :class="items.meta.current_page == number ? 'active' : ''"
      v-for="(number, index) in totalPages"
      :key="index"
    >
      <span v-if="items.meta.current_page == number">{{ number }}</span>
      <a
        href="#"
        v-else
        @click="goToUrl(items.meta.path + '?page=' + number, $event)"
        >{{ number }}</a
      >
    </li>

    <li v-if="items.links.next != null">
      <a rel="next" href="#" @click="goToUrl(items.links.next, $event)"
        >&raquo;</a
      >
    </li>
    <li class="disabled" v-else>
      <span>&raquo;</span>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    rows: {
      required: true,
    },
    search: {
      default() {
        return {};
      },
    },
  },

  data() {
    return {
      items: [],
      searchParams: {},
    };
  },

  methods: {
    goToUrl(url, event) {
      event.preventDefault();
      url = url + this.searchQuery;
      this.getData(url);
    },

    getData(url) {
      axios
        .get(url)
        .then((response) => {
          this.$emit("update:rows", response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },

  computed: {
    totalPages() {
      if (this.items.length === 0) return 1;

      return Math.ceil(this.items.meta.total / this.items.meta.per_page);
    },

    searchQuery() {
      let params = [];

      for (let key in this.searchParams) {
        params.push(key + "=" + this.searchParams[key]);
      }

      return params.join("&");
    },
  },

  mounted() {
    this.items = this.rows;
    this.searchParams = this.search;
  },

  watch: {
    rows(value) {
      this.items = value;
    },
  },
};
</script>
