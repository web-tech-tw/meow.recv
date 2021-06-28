<template>
  <div v-show="ready" :class="activeStatus">
    <v-row
      :disabled="!!active"
      class="interactive"
      justify="center"
      align="center"
    >
      <v-col
        v-for="(article, key) in articles"
        :key="key"
        align-self="start"
        class="mb-3"
        cols="12"
        sm="8"
        md="6"
      >
        <post
          :article="article"
          @share="shareArticle"
          @edit="editArticle"
          @delete="deleteArticle"
        >
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
      </v-col>
      <v-col v-if="!articles.length">
        <v-card>
          <v-card-title>Empty, Empty, Empty, beep---</v-card-title>
          <v-card-subtitle>There is nothing.</v-card-subtitle>
          <v-card-actions>
            <v-spacer />
            <v-btn class="amber darken-4" nuxt to="/inspire">
              Compose your life
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
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
        @success="success"
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
import ShareModel from '~/components/post/ShareModel'
import EditModel from '~/components/post/EditModel'
import RemoveModel from '~/components/post/RemoveModel'
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
    ready: false,
    active: 0,
    target: {},
    articles: [],
    notification: '',
  }),
  async fetch() {
    try {
      this.articles = await this.$axios.$get('timeline.php')
      this.ready = true
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
