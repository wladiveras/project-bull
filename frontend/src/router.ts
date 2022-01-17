import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router"

import Dashboard from "./views/Dashboard.vue"
import Manager from "./views/Manager.vue"
import Tables from "./views/Tables.vue"
import UIElements from "./views/UIElements.vue"
import Login from "./views/Login.vue"
import Modal from "./views/Modal.vue"
import Card from "./views/Card.vue"
import Blank from "./views/Blank.vue"
import NotFound from "./views/NotFound.vue"

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
  {
    path: "/ui-elements",
    name: "UIElements",
    component: UIElements,
  },
  {
    path: "/modal",
    name: "Modal",
    component: Modal,
  },
  {
    path: "/blank",
    name: "Blank",
    component: Blank,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
})

export default router
