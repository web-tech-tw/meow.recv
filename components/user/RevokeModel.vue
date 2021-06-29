<template>
  <v-card>
    <v-card-title>Revoke</v-card-title>
    <v-card-text>You will lost the control of this identity.</v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="grey" @click="$emit('cancel')">Cancel</v-btn>
      <v-btn class="red" @click="revoke">Revoke</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'RevokeModel',
  methods: {
    async revoke() {
      await this.$axios.$delete('user.php')
      this.$emit('success')
    },
  },
}
</script>
