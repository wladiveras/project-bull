import { ViteSSG } from "vite-ssg"
import generatedRoutes from "virtual:generated-pages"
import { setupLayouts } from "virtual:generated-layouts"
import App from "./App.vue"

// windicss layers
import "virtual:windi-base.css"
import "virtual:windi-components.css"
import "./styles/main.css"
import "virtual:windi-utilities.css"
import "virtual:windi-devtools"

const routes = setupLayouts(generatedRoutes)

export const createApp = ViteSSG(
  App,
  { routes, base: import.meta.env.BASE_URL },
  (ctx) => {
    Object.values(import.meta.globEager("./modules/*.ts")).map((i) =>
      i.install?.(ctx)
    )
  }
)
