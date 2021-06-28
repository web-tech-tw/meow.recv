<template>
  <v-list-item>
    <v-list-item-content>
      <v-textarea v-model="article.content" />
      <v-btn title="Comments" rounded @click="create">
        <v-icon>mdi-comment-multiple-outline</v-icon>
      </v-btn>
    </v-list-item-content>
  </v-list-item>
</template>

<script>
export default {
  name: 'AddComment',
  props: {
    parent: {
      type: String,
      required: true,
    },
  },
  data: () => ({
    article: {
      content: '',
      parent: '',
    },
  }),
  mounted() {
    this.article.parent = this.parent
  },
  methods: {
    async create() {
      await this.$axios.$post('post.php', this.article)
      this.$emit('submit')
    },
  },
}
</script>
