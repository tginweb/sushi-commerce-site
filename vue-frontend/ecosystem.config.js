module.exports = {
  apps: [
    {
      name: "ssr-main",
      script: "dist/ssr/index.js",
      instances: "max", // Используем все доступные CPU ядра
      exec_mode: "cluster",
      env: {
        NODE_ENV: "production",
        PORT: 3000,
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
        SSR_CACHE_SECRET: process.env.SSR_CACHE_SECRET || "your_secret_key",
      },
      env_production: {
        NODE_ENV: "production",
        PORT: 3000,
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
        SSR_CACHE_SECRET: process.env.SSR_CACHE_SECRET || "your_secret_key",
      },
      // Настройки для балансировки
      max_memory_restart: "1G",
      node_args: "--max-old-space-size=1024",
      // Автоматический перезапуск при ошибках
      autorestart: true,
      watch: false,
      max_restarts: 10,
      min_uptime: "10s",
      // Логирование
      log_file: "./logs/ssr-main.log",
      out_file: "./logs/ssr-main-out.log",
      error_file: "./logs/ssr-main-error.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss Z",
      // Мониторинг
      merge_logs: true,
      // Настройки кластера
      kill_timeout: 5000,
      wait_ready: true,
      listen_timeout: 10000,
    },
    {
      name: "ssr-service", // Специальная нода для служебных запросов
      script: "dist/ssr/index.js",
      instances: 1, // Только один инстанс для служебных запросов
      exec_mode: "cluster",
      env: {
        NODE_ENV: "production",
        PORT: 3001, // Отдельный порт для служебной ноды
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
        SSR_CACHE_SECRET: process.env.SSR_CACHE_SECRET || "your_secret_key",
        SSR_SERVICE_NODE: "true", // Флаг для служебной ноды
      },
      env_production: {
        NODE_ENV: "production",
        PORT: 3001,
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
        SSR_CACHE_SECRET: process.env.SSR_CACHE_SECRET || "your_secret_key",
        SSR_SERVICE_NODE: "true",
      },
      // Настройки для служебной ноды
      max_memory_restart: "2G", // Больше памяти для обработки
      node_args: "--max-old-space-size=2048",
      // Автоматический перезапуск
      autorestart: true,
      watch: false,
      max_restarts: 5,
      min_uptime: "10s",
      // Логирование
      log_file: "./logs/ssr-service.log",
      out_file: "./logs/ssr-service-out.log",
      error_file: "./logs/ssr-service-error.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss Z",
      // Мониторинг
      merge_logs: true,
      // Настройки кластера
      kill_timeout: 5000,
      wait_ready: true,
      listen_timeout: 10000,
    },
    {
      name: "ssr-crawler", // Отдельный процесс для crawler
      script: "dist/ssr/crawler/index.js",
      instances: 1,
      exec_mode: "fork",
      env: {
        NODE_ENV: "production",
        CRAWLER_API_URL: "http://localhost:3001/api/cache-urls",
        CRAWLER_TARGET_NODE: "http://localhost:3001",
        CRAWLER_CONCURRENCY: "5",
        CRAWLER_TIMEOUT: "30000",
        CRAWLER_CHECK_INTERVAL: "5", // Проверка каждые 5 минут
        CRAWLER_REFRESH_THRESHOLD: "10", // Обновлять за 10 минут до истечения
        USE_SITEMAP: "true", // Использовать sitemap.xml
        SITEMAP_URL:
          process.env.SITEMAP_URL || "https://your-domain.com/sitemap.xml",
        SITEMAP_TIMEOUT: "30000",
        SITEMAP_MAX_DEPTH: "3",
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
      },
      env_production: {
        NODE_ENV: "production",
        CRAWLER_API_URL: "http://localhost:3001/api/cache-urls",
        CRAWLER_TARGET_NODE: "http://localhost:3001",
        CRAWLER_CONCURRENCY: "5",
        CRAWLER_TIMEOUT: "30000",
        CRAWLER_CHECK_INTERVAL: "5",
        CRAWLER_REFRESH_THRESHOLD: "10",
        USE_SITEMAP: "true",
        SITEMAP_URL:
          process.env.SITEMAP_URL || "https://your-domain.com/sitemap.xml",
        SITEMAP_TIMEOUT: "30000",
        SITEMAP_MAX_DEPTH: "3",
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
      },
      // Настройки для crawler
      max_memory_restart: "512M",
      node_args: "--max-old-space-size=512",
      // Автоматический перезапуск
      autorestart: true,
      watch: false,
      max_restarts: 3,
      min_uptime: "10s",
      // Логирование
      log_file: "./logs/ssr-crawler.log",
      out_file: "./logs/ssr-crawler-out.log",
      error_file: "./logs/ssr-crawler-error.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss Z",
      // Мониторинг
      merge_logs: true,
    },
    {
      name: "ssr-crawler-monitor", // Отдельный процесс для мониторинга кеша
      script: "dist/ssr/crawler/index.js",
      instances: 1,
      exec_mode: "fork",
      args: "monitor", // Запуск в режиме мониторинга
      env: {
        NODE_ENV: "production",
        CRAWLER_API_URL: "http://localhost:3001/api/cache-urls",
        CRAWLER_TARGET_NODE: "http://localhost:3001",
        CRAWLER_CONCURRENCY: "3", // Меньше параллельности для мониторинга
        CRAWLER_TIMEOUT: "30000",
        CRAWLER_CHECK_INTERVAL: "5", // Проверка каждые 5 минут
        CRAWLER_REFRESH_THRESHOLD: "10",
        USE_SITEMAP: "true",
        SITEMAP_URL:
          process.env.SITEMAP_URL || "https://your-domain.com/sitemap.xml",
        SITEMAP_TIMEOUT: "30000",
        SITEMAP_MAX_DEPTH: "3",
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
      },
      env_production: {
        NODE_ENV: "production",
        CRAWLER_API_URL: "http://localhost:3001/api/cache-urls",
        CRAWLER_TARGET_NODE: "http://localhost:3001",
        CRAWLER_CONCURRENCY: "3",
        CRAWLER_TIMEOUT: "30000",
        CRAWLER_CHECK_INTERVAL: "5",
        CRAWLER_REFRESH_THRESHOLD: "10",
        USE_SITEMAP: "true",
        SITEMAP_URL:
          process.env.SITEMAP_URL || "https://your-domain.com/sitemap.xml",
        SITEMAP_TIMEOUT: "30000",
        SITEMAP_MAX_DEPTH: "3",
        REDIS_URL: process.env.REDIS_URL || "redis://localhost:6379",
      },
      // Настройки для мониторинга
      max_memory_restart: "256M",
      node_args: "--max-old-space-size=256",
      // Автоматический перезапуск
      autorestart: true,
      watch: false,
      max_restarts: 5,
      min_uptime: "10s",
      // Логирование
      log_file: "./logs/ssr-crawler-monitor.log",
      out_file: "./logs/ssr-crawler-monitor-out.log",
      error_file: "./logs/ssr-crawler-monitor-error.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss Z",
      // Мониторинг
      merge_logs: true,
    },
  ],
};
