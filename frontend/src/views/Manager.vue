<template>
  <div>
    <NSpin class="pagination-container__list" :show="loading">
      <h3 class="flex justify-center text-3xl font-semibold text-gray-700">
        Identificação: 43959345
      </h3>

      <h4>
        Status :
        <span
          v-if="bodyData.status_mock === 'dead'"
          class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full"
        >
          Abatido
        </span>
        <span
          v-else-if="bodyData.status_mock === 'ready'"
          class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full"
        >
          > Pronto para o abate
        </span>
        <span
          v-else
          class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full"
        >
          Produzindo
        </span>
      </h4>

      <h4 v-if="bodyData.birthday">
        Idade: {{ bodyData.age }} anos ({{ bodyData.birthday }})
      </h4>

      <h4>
        <b>{{ bodyData.sign }}@</b> ({{ bodyData.weight }}kg)
      </h4>

      <div class="mt-6">
        <div class="mt-4">
          <div class="p-6 bg-white rounded-md shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 capitalize">
              Gerenciar animal
            </h2>
            <div
              data-v-df52703a=""
              class="flex -space-x-1 justify-center mx-auto overflow-hidden margin"
            >
              <img
                src="https://openclipart.org/download/266218/Bull-Head-Silhouette.svg"
                alt=""
                data-v-df52703a=""
                class="inline-block h-10 w-10 rounded-full ring-2 ring-white"
                style="border: grey 1px solid"
              />
            </div>

            <form @submit.prevent="">
              <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                  <label class="text-gray-700"> IDentificação </label>
                  <input
                    class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                    type="text"
                    v-maska="'##########'"
                    v-model="formCode"
                  />
                </div>

                <div>
                  <label class="text-gray-700"> Peso do animal </label>
                  <input
                    class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                    type="text"
                    v-maska="'#####'"
                    v-model="formWeight"
                  />
                </div>

                <div>
                  <label class="text-gray-700">
                    Consumo de ração semanal
                  </label>
                  <input
                    class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                    type="text"
                    v-maska="'#####'"
                    v-model="formFood"
                  />
                </div>
                <div>
                  <label class="text-gray-700">
                    Produção de leite semanal
                  </label>
                  <input
                    class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                    type="text"
                    v-maska="'#####'"
                    v-model="formMilk"
                  />
                </div>
              </div>

              <div class="flex justify-end mt-4 margin-left">
                <button
                  @click="goReturn()"
                  class="px-4 py-2 text-gray-200 bg-gray-400 rounded-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700 margin-right"
                >
                  Cancelar
                </button>
                <button
                  @click="deleteBull(getId)"
                  class="px-4 py-2 text-red-200 bg-red-800 rounded-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700 margin-right"
                >
                  deletar
                </button>

                <button
                  @click="goToSlaughter(getId)"
                  v-if="bodyData.status_mock === 'ready'"
                  class="px-4 py-2 text-gray-200 bg-gray-800 rounded-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700 margin-right"
                >
                  Enviar ao abate
                </button>
                <button
                  @click="updateBull(bodyData.id)"
                  class="px-4 py-2 text-green-200 bg-green-400 rounded-md hover:bg-green-700 focus:outline-none focus:bg-green-700 margin-right"
                >
                  Salvar alteração
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </NSpin>
  </div>
</template>

<script setup lang="ts">
import { defineComponent, ref } from "vue"
import { useRouter, useRoute } from "vue-router"
import config from "../config"
import axios from "axios"

import { notify } from "@kyvg/vue3-notification"
import { NSpin } from "naive-ui"
import { mask } from "maska"

const router = useRouter()
const route = useRoute()
const bodyData = ref({})
const loading = ref(true)
const getId = parseInt(route.query.id)

const formCode = ref(0)
const formWeight = ref(0)
const formMilk = ref(0)
const formFood = ref(0)

axios
  .get(`${config.api.host}bull/get/${getId}`)
  .then(function (response) {
    loading.value = false
    bodyData.value = response.data.bull

    formCode.value = response.data.bull.code
    formWeight.value = response.data.bull.weight
    formMilk.value = response.data.bull.week_milk
    formFood.value = response.data.bull.week_food
  })
  .catch(function (error) {
    notify({
      type: "error",
      title: "Não identificado",
      text: `Não foi possível identificar o animal de código ${getId}`,
    })
    goReturn()
  })

// == [ Methods ] ===========================================>
const goReturn = () => router.push("/")

const deleteBull = async (id) => {
  await axios
    .delete(`${config.api.host}bull/delete/${id}`)
    .then(function (response) {
      notify({
        type: "success",
        title: `deletado`,
        text: `O animal foi deletado do sistema`,
      })
      goReturn()
    })
    .catch(function (error) {
      notify({
        type: "error",
        title: "Não identificado",
        text: `Falha ao deletar o animal do sistema`,
      })
    })
}

const goToSlaughter = async (id) => {
  bodyData.status = "dead"
  await axios
    .put(`${config.api.host}bull/update/${id}`, {
      status: "dead",
    })
    .then(function (response) {
      notify({
        type: "success",
        title: `Tudo certo`,
        text: `Animal enviado para o abate`,
      })
      goReturn()
    })
    .catch(function (error) {
      notify({
        type: "error",
        title: "Falha !",
        text: `Houve um problema ao enviar o animal ao abate`,
      })
    })
}

const updateBull = async (id) => {
  await axios
    .put(`${config.api.host}bull/update/${id}`, {
      code: formCode.value,
      weight: formWeight.value,
      weekMilk: formMilk.value,
      weekFood: formFood.value,
    })
    .then(function (response) {
      notify({
        type: "success",
        title: `Atualizado`,
        text: `As informações foram atualizadas com sucesso`,
      })
      goReturn()
    })
    .catch(function (error) {
      notify({
        type: "error",
        title: "Falha na atualização !",
        text: `Falha ao atualizar as informações do sistema`,
      })
    })
}
</script>
<style scoped>
.margin-right {
  margin-right: 10px;
}

.dead-text {
  text-decoration: line-through;
}
</style>
