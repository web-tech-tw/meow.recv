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
      parent: '39be1778-d40c-11eb-945e-e766493c50dc',
    },
  }),
  methods: {
    create() {
      this.$axios.$post('post.php', this.article)
    },
  },
}
</script>
