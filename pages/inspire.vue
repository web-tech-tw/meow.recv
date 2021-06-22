<template>
  <v-card class="text-center">
    <v-form>
      <v-textarea v-model="content" class="blockquote" />
      <v-btn color="secondary" nuxt to="/inspire">Cancel</v-btn>
      <v-btn color="primary" nuxt to="/inspire">Post</v-btn>
    </v-form>
  </v-card>
</template>

<script>
export default {
  data: () => ({
    article: {
      content: '',
    },
  }),
  methods: {
    create() {
      this.$axios.$post('api/post.php', this.article)
    },
  },
}
</script>
