<template>
  <div :class="activeStatus">
    <v-card :disabled="!!active" class="interactive">
      <v-card-title>{{ profile.display_name }}</v-card-title>
      <v-card-subtitle>ID: {{ profile.identity }}</v-card-subtitle>
      <v-card-text>
        <p>
          IP: {{ profile.ip_addr }}<br />
          User-Agent: {{ profile.device }}
        </p>
        Created at {{ timeReadable(profile.created_time) }}
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn color="red" @click="active = 2">Revoke</v-btn>
      </v-card-actions>
    </v-card>
    <div id="component">
      <rename-model v-show="active === 1" @cancel="cancel" @success="$fetch" />
      <revoke-model v-show="active === 2" @cancel="cancel" @success="$fetch" />
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import RenameModel from '~/components/RenameModel'
import RevokeModel from '~/components/RevokeModel'

export default {
  components: { RevokeModel, RenameModel },
  data: () => ({
    active: 0,
    profile: {},
  }),
  async fetch() {
    try {
      this.profile = await this.$axios.$get('user.php')
    } catch (e) {
      if (e.response.status === 401) {
        await this.$router.replace('/signup')
      }
    }
  },
  computed: {
    activeStatus() {
      return this.active ? 'active' : ''
    },
  },
  methods: {
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
