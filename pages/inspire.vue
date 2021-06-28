<template>
  <v-card class="text-center">
    <v-card-text>
      <v-textarea
        v-model="article.content"
        label="How are you today?"
        class="blockquote"
      />
      <v-btn color="grey" nuxt to="/">Cancel</v-btn>
      <v-btn color="primary" :disabled="empty" @click="create">Say</v-btn>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  data: () => ({
    article: {
      content: '',
    },
  }),
  computed: {
    empty() {
      return !this.article.content
    },
  },
  methods: {
    async create() {
      await this.$axios.$post('post.php', this.article)
      await this.$router.replace('/')
    },
  },
}
</script>
