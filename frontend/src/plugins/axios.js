export default (ctx) => {
  ctx.$axios.onError((error) => {
    if (error.response) {
      switch (error.response.status) {
        case 422:
          ctx.app.$notify.error({
            message: error.response.data,
          })
          break
        case 401:
          ctx.app.$notify.error({
            message: error.response.data,
          })
          if (ctx.$auth.loggedIn) {
            ctx.$auth.logout()
          }
          break
        default:
          ctx.app.$notify.error({
            message: error.response.data,
          })
      }
    } else {
      ctx.app.$notify.error({
        message: 'Неизвестная ошибка',
      })
    }

    return Promise.reject(error.response)
  })
}
