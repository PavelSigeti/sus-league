export default [
    {
        path: '/',
        name: 'Home',
        component: () => import('../views/Home.vue'),
        meta: {
            auth: false,
            layout: 'HomePage',
        }
    },
    {
        path: '/rating',
        name: 'home.rating',
        component: () => import('../views/HomeRating.vue'),
        meta: {
            layout: 'HomePage',
            auth: false,
        }
    },
    {
        path: '/stage',
        name: 'home.stage',
        component: () => import('../views/HomeStage.vue'),
        meta: {
            layout: 'HomePage',
            auth: false,
        }
    },
    {
        path: '/stage/:id',
        name: 'home.stage.show',
        component: () => import('../views/HomeStageShow.vue'),
        meta: {
            layout: 'HomePage',
            auth: false,
        }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('../views/Dashboard.vue'),
        meta: {
            auth: true,
            layout: 'Auth',
        }
    },
    {
        path: '/reset-password/:token',
        name: 'Reset-password',
        component: () => import('../views/ResetPassword.vue'),
        meta: {
            layout: 'HomePage',
        }
    },
    {
        path: '/admin',
        name: 'Admin',
        component: () => import('../views/admin/index.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/admin/tournament',
        name: 'tournament.index',
        component: () => import('../views/admin/tournament/index.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/admin/tournament/:id',
        name: 'tournament.edit',
        component: () => import('../views/admin/tournament/edit.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/admin/stage/:id',
        name: 'stage.edit',
        component: () => import('../views/admin/stage/edit.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: () => import('../views/admin/TheSettings.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/admin/pages',
        name: 'admin.pages',
        component: () => import('../views/admin/page/index.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/admin/pages/create',
        name: 'admin.pages.create',
        component: () => import('../views/admin/page/create.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/admin/page/:id',
        name: 'admin.pages.edit',
        component: () => import('../views/admin/page/edit.vue'),
        meta: {
            layout: 'Admin',
            auth: true,
        }
    },
    {
        path: '/dashboard/settings',
        name: 'user.settings',
        component: () => import('../views/user/Settings.vue'),
        meta: {
            layout: 'Auth',
            auth: true,
        }
    },
    {
        path: '/dashboard/stages',
        name: 'user.stages',
        component: () => import('../views/user/stage/StageUser.vue'),
        meta: {
            layout: 'Auth',
            auth: true,
        }
    },
    {
        path: '/dashboard/stage/:id',
        name: 'user.stage.page',
        component: () => import('../views/user/stage/StagePage.vue'),
        meta: {
            layout: 'Auth',
            auth: true,
        }
    },
    {
      path: '/dashboard/rating',
      name: 'rating',
      component: () => import('../views/user/Rating.vue'),
      meta: {
          layout: 'Auth',
          auth: true,
      }
    },

    {
        path: '/dashboard/page/:slug',
        name: 'page',
        component: () => import('../views/user/Page.vue'),
        meta: {
            layout: 'Auth',
            auth: true,
        }
    },

    {
        path: '/page/:slug',
        name: 'home-page',
        component: () => import('../views/PublicPage.vue'),
        meta: {
            layout: 'HomePage',
            auth: false,
        }
    },

    {
        path: '/:pathMatch(.*)*',
        name: '404',
        component: () => import('../views/NotFound.vue'),
        meta: {
            layout: 'Clear',
        }

    },
];
