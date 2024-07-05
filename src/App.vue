<template>
  <div id="app">
    <OpenAIEngines />
  </div>
</template>

<script>
import OpenAIEngines from './components/OpenAIEngines.vue'

export default {
  name: 'App',
  components: {
    OpenAIEngines
  }
}
</script>

<style>
#app {
  font-family: 'Roboto Slab', serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>

