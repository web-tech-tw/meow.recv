<template>
  <v-app dark>
    <v-app-bar fixed app>
      <v-toolbar-title>melody.proto</v-toolbar-title>
      <v-spacer />
    </v-app-bar>
    <v-main>
      <nuxt />
      <notice />
    </v-main>
  </v-app>
</template>

<script>
import Notice from '~/components/Notice'

export default {
  components: { Notice },
}
</script>
