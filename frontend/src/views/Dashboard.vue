<template>
  <div>
    <!-- Animais para abate -->
    <h3 class="text-3xl font-medium text-gray-700">Animais para abate</h3>

    <div class="mt-4">
      <div class="flex flex-wrap -mx-6">
        <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
          <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
            <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">8,282</h4>
              <div class="text-gray-500">Possui 18 arrobas</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 sm:mt-0">
          <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
            <div class="p-3 bg-red-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">200,521</h4>
              <div class="text-gray-500">Possui mais de 05 anos de idade</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
          <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
            <div class="p-3 bg-green-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">215,542</h4>
              <div class="text-gray-500">Produz 70L e Consome 50KG por semana</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 xl:mt-0 space-top">
          <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
            <div class="p-3 bg-green-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">215,542</h4>
              <div class="text-gray-500">Produz 40L por semana</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Relatório geral -->
    <h3 class="text-3xl font-medium text-gray-700">Relatório Semanal</h3>

    <div class="mt-4">
      <div class="flex flex-wrap -mx-6">
        <div class="w-full px-6 sm:w-1/2 xl:w-1/3">
          <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
            <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">8,282</h4>
              <div class="text-gray-500">Leite produzido</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/3 sm:mt-0">
          <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
            <div class="p-3 bg-red-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">200,521</h4>
              <div class="text-gray-500">Animais abatidos</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/3 xl:mt-0">
          <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
            <div class="p-3 bg-green-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">215,542</h4>
              <div class="text-gray-500">Ração necessária</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-8"></div>

    <div class="flex flex-col mt-8">
      <!---->
      <div class="pagination-container">
        <NSpin class="pagination-container__list" :show="loading">
          <NList bordered>
            <NListItem v-for="(V, K) in list.data" :key="K">
              <font color="red">{{ V }}</font>
            </NListItem>
          </NList>
        </NSpin>

        <div class="pagination-container__page">
          <NPagination
            v-model:page="current"
            v-model:page-size="pageSize"
            :page-count="totalPage"
            :page-slot="5"
            :page-sizes="[5, 10, 20]"
            show-size-picker
          />
        </div>
      </div>
      <!---->
    </div>
  </div>
</template>

<script setup lang="ts">
import { NAvatar, NList, NListItem, NPagination, NSpin } from "naive-ui"
import { ref, computed, watch } from "vue"
import { usePagination } from "vue-request"
import axios from "axios"
import config from "../config"

// == [Functions] Functions to format and helper bulls date
const getBirth = (date: string) => {
  const newDate = new Date(date)
  const options = {
    year: "numeric",
    month: "long",
  }

  const birthMonth = newDate.toLocaleDateString("pt-BR", options)

  return birthMonth
}

const getAge = (date) => {
  const today = new Date()
  const birthDate = new Date(date)

  let age = today.getFullYear() - birthDate.getFullYear()
  let month = today.getMonth() - birthDate.getMonth()

  if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
    age--
  }

  return age
}
// == [AXIOS API] Axios call function
const BullService = (params: { page?: number; limit?: number }) => {
  return axios.get(config.api.host + "bull/list", {
    params,
  })
}

const { data, current, totalPage, loading, pageSize } = usePagination(BullService, {
  defaultParams: [
    {
      limit: 10,
    },
  ],
  pagination: {
    currentKey: "page",
    pageSizeKey: "limit",
    totalKey: "data.pagination.total",
    totalPageKey: "data.pagination.totalPage",
  },
})

const list = computed(() => data.value)
</script>

<style lang="scss">
.pagination-container {
  height: 420px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  &__list {
    height: 370px;
    overflow: auto;

    &-suffix {
      margin-right: 20px;
    }
  }
}
</style>
