export default async ({ app, $axios, store }) => {
  try {
    const profile = await $axios.$get('user.php')
    store.commit('syncProfile', profile)
  } catch (e) {
    if (e.response.status === 401) {
      setTimeout(() => app.router.push('/signup'), 1000)
    }
  }
}
