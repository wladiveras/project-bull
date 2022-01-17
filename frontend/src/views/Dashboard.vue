<template>
  <div>
    <!-- Animais para abate -->
    <h3 class="text-3xl font-medium text-gray-700">Animais para abate</h3>

    <div class="mt-4">
      <div class="flex flex-wrap -mx-6">
        <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
          <div
            class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm"
          >
            <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4
                v-if="bodyData.readyTo.sign"
                class="text-2xl font-semibold text-gray-700"
              >
                {{ bodyData.readyTo.sign }}
              </h4>
              <div class="text-gray-500">Possui 18 arrobas</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 sm:mt-0">
          <div
            class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm"
          >
            <div class="p-3 bg-red-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">
                {{ numberWithCommas(bodyData.readyTo.older) }}
              </h4>
              <div class="text-gray-500">Possui mais de 05 anos de idade</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
          <div
            class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm"
          >
            <div class="p-3 bg-green-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">
                {{ numberWithCommas(bodyData.readyTo.food_milk) }}
              </h4>
              <div class="text-gray-500">
                Produz 70L e Consome 50KG por semana
              </div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 xl:mt-0 space-top">
          <div
            class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm"
          >
            <div class="p-3 bg-green-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">
                {{ numberWithCommas(bodyData.readyTo.milk) }}
              </h4>
              <div class="text-gray-500">Produz 40L por semana</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Relatório geral -->
    <h3 class="margin-top text-3xl font-medium text-gray-700">
      Relatório Semanal
    </h3>

    <div class="mt-4">
      <div class="flex flex-wrap -mx-6">
        <div class="w-full px-6 sm:w-1/2 xl:w-1/3">
          <div
            class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm"
          >
            <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">
                {{ numberWithCommas(bodyData.week.milk) }}
              </h4>
              <div class="text-gray-500">Leite produzido</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/3 sm:mt-0">
          <div
            class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm"
          >
            <div class="p-3 bg-red-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">
                {{ numberWithCommas(bodyData.week.dead) }}
              </h4>
              <div class="text-gray-500">Animais abatidos</div>
            </div>
          </div>
        </div>

        <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/3 xl:mt-0">
          <div
            class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm"
          >
            <div class="p-3 bg-green-600 bg-opacity-75 rounded-full">!</div>

            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">
                {{ numberWithCommas(bodyData.week.food) }}
              </h4>
              <div class="text-gray-500">Ração necessária</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-8"></div>

    <div class="flex flex-col mt-8">
      <!---->
      <BullsList />
      <!---->
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue"
import axios from "axios"
import config from "../config"
import BullsList from "../components/dashboard/BullsList.vue"
import { notify } from "@kyvg/vue3-notification"

const loading = ref(false)
const bodyData = ref({})

const numberWithCommas = (x) => {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

axios
  .get(`${config.api.host}bull/get-report`)
  .then(function (response) {
    loading.value = false
    bodyData.value = response.data.report
  })
  .catch(function (error) {
    notify({
      type: "error",
      title: "Falha ao carregar dados",
      text: `Falha ao carregar informações, tente novamente mais tarde.`,
    })
  })
</script>

<style scoped>
.margin-top {
  margin-top: 50px;
}
</style>
