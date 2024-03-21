import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("./pages/Home.vue"),
    },
    {
        path: "/test",
        component: () => import("./pages/Test.vue"),
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});