<template>
  <v-card>
    <v-card-title>{{ article.author.display_name }}</v-card-title>
    <v-card-subtitle>
      {{ timeReadable(article.created_time) }}
    </v-card-subtitle>
    <v-card-text v-html="getMessageHTML(article.content)" />
    <v-card-actions>
      <slot />
    </v-card-actions>
  </v-card>
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
  },
  data: () => ({ timeReadable, getMessageHTML }),
}
</script>
