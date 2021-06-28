<template>
  <div :class="activeStatus">
    <v-card :disabled="!!active" class="interactive">
      <v-card-title>{{ $store.state.profile.display_name }}</v-card-title>
      <v-card-subtitle>ID: {{ $store.state.profile.identity }}</v-card-subtitle>
      <v-card-text>
        <p>
          IP: {{ $store.state.profile.ip_addr }}<br />
          User-Agent: {{ $store.state.profile.device }}
        </p>
        Created at {{ timeReadable($store.state.profile.created_time) }}
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn color="red" @click="active = 2">Revoke</v-btn>
      </v-card-actions>
    </v-card>
    <div class="component">
      <rename-model v-show="active === 1" @cancel="cancel" @success="$fetch" />
      <revoke-model
        v-show="active === 2"
        @cancel="cancel"
        @success="revokeSuccess"
      />
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import RenameModel from '~/components/user/RenameModel'
import RevokeModel from '~/components/user/RevokeModel'

export default {
  components: { RevokeModel, RenameModel },
  data: () => ({
    active: 0,
    notification: '',
  }),
  async fetch() {
    try {
      const profile = await this.$axios.$get('user.php')
      this.$store.commit('syncProfile', profile)
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
    revokeSuccess() {
      this.$fetch()
      this.cancel()
      this.$store.commit('setNotification', 'Revoke Successful!')
    },
    cancel() {
      this.active = 0
    },
    timeReadable(timestamp) {
      const microTimestamp = timestamp * 1000
      if (moment.now() - microTimestamp < 86400000) {
        return moment(microTimestamp).fromNow()
      } else {
        return moment(microTimestamp).format()
      }
    },
  },
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
