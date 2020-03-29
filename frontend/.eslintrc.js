module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
  },
  parserOptions: {
    parser: 'babel-eslint',
  },
  extends: [
    '@nuxtjs',
    'prettier',
    'prettier/vue',
    'plugin:prettier/recommended',
    'plugin:nuxt/recommended',
  ],
  plugins: [
    'prettier',
  ],
  rules: {
    'no-console': 0,
    'vue/no-v-html': 0,
    'vue/no-use-v-if-with-v-for': 1,
    'vue/no-side-effects-in-computed-properties': 1,
  },
}
