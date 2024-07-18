<template>
  <ul class="list-container">
    <li
      v-for="item in formattedItems"
      :key="item.id"
      :data-id="item.id"
      :data-name="item.name"
      :data-slug="item.slug"
      :data-parent="item.parent"
      :data-parent-id="item.parentId"
    >
      <div class="list-item">
        <span v-if="$vnode.key > 0" class="child-arrow">&crarr;</span>
        <div class="item-name">
          <img :src="item.image" />
          {{ item.name }}
        </div>
        <app-actions
          @deleteItem="emitDeleteEvent(item.id)"
          class="item-action"
          :actions="{
            edit: { name: 'service.edit', params: { id: item.id } },
            delete: item.id,
          }"
        ></app-actions>
      </div>

      <service-tree
        v-if="item.children.length > 0"
        :key="item.id"
        @deleteItem="emitDeleteEvent"
        :items="item.children"
      ></service-tree>
    </li>
  </ul>
</template>

<script>
export default {
  name: "ServiceTree",

  props: {
    items: Array,
  },

  methods: {
    format(parentId = null) {
      if (this.$vnode.key > 0) return this.items;

      return this.items
        .filter((item) => item.parentId === parentId)
        .map((item) => {
          return {
            id: item.id,
            name: item.name,
            slug: item.slug,
            parent: item.parent,
            parentId: item.parentId,
            image: item.image50 || item.image,
            children: this.format(item.id),
          };
        });
    },

    emitDeleteEvent(itemId) {
      this.$emit("deleteItem", itemId);
    },
  },

  computed: {
    formattedItems() {
      return this.format(this.$vnode.key || null);
    },
  },
};
</script>

<style lang="scss"
       scoped>
$lineHeight: 40px;

ul.list-container {
  list-style-type: none;
  margin-bottom: 0;
  background: rgba(0, 135, 203, 0.1);

  > li {
    .list-item {
      width: 100%;
      padding: 10px 15px;
      background-color: #ffffff;
      border-bottom: 1px solid #efefef;
      box-sizing: border-box;
      user-select: none;
      color: #333333;
      font-weight: 400;
      position: relative;

      .child-arrow {
        font-weight: bold;
        position: absolute;
        left: -20px;
        transform: rotateY(180deg);
        line-height: $lineHeight;
      }

      .item-name,
      .item-action {
        display: inline-block;
      }

      .item-name img {
        width: $lineHeight;
        height: auto;
        border-radius: 50%;
        margin-right: 10px;
      }

      .item-action {
        float: right;
        line-height: $lineHeight;
      }
    }
  }
}
</style>