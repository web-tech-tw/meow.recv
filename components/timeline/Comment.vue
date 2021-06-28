<template>
  <v-hover v-slot="{ hover }">
    <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title>
          {{ child.author.display_name }}
        </v-list-item-title>
        <v-list-item-subtitle>
          {{ child.content }}
        </v-list-item-subtitle>
        <v-list-item-action-text>
          {{ timeReadable(child.created_time) }}
        </v-list-item-action-text>
      </v-list-item-content>
      <v-list-item-action v-show="hover">
        <v-btn-toggle>
          <v-btn title="View" rounded small nuxt :to="`/post/${child.uuid}`">
            <v-icon>mdi-comment-multiple-outline</v-icon>
          </v-btn>
          <v-menu v-if="isOwner" offset-y>
            <template #activator="{ on, attrs }">
              <v-btn small title="More" rounded v-bind="attrs" v-on="on">
                <v-icon>mdi-more</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="$emit('share', child)">
                <v-list-item-icon>
                  <v-icon>mdi-share</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Share</v-list-item-content>
              </v-list-item>
              <v-list-item @click="$emit('edit', child)">
                <v-list-item-icon>
                  <v-icon>mdi-pen</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Edit</v-list-item-content>
              </v-list-item>
              <v-list-item @click="$emit('delete', child)">
                <v-list-item-icon>
                  <v-icon>mdi-delete</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Remove</v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-btn-toggle>
      </v-list-item-action>
    </v-list-item>
  </v-hover>
</template>

<script>
import { timeReadable } from '~/utils/content'

export default {
  name: 'Comment',
  props: {
    child: {
      type: Object,
      required: true,
    },
  },
  data: () => ({ timeReadable }),
  computed: {
    isOwner() {
      return this.child.author.identity === this.$store.state.profile.identity
    },
  },
}
</script>
