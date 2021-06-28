<template>
  <div :class="activeStatus">
    <v-row
      :disabled="!!active"
      class="interactive"
      justify="center"
      align="center"
    >
      <v-col
        v-for="(article, key) in articles"
        :key="key"
        class="mb-3"
        cols="12"
        sm="8"
        md="6"
      >
        <post :article="article">
          <v-spacer />
          <v-btn title="Comments" rounded>
            <v-icon>mdi-comment-multiple-outline</v-icon>
          </v-btn>
          <v-menu offset-y>
            <template #activator="{ on, attrs }">
              <v-btn title="More" rounded v-bind="attrs" v-on="on">
                <v-icon>mdi-more</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item v-if="isOwner(article)">
                <v-list-item-icon>
                  <v-icon>mdi-pen</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Edit</v-list-item-content>
              </v-list-item>
              <v-list-item
                v-if="isOwner(article)"
                @click="callComponent(article, 2)"
              >
                <v-list-item-icon>
                  <v-icon>mdi-delete</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Remove</v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
        </post>
        <v-list class="mx-2">
          <comment
            v-for="(child, index) in article.children"
            :key="index"
            :child="child"
          />
          <add-comment :parent="article.uuid" @submit="success" />
        </v-list>
      </v-col>
    </v-row>
    <div class="component">
      <remove-model
        v-show="active === 2"
        :target="target"
        @cancel="cancel"
        @success="success"
      />
    </div>
  </div>
</template>

<script>
import Post from '~/components/timeline/Post'
import Comment from '~/components/timeline/Comment'
import AddComment from '~/components/timeline/AddComment'
import RemoveModel from '~/components/post/RemoveModel'

export default {
  components: { Post, Comment, AddComment, RemoveModel },
  data: () => ({
    active: 0,
    target: {},
    articles: [],
    notification: '',
  }),
  async fetch() {
    try {
      this.articles = await this.$axios.$get('timeline.php')
    } catch (e) {
      if (e.response.status === 401) {
        this.$store.commit('setNotification', 'Unauthenticated')
      }
    }
  },
  computed: {
    activeStatus() {
      return this.active ? 'active' : ''
    },
  },
  methods: {
    callComponent(item, code) {
      this.target = item
      this.active = code
    },
    isOwner(article) {
      return article.author.identity === this.$store.state.profile.identity
    },
    success() {
      this.$fetch()
      this.cancel()
    },
    cancel() {
      this.target = {}
      this.active = 0
    },
  },
  fetchOnServer: false,
}
</script>

<style scoped>
.active .interactive {
  opacity: 0.3;
  filter: blur(3px);
}

.active .component {
  position: absolute;
  top: 50px;
  left: 300px;
  right: 300px;
}

@media screen and (max-width: 1200px) {
  .active .component {
    left: 100px;
    right: 100px;
  }
}

@media screen and (max-width: 600px) {
  .active .component {
    left: 50px;
    right: 50px;
  }
}
</style>
