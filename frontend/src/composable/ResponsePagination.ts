import { computed, reactive, readonly, Ref, watch } from "vue"

interface PaginationConfig {
  rowsPerPage: number
  arrayToPaginate: Ref<any[]>
}

export interface PaginationContext {
  state: {
    numberOfPages: number
    currentPage: number
    rowsPerPage: number
  }
  paginatedArray: Ref<any[]>
  setCurrentPage(pageNumber: number): void
  previousPage(): void
  nextPage(): void
}

export function usePagination(config: PaginationConfig) {
  const paginationState = reactive({
    numberOfPages: 0,
    currentPage: 1,
    rowsPerPage: config?.rowsPerPage || 8,
  })

  const previousPage = async () => {
    if (paginationState.currentPage > 1) {
      paginationState.currentPage--
    }
  }

  const setCurrentPage = async (pageNumber: number) =>
    (paginationState.currentPage = pageNumber)

  const nextPage = async () => {
    if (paginationState.numberOfPages > paginationState.currentPage) {
      paginationState.currentPage++
    }
  }

  watch(config.arrayToPaginate, () => {
    paginationState.numberOfPages = Math.ceil(
      (config.arrayToPaginate.value.length || 0) / paginationState.rowsPerPage
    )
  })

  const paginatedArray = computed(() =>
    config.arrayToPaginate.value.slice(
      (paginationState.currentPage - 1) * paginationState.rowsPerPage,
      paginationState.currentPage * paginationState.rowsPerPage
    )
  )

  const paginationContext: PaginationContext = {
    setCurrentPage,
    previousPage,
    nextPage,
    state: readonly(paginationState),
    paginatedArray,
  }

  return paginationContext
}
