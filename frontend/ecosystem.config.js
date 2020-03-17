const prod = process.argv.indexOf('production') !== -1;

module.exports = {
  apps: [{
    name: 'site',
    exec_mode: 'cluster',
    script: './node_modules/nuxt/bin/nuxt.js',
    args: prod
      ? 'start --config-file nuxt.config.js'
      : '--config-file nuxt.config.js',
    instances: 2,
    autorestart: true,
    max_memory_restart: '1G',
    env_production: {
      NODE_ENV: 'production',
      watch: false,
    },
    env_development: {
      NODE_ENV: 'development',
      watch: true,
    },
  }],
};
