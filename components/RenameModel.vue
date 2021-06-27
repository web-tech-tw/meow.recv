<template>
  <v-card>
    <v-card-title>Rename/Move</v-card-title>
    <v-card-text>
      <v-text-field
        v-model="user.display_name"
        label="Type new name for renaming, or append target directory for moving..."
        autofocus
        @keydown.enter="rename"
      />
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="grey" @click="$emit('cancel')">Cancel</v-btn>
      <v-btn class="primary" @click="rename">Rename</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'RenameModel',
  data: () => ({
    user: {
      display_name: '',
    },
  }),
  methods: {
    async rename() {
      await this.$axios.$patch(`user.php`, this.user)
      this.$emit('success')
    },
  },
}
</script>
