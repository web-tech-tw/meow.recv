<template>
  <div>
    <v-card>
      <v-card-title>{{ article.author.display_name }}</v-card-title>
      <v-card-subtitle>
        {{ timeReadable(article.created_time) }}
      </v-card-subtitle>
      <v-card-text v-html="getMessageHTML(article.content)" />
      <v-card-actions>
        <v-spacer />
        <v-btn-toggle shaped mandatory>
          <v-btn title="Comments" rounded @click="comments = !comments">
            <v-icon>mdi-comment-multiple-outline</v-icon>
            {{ article.children.length }}
          </v-btn>
          <v-menu v-if="isOwner" offset-y>
            <template #activator="{ on, attrs }">
              <v-btn title="More" rounded v-bind="attrs" v-on="on">
                <v-icon>mdi-more</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-pen</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Edit</v-list-item-content>
              </v-list-item>
              <v-list-item @click="$emit('delete', article)">
                <v-list-item-icon>
                  <v-icon>mdi-delete</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Remove</v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-btn-toggle>
      </v-card-actions>
    </v-card>
    <slot v-if="comments" name="comments" />
  </div>
</template>

<script>
import { timeReadable, getMessageHTML } from '~/utils/content'

export default {
  name: 'Post',
  props: {
    article: {
      type: Object,
      required: true,
    },
    showComments: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data: () => ({ timeReadable, getMessageHTML, comments: false }),
  computed: {
    isOwner() {
      return this.article.author.identity === this.$store.state.profile.identity
    },
  },
  mounted() {
    this.comments = this.showComments
  },
}
</script>
