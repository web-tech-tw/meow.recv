<template>
  <v-app dark>
    <v-app-bar fixed app>
      <v-toolbar-title>melody.proto</v-toolbar-title>
      <v-spacer />
    </v-app-bar>
    <v-main>
      <nuxt />
    </v-main>
    <v-footer height="70px" app>
      <v-row justify="space-around" align="center">
        <v-btn icon x-large nuxt to="/">
          <v-icon large>mdi-contacts</v-icon>
        </v-btn>
        <v-btn icon x-large nuxt to="/inspire">
          <v-icon large>mdi-plus</v-icon>
        </v-btn>
        <v-btn icon x-large nuxt to="/user">
          <v-icon large>mdi-cog</v-icon>
        </v-btn>
      </v-row>
    </v-footer>
  </v-app>
</template>
