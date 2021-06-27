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
  async fetch() {
    try {
      await this.$axios.$get('timeline.php')
    } catch (e) {
      if (e.response.status === 401) {
        await this.$router.replace('/signup')
      }
    }
  },
  methods: {
    create() {
      this.$axios.$post('post.php', this.article)
    },
  },
}
</script>
