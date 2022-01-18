import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router"

import Dashboard from "./views/Dashboard.vue"
import Manager from "./views/Manager.vue"
import Card from "./views/Card.vue"

const routes: RouteRecordRaw[] = [
  {
    path: "/",
    name: "Dashboard",
    component: Dashboard,
  },
  {
    path: "/manager",
    name: "Manager",
    component: Manager,
  },
  {
    path: "/cards",
    name: "Cards",
    component: Card,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
})

export default router
