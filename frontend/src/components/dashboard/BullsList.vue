<template>
  <NSpin class="pagination-container__list" :show="loading">
    <div class="flex flex-col mt-8">
      <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div
          class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg"
        >
          <table class="min-w-full">
            <thead>
              <tr>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50"
                >
                  Código / Arroba
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50"
                >
                  idade
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50"
                >
                  Peso
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50"
                >
                  Leite semanal
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50"
                >
                  Ração semanal
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50"
                >
                  status
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
              </tr>
            </thead>

            <tbody class="bg-white">
              <tr v-for="(bull, index) in bulls" :key="index">
                <td
                  class="px-6 py-4 border-b border-gray-200 whitespace-nowrap"
                >
                  <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10">
                      <img
                        class="w-10 h-10 rounded-full"
                        src="https://openclipart.org/download/266218/Bull-Head-Silhouette.svg"
                        alt=""
                      />
                    </div>

                    <div class="ml-4">
                      <div class="text-sm font-medium leading-5 text-gray-900">
                        {{ bull.code }}
                      </div>
                      <div class="text-sm leading-5 text-gray-500">
                        @ {{ bull.sign }}
                      </div>
                    </div>
                  </div>
                </td>

                <td
                  class="px-6 py-4 border-b border-gray-200 whitespace-nowrap"
                >
                  <div class="text-sm leading-5 text-gray-900">
                    {{ getAge(bull.birthday.date) }} anos
                  </div>
                  <div class="text-sm leading-5 text-gray-900">
                    {{ getBirth(bull.birthday.date) }}
                  </div>
                </td>
                <td
                  class="px-6 py-4 text-sm leading-5 text-gray-500 border-b border-gray-200 whitespace-nowrap"
                >
                  {{ bull.weight }}
                </td>
                <td
                  class="px-6 py-4 text-sm leading-5 text-gray-500 border-b border-gray-200 whitespace-nowrap"
                >
                  {{ bull.week_milk }}L
                </td>
                <td
                  class="px-6 py-4 text-sm leading-5 text-gray-500 border-b border-gray-200 whitespace-nowrap"
                >
                  {{ bull.week_food }} kg
                </td>
                <td
                  class="px-6 py-4 border-b border-gray-200 whitespace-nowrap"
                >
                  <span
                    v-if="bull.status === 'dead'"
                    class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full"
                  >
                    Pronto pro abate
                  </span>
                  <span
                    v-else-if="
                      bull.week_milk < 40 ||
                      (bull.week_milk < 70 && bull.week_food > 40) ||
                      getAge(bull.birthday.date) > 5 ||
                      bull.sign > 18
                    "
                    class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full"
                  >
                    Abatido
                  </span>
                  <span
                    v-else
                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full"
                  >
                    Produzindo
                  </span>
                </td>
                <td
                  class="px-6 py-4 text-sm font-medium leading-5 text-right border-b border-gray-200 whitespace-nowrap"
                >
                  <a
                    @click="goManager(bull.id)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Editar
                  </a>
                </td>
              </tr>
            </tbody>
          </table>

          <pagination
            :totalPages="totalPage"
            :perPage="pageSize"
            :currentPage="current"
            @pagechanged="onPageChange"
          />
        </div>
      </div>
    </div>
  </NSpin>
</template>

<script setup lang="ts">
import axios from "axios"
import { NSpin } from "naive-ui"
import { ref, computed, watch } from "vue"
import { usePagination } from "vue-request"
import Pagination from "../Pagination.vue"
import config from "../../config"
import { useRouter } from "vue-router"

const router = useRouter()
const goManager = (id) => router.push(`/manager?id=${id}`)

// == [Functions] Functions to format and helper bulls date
const getBirth = (date: string) => {
  const newDate = new Date(date)
  const options = {
    year: "numeric",
    month: "long",
  }

  return newDate.toLocaleDateString("pt-BR", options)
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
  return axios.get(`${config.api.host}bull/list`, {
    params,
  })
}

const { data, current, totalPage, loading, pageSize } = usePagination(
  BullService,
  {
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
  }
)

const onPageChange = (page) => {
  current.value = page
}

const bulls = computed(() => data.value?.data.bulls || [])
</script>
