<template>
  <v-card>
    <v-card-title>Edit</v-card-title>
    <v-card-text>
      <v-textarea
        v-model="article.content"
        class="blockquote"
        label="Power your article"
      />
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="grey" @click="$emit('cancel')">Cancel</v-btn>
      <v-btn :disabled="empty || unchanged" class="primary" @click="refactor">
        OK
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'EditModel',
  props: {
    target: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    article: {
      uuid: '',
      content: '',
    },
  }),
  computed: {
    empty() {
      return !this.article.content
    },
    unchanged() {
      return this.article.content === this.target.content
    },
  },
  watch: {
    target() {
      this.article.uuid = this.target.uuid
      this.article.content = this.target.content
    },
  },
  methods: {
    async refactor() {
      await this.$axios.$put('post.php', this.article)
      this.$emit('success')
    },
  },
}
</script>
