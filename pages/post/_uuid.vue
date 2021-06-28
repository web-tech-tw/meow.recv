<template>
  <div v-if="article" :class="activeStatus">
    <post
      :disabled="!!active"
      class="interactive"
      :article="article"
      :show-comments="true"
      @delete="deleteArticle"
    >
      <template #comments>
        <v-list class="mx-1">
          <comment
            v-for="(child, index) in article.children"
            :key="index"
            :child="child"
          />
          <add-comment :parent="article.uuid" @submit="success" />
        </v-list>
      </template>
    </post>
    <div class="component">
      <remove-model
        v-show="active === 2"
        :target="article"
        @cancel="cancel"
        @success="deleteSuccess"
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
    article: null,
  }),
  async fetch() {
    try {
      this.article = await this.$axios.$get(
        `post.php?uuid=${this.$route.params.uuid}`
      )
    } catch (e) {
      if (e.response.status === 401) {
        this.$store.commit('setNotification', 'Unauthenticated')
      }
      if (e.response.status === 404) {
        this.$store.commit('setNotification', 'Not Found')
      }
    }
  },
  computed: {
    activeStatus() {
      return this.active ? 'active' : ''
    },
  },
  methods: {
    deleteArticle(item) {
      this.callComponent(item, 2)
    },
    callComponent(item, code) {
      this.target = item
      this.active = code
    },
    deleteSuccess() {
      this.success()
      this.$router.replace('/')
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
