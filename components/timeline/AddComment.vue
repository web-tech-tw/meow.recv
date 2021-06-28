<template>
  <v-list-item>
    <v-list-item-content>
      <v-text-field
        v-model="article.content"
        append-outer-icon="mdi-send"
        @click:append-outer="create"
        @keydown.ctrl.enter="create"
      />
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
      this.article.content = ''
      this.$emit('submit')
    },
  },
}
</script>
