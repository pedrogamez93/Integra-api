Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-push-notification',
      path: '/nova-push-notification',
      component: require('./components/Tool'),
    },
  ])
})
