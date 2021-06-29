<template>
  <v-card>
    <v-card-title>Reset Display Name</v-card-title>
    <v-card-text>
      <v-text-field
        v-model="displayName"
        label="Who are you? ouo"
        autofocus
        @keydown.enter="rename"
      />
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="grey" @click="cancel">Cancel</v-btn>
      <v-btn class="primary" :disabled="empty || unchanged" @click="rename">
        Rename
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'RenameModel',
  data: () => ({
    displayName: '',
  }),
  computed: {
    empty() {
      return !this.displayName
    },
    unchanged() {
      return this.displayName === this.originalName
    },
    originalName() {
      return this.$store.state.profile.display_name
    },
  },
  mounted() {
    this.displayName = this.originalName
  },
  methods: {
    cancel() {
      this.displayName = this.originalName
      this.$emit('cancel')
    },
    async rename() {
      await this.$axios.$patch(`user.php?display_name=${this.displayName}`)
      this.$emit('success')
    },
  },
}
</script>
