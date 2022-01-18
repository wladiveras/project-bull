<template>
  <div>
    <button
      @click="openModal()"
      class="hot-fix bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
    >
      Incluir Animal no sistema
    </button>
    <div
      :class="`modal ${
        !isOpen && 'opacity-0 pointer-events-none'
      } z-50 fixed w-full h-full top-0 left-0 flex items-center justify-center`"
    >
      <div
        @click="isOpen = false"
        class="absolute w-full h-full bg-gray-900 opacity-50 modal-overlay"
      ></div>

      <div
        class="z-50 w-11/12 mx-auto overflow-y-auto bg-white rounded shadow-lg modal-container md:max-w-md"
      >
        <div
          class="absolute top-0 right-0 z-50 flex flex-col items-center mt-4 mr-4 text-sm text-white cursor-pointer modal-close"
        >
          <svg
            class="text-white fill-current"
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            viewBox="0 0 18 18"
          >
            <path
              d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
            />
          </svg>
          <span class="text-sm">(Esc)</span>
        </div>

        <!-- Add margin if you want to see some of the overlay behind the modal-->
        <div class="px-6 py-4 text-left modal-content">
          <!--Title-->
          <div class="flex items-center justify-between pb-3">
            <p class="text-2xl font-bold">{{ modalTitle }}</p>
            <div
              class="z-50 cursor-pointer modal-close"
              @click="isOpen = false"
            >
              <svg
                class="text-black fill-current"
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 18 18"
              >
                <path
                  d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                />
              </svg>
            </div>
          </div>

          <!--Body-->
          <p v-if="!isFinished">
            <input
              class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
              type="text"
              placeholder="Peso do animal"
              v-maska="'#####'"
              v-model="formWeight"
            />

            <input
              class="w-1/2 mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
              type="text"
              placeholder="Leite Semanal"
              v-maska="'#####'"
              v-model="formMilk"
            />

            <input
              class="w-1/2 mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
              type="text"
              placeholder="Ração Semanal"
              v-maska="'#####'"
              v-model="formFood"
            />
            <br>
            <br>
            <h3 class="flex birth-day justify-center">Data de Nascimento</h3>
            <br>
            <div class="flex justify-center">
            <v-date-picker mode="date" v-model="formBirthday" />
            </div>
          </p>
            <br>
            <br>
          <!--Footer-->
          <div class="flex justify-end pt-2">
            <button
              @click="isOpen = false"
              class="p-3 px-6 py-3 mr-2 text-indigo-500 bg-transparent rounded-lg hover:bg-gray-100 hover:text-indigo-400 focus:outline-none"
            >
              {{ closeButtonText }}
            </button>
            <button
            v-if="!isFinished"
              @click="onInsert()"
              class="px-6 py-3 font-medium tracking-wide text-white bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none"
            >
              Incluir
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue"
import { useRouter } from "vue-router"
import format from 'date-fns/format'
import config from "../../config"
import axios from "axios"

import { notify } from "@kyvg/vue3-notification"
import { mask } from "maska"

const modalTitle = ref("Adicionar Animal")

const router = useRouter()
const dropdownOpen = ref(false)
const searchInput = ref("")
const isOpen = ref(false)
const isResponseOk = ref(false)
const isFinished = ref(false)
const formMilk = ref("")
const formFood = ref("")
const formWeight = ref("")
const formBirthday = ref("")
const bullCode = ref("")
const closeButtonText = ref("Cancelar")

const openModal = () =>{
  isOpen.value = true

  isResponseOk.value = false
  isFinished.value = false
  formMilk.value = ""
  formFood.value = ""
  formWeight.value = ""
  formBirthday.value = ""
  bullCode.value = ""
  modalTitle = "Adicionar Animal"
  closeButtonText = "Cancelar"
}

const onInsert = () => {

const datetime = format(formBirthday.value, 'yyyy-MM-dd HH:mm:ss');

  axios
    .post(`${config.api.host}bull/add`, {
      birthday: datetime,
      weight: formWeight.value,
      weekMilk: formMilk.value,
      weekFood: formFood.value
    })
    .then(function (response) {
      notify({
        type: "success",
        title: "Adicionado !",
        text: `O animal foi registrado com sucesso `,
      })

    bullCode.value = response.data.code
    modalTitle.value = "Animal Registrado"
    closeButtonText.value = "Fechar"
    isFinished.value = true
    
    axios
      .get(`${config.api.host}bull/get/id/${response.data.code}`)
      .then(function (response) {
        router.push(`/manager?id=${response.data.bull.id}`)
      })
    })
    .catch(function (error) {
      notify({
        type: "error",
        title: "Não identificado",
        text: `Não foi possivel realizar a inclusão do animal no sistema'`,
      })
    })
}
</script>

<style>
.birth-day {
    font-size: 15px;
    font-weight:bold;
}
</style>