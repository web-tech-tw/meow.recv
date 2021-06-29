<template>
  <div v-if="article" :class="activeStatus">
    <post
      :disabled="!!active"
      class="interactive"
      :article="article"
      :show-comments="true"
      @share="shareArticle"
      @edit="editArticle"
      @delete="deleteArticle"
    >
      <template #top>
        <v-toolbar v-if="article.parent">
          <v-toolbar-title>
            <v-btn nuxt :to="`/post/${article.parent}`" rounded>
              <v-icon>mdi-chevron-left</v-icon>
              Move to parent
            </v-btn>
          </v-toolbar-title>
        </v-toolbar>
      </template>
      <template #comments>
        <v-list class="mx-1">
          <comment
            v-for="(child, index) in article.children"
            :key="index"
            :child="child"
            @share="shareArticle"
            @edit="editArticle"
            @delete="deleteArticle"
          />
          <add-comment :parent="article.uuid" @submit="success" />
        </v-list>
      </template>
    </post>
    <div class="component">
      <share-model
        v-show="active === 1"
        :target="target"
        @cancel="cancel"
        @link="linkArticle"
      />
      <edit-model
        v-show="active === 2"
        :target="target"
        @cancel="cancel"
        @success="success"
      />
      <remove-model
        v-show="active === 3"
        :target="target"
        @cancel="cancel"
        @success="deleteSuccess"
      />
      <link-model
        v-show="active === 4"
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
import ShareModel from '~/components/post/ShareModel'
import EditModel from '~/components/post/EditModel'
import LinkModel from '~/components/post/LinkModel'

export default {
  components: {
    Post,
    Comment,
    AddComment,
    ShareModel,
    EditModel,
    RemoveModel,
    LinkModel,
  },
  data: () => ({
    active: 0,
    target: {},
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
    shareArticle(item) {
      this.callComponent(item, 1)
    },
    editArticle(item) {
      this.callComponent(item, 2)
    },
    deleteArticle(item) {
      this.callComponent(item, 3)
    },
    linkArticle(item) {
      this.callComponent(item, 4)
    },
    callComponent(item, code) {
      this.target = item
      this.active = code
    },
    deleteSuccess() {
      const deleted = this.target.uuid
      this.success()
      if (this.article.uuid === deleted) {
        this.$router.replace('/')
      }
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
