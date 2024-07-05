<template>
  <div>
    <h2>Consulta de Engines da OpenAI</h2>
    <form @submit.prevent="validateAndSubmit">
      <label for="api_key">Digite sua chave de API:</label>
      <input type="text" id="api_key" v-model="apiKey" required>
      <button type="submit">Enviar</button>
    </form>
    <div v-if="engines.length > 0">
      <h3>Engines disponíveis:</h3>
      <ul>
        <li v-for="engine in engines" :key="engine.id">
          {{ engine.id }}: {{ engine.object }}
        </li>
      </ul>
    </div>
    <div v-if="error">
      <p>{{ error }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      apiKey: '',
      engines: [],
      error: ''
    }
  },
  methods: {
    validateAndSubmit() {
      if (this.apiKey.startsWith('sk-')) {
        this.fetchEngines()
      } else {
        this.error = 'Chave de API digitada incorretamente. Deve começar com "sk-".'
      }
    },
    async fetchEngines() {
      try {
        const response = await axios.get('https://api.openai.com/v1/engines', {
          headers: {
            'Authorization': `Bearer ${this.apiKey}`
          }
        })
        this.engines = response.data.data
      } catch (error) {
        this.error = 'Erro ao fazer a solicitação. Veja o log para mais detalhes.'
        console.error(error)
      }
    }
  }
}
</script>

