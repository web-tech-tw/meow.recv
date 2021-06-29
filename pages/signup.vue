<template>
  <v-card>
    <v-card-title>Create a virtual user</v-card-title>
    <v-card-text>
      <v-text-field
        v-model="user.display_name"
        label="Name"
        autofocus
        @keydown.enter="submit"
      />
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="primary" :disabled="empty" @click="submit">Create</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  layout: 'unauthenticated',
  data: () => ({
    user: {
      display_name: '',
    },
  }),
  computed: {
    empty() {
      return !this.user.display_name
    },
  },
  methods: {
    async submit() {
      await this.$axios.$post('user.php', this.user)
      const profile = await this.$axios.$get('user.php')
      this.$store.commit('setNotification', 'Hello World!')
      this.$store.commit('syncProfile', profile)
      await this.$router.replace('/')
    },
  },
}
</script>
