<template>
  <v-card class="text-center">
    <v-btn color="primary" @click="revoke">Post</v-btn>
  </v-card>
</template>

<script>
export default {
  methods: {
    revoke() {
      this.$axios.$delete('user.php')
    },
  },
}
</script>
