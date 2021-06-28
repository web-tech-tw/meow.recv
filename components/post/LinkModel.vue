<template>
  <v-card>
    <v-card-title>Share as new post</v-card-title>
    <v-card-text>
      <v-textarea
        v-model="article.content"
        class="blockquote"
        label="What do you think?"
      />
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="grey" @click="$emit('cancel')">Cancel</v-btn>
      <v-btn :disabled="empty" class="primary" @click="link">Say</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'LinkModel',
  props: {
    target: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    article: {
      link: '',
      content: '',
    },
  }),
  computed: {
    empty() {
      return !this.article.content
    },
  },
  watch: {
    target() {
      this.article.link = this.target.uuid
    },
  },
  methods: {
    async link() {
      await this.$axios.$post(`post.php`, this.article)
      this.$emit('success')
    },
  },
}
</script>
