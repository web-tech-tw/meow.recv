<template>
  <v-card>
    <v-card-title>Share</v-card-title>
    <v-card-text>
      <v-text-field
        ref="uri"
        :value="uriLink"
        class="blockquote"
        label="Shine day"
        append-outer-icon="mdi-clipboard-outline"
        @click:append-outer="copy"
      />
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="amber darken-3" @click="$emit('link', target)">
        Or share as new post
      </v-btn>
      <v-btn class="primary" @click="$emit('cancel')">Close</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'ShareModel',
  props: {
    target: {
      type: Object,
      required: true,
    },
  },
  computed: {
    uriLink() {
      if (process.client) {
        return `${location.protocol}//${location.host}/post/${this.target.uuid}`
      } else {
        return this.target.uuid
      }
    },
  },
  methods: {
    copy() {
      const element =
        this.$refs.uri.$el.children[0].children[0].children[0].children[1]
      element.select()
      element.setSelectionRange(0, this.uriLink.length)
      document.execCommand('copy')
      this.$store.commit('setNotification', 'Copied!')
    },
  },
}
</script>
