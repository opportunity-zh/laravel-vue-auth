


export const routes = [
    {
        path: "/",
        component: () => import("./pages/Home.vue"),
    },
    {
        path: "/login",
        component: () => import("./pages/Auth/Login.vue"),
    },
    {
        path: "/test",
        component: () => import("./pages/Test.vue"),
        meta: { requiresAuth: true },
    },
];





