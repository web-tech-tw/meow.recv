<template>
  <v-card class="text-center">
    <v-form>
      <v-textarea v-model="article.content" class="blockquote" />
      <v-btn color="grey" nuxt to="/">Cancel</v-btn>
      <v-btn color="primary" @click="create">Post</v-btn>
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
    async create() {
      await this.$axios.$post('post.php', this.article)
      await this.$router.replace('/')
    },
  },
}
</script>
