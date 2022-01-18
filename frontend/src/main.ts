import { createApp } from "vue"
import App from "./App.vue"
import router from "./router"
import "./assets/main.css"

import Notifications from "@kyvg/vue3-notification"
import Maska from "maska"

import DashboardLayout from "./components/DashboardLayout.vue"
import EmptyLayout from "./components/EmptyLayout.vue"

const app = createApp(App)

app.component("default-layout", DashboardLayout)
app.component("empty-layout", EmptyLayout)

app.use(Notifications)
app.use(Maska)
app.use(router)
app.mount("#app")
