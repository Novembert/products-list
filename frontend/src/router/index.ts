import { RouteNames } from '@/types/router'
import { createRouter, createWebHashHistory } from 'vue-router'

declare module 'vue-router' {
  interface RouteMeta {
    breadcrumb?: string
  }
}

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: RouteNames.App,
      component: () => import('@/app/AppLayout.vue'),
      children: [
        {
          path: '/',
          // This could display some kind of welcome dashboard, but currently we just have the products page
          name: RouteNames.Home,
          redirect: {
            name: RouteNames.Products,
          },
        },
        {
          path: '/products',
          name: RouteNames.Products,
          meta: {
            breadcrumb: 'productsForSale.title',
          },
          component: () => import('@/modules/products/views/ProductsView.vue'),
        },
      ],
    },
  ],
})

export default router
