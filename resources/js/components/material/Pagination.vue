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
    <li v-if="items.links.first != null" :class="items.meta.current_page == 1 ? 'active' : ''">
      <a rel="first" href="#" @click="goToUrl(items.links.first, $event)"
        >1</a
      >
    </li>
    <li v-if="items.meta.current_page > 4">
      <span>...</span>      
    </li>
    <li
      :class="items.meta.current_page == number ? 'active' : ''"
      v-for="(number, index) in totalPages"
      :key="index"
      v-show="index != 0 && index != totalPages -1"
    >
      <span v-if="items.meta.current_page == number">{{ number }}</span>
      <a
        href="#"
        v-else-if="items.meta.current_page > index - 3 && items.meta.current_page < index + 4"
        @click="goToUrl((items.meta.newPath ? items.meta.newPath+'&' :items.meta.path+'?') + 'page=' + number, $event)"
        >{{ number }}</a
      >
    </li>
    <li v-if="items.meta.current_page < items.meta.last_page - 4">
      <span>...</span>      
    </li>
    <li v-if="items.links.last != null" :class="items.meta.current_page == items.meta.last_page ? 'active' : ''">
      <a rel="last" href="#" @click="goToUrl(items.links.last, $event)"
        >{{items.meta.last_page}}</a
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
    search: "",
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
      var hasKey = this.checkKey;
      if(hasKey != ""){
        url = url + "&key="+hasKey + this.searchQuery;
      }else{
        url = url + this.searchQuery;
      }      
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
      if (this.search) {
        return "&" + this.search;
      } else {
        return "";
      }
    },
    checkKey(){
      if (this.items.meta.key){
        return this.items.meta.key;
      }else{
        return "";
      }
    }
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
