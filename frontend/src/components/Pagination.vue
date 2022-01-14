<template>
  <div
    v-if="paginationContext.state.numberOfPages > 1"
    class="pagination-container"
    aria-label="..."
  >
    <ul v-if="paginationContext.state.numberOfPages" class="pagination">
      <li
        class="page-item"
        :class="{ disabled: paginationContext.state.currentPage === 1 }"
        @click="paginationContext.previousPage"
      >
        <span class="page-link">&laquo;</span>
      </li>
      <li
        v-for="index in paginationContext.state.numberOfPages"
        :key="index"
        class="page-item"
        @click="paginationContext.setCurrentPage(index)"
      >
        <div
          class="page-link"
          :class="{
            'active-page': paginationContext.state.currentPage === index,
          }"
        >
          {{ index }}
        </div>
      </li>
      <li
        class="page-item"
        :class="{
          disabled:
            paginationContext.state.currentPage ===
              paginationContext.state.numberOfPages ||
            !paginationContext.state.numberOfPages,
        }"
        @click="paginationContext.nextPage()"
      >
        <div class="page-link">&raquo;</div>
      </li>
    </ul>
  </div>
</template>

<script lang="ts" setup>
import { defineProps } from "vue"

const props = defineProps({
  paginationContext: {
    required: true,
  },
})
</script>

<style scoped lang="scss">
.pagination-container {
  display: inline-block;
}

.pagination {
  margin: 0px;
  padding: 0px;
  display: flex;
  border: none;
  box-sizing: border-box;
  overflow: hidden;
  word-wrap: break-word;
  align-content: center;
  border-radius: 14px;
  height: 60px;
  box-shadow: rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 0%) 0px 0px 0px 0px,
    rgb(0 0 0 / 12%) 0px 1px 1px 0px, rgb(60 66 87 / 16%) 0px 0px 0px 1px,
    rgb(0 0 0 / 0%) 0px 0px 0px 0px, rgb(0 0 0 / 0%) 0px 0px 0px 0px,
    rgb(60 66 87 / 8%) 0px 2px 5px 0px;
}

.page-item {
  flex: 1;
  display: flex;
  border: none;
  justify-content: center;
  cursor: pointer;
  margin-bottom: 0px;
  height: 60px;
}

.page-link {
  color: #666b85;
  float: left;
  border: none;
  padding: 0px 25px;
  font-size: 14px;
  font-weight: 800;
  line-height: 60px;
  text-decoration: none;
  &:hover {
    color: #333333;
    background-color: #e9e9e9;
    border: none;
  }
}

.active-page {
  background-color: #60d394 !important;
  border: none;
  color: white !important;
  &:hover {
    border: none;
  }
}

.disabled {
  .page-link {
    background-color: #f9fafb;
  }
  cursor: not-allowed;
}
</style>
