<template>
  <div>
    <v-card>
      <slot name="top" />
      <v-card-title>{{ article.author.display_name }}</v-card-title>
      <v-card-subtitle>
        {{ timeReadable(article.created_time) }}
      </v-card-subtitle>
      <v-card-text
        class="mark-output"
        v-html="getMessageHTML(article.content)"
      />
      <v-card-text v-if="article.link && isSingleLink">
        <post :article="article.link" />
      </v-card-text>
      <v-card-subtitle v-if="article.link && !isSingleLink">
        (Linked with another post)
      </v-card-subtitle>
      <v-card-actions>
        <v-spacer />
        <v-btn-toggle shaped mandatory>
          <v-btn
            v-if="!isLink"
            title="Comments"
            rounded
            @click="comments = !comments"
          >
            <v-icon>mdi-comment-multiple-outline</v-icon>
            {{ article.children.length }}
          </v-btn>
          <v-btn v-else title="View" rounded nuxt :to="`/post/${article.uuid}`">
            <v-icon>mdi-eye</v-icon>
          </v-btn>
          <v-btn v-if="!isOwner && !isLink" @click="$emit('share', article)">
            <v-icon>mdi-share</v-icon>
          </v-btn>
          <v-menu v-if="isOwner && !isLink" offset-y>
            <template #activator="{ on, attrs }">
              <v-btn title="More" rounded v-bind="attrs" v-on="on">
                <v-icon>mdi-more</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="$emit('share', article)">
                <v-list-item-icon>
                  <v-icon>mdi-share</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Share</v-list-item-content>
              </v-list-item>
              <v-list-item @click="$emit('edit', article)">
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
import Post from '~/components/timeline/Post'

export default {
  name: 'Post',
  components: { Post },
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
    isLink() {
      return this.article.children === undefined
    },
    isSingleLink() {
      return typeof this.article.link !== 'string'
    },
  },
  mounted() {
    this.comments = this.showComments
  },
}
</script>

<style>
.mark-output img {
  max-width: 50%;
}
</style>
