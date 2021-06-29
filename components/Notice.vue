<template>
  <v-snackbar v-if="text" v-model="status">
    {{ text }}
    <template #action="{ attrs }">
      <v-btn color="pink" text v-bind="attrs" @click="close">Close</v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
  name: 'Notice',
  data: () => ({
    status: true,
  }),
  computed: {
    text() {
      return this.$store.state.notification
    },
  },
  watch: {
    text() {
      this.status = true
      setTimeout(close, 5000)
    },
  },
  methods: {
    close() {
      this.status = false
      this.$store.commit('setNotification', '')
    },
  },
}
</script>
