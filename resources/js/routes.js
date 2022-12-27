export default [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/products', component: require('./components/product/Products.vue').default },
    { path: '/product/tag', component: require('./components/product/Tag.vue').default },
    { path: '/product/category', component: require('./components/product/Category.vue').default },
    { path: '/reg', component: require('./components/Register.vue').default },
    { path: '/qcpass', component: require('./components/Qcpass.vue').default },
    { path: '/userserp', component: require('./components/UsersOdoo.vue').default },
    { path: '/segel', component: require('./components/Segel.vue').default },
    { path: '/truck', component: require('./components/Truck.vue').default },
    { path: '/driver', component: require('./components/Driver.vue').default },
    { 
        path: '/contractsearch', 
        component: require('./components/ContractSearch.vue').default,
        meta: {
            requiresAuth: true
        } 
    },
    { path: '*', component: require('./components/NotFound.vue').default }
];
