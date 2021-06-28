<template>
  <v-list-item two-line>
    <v-list-item-content>
      <v-list-item-title>
        {{ child.author.display_name }}
      </v-list-item-title>
      <v-list-item-subtitle>
        {{ timeReadable(child.created_time) }}
      </v-list-item-subtitle>
      <v-list-item-action-text>
        {{ child.content }}
      </v-list-item-action-text>
    </v-list-item-content>
  </v-list-item>
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
}
</script>
