<template>
  <v-snackbar v-if="text" v-model="status">
    {{ text }}
    <template #action="{ attrs }">
      <v-btn color="pink" text v-bind="attrs" @click="status = false">
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
  name: 'Notice',
  props: {
    text: {
      type: String,
      required: true,
    },
  },
  data: () => ({
    status: true,
  }),
  mounted() {
    setTimeout(() => {
      this.status = false
      this.$store.commit('setNotification', '')
    }, 5000)
  },
}
</script>
