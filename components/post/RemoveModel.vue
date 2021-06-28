<template>
  <v-card>
    <v-card-title>Remove</v-card-title>
    <v-card-text>You will lost the control of this identity.</v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn class="grey" @click="$emit('cancel')">Cancel</v-btn>
      <v-btn class="red" @click="remove">Remove</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'RemoveModel',
  props: {
    target: {
      type: Object,
      required: true,
    },
  },
  methods: {
    async remove() {
      await this.$axios.$delete(`post.php?uuid=${this.target.uuid}`)
      this.$emit('success')
    },
  },
}
</script>
