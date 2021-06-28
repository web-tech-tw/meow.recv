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
        align-self="start"
        class="mb-3"
        cols="12"
        sm="8"
        md="6"
      >
        <post :article="article" @delete="deleteArticle">
          <template #comments>
            <v-list class="mx-1">
              <comment
                v-for="(child, index) in article.children"
                :key="index"
                :child="child"
                @delete="deleteArticle"
              />
              <add-comment :parent="article.uuid" @submit="success" />
            </v-list>
          </template>
        </post>
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
    deleteArticle(item) {
      this.callComponent(item, 2)
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
