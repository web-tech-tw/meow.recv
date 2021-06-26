<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <v-card v-for="(article, key) in articles" :key="key" class="mb-3">
        <v-card-text>
          {{ article.content }}
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="primary" nuxt to="/inspire"> Continue </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: () => ({
    articles: [],
  }),
  async fetch() {
    this.articles = await this.$axios.$get('timeline.php')
  },
  fetchOnServer: false,
}
</script>
