Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'list-rut',
      path: '/list-rut',
      component: require('./components/Tool'),
    },
  ])
})
